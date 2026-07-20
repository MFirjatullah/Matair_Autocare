<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Service;
use App\Models\Rating;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    // ── Dashboard ────────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total_reservations' => Reservation::count(),
            'pending'            => Reservation::where('status', 'pending')->count(),
            'completed'          => Reservation::where('status', 'completed')->count(),
            'total_customers'    => User::where('role', 'customer')->count(),
            'total_karyawan'     => User::where('role', 'karyawan')->count(),
            'revenue'            => Reservation::where('status', 'completed')->sum('total_price'),
            'unassigned'         => Reservation::whereNull('karyawan_id')
                                       ->whereNotIn('status', ['cancelled','completed'])->count(),
        ];

        $recent = Reservation::with(['user', 'service', 'karyawan'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent'));
    }

    // ── Kelola Reservasi ─────────────────────────────────────
    public function reservations(Request $request)
    {
        $query = Reservation::with(['user', 'service', 'karyawan']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date')) {
            $query->whereDate('reservation_date', $request->date);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('customer_name', 'like', "%{$request->search}%")
                  ->orWhere('plate_number',   'like', "%{$request->search}%")
                  ->orWhere('customer_phone', 'like', "%{$request->search}%");
            });
        }

        $reservations = $query->orderByDesc('created_at')->paginate(15);
        $karyawanList = User::where('role', 'karyawan')->where('is_available', 1)->get();

        return view('admin.reservations', compact('reservations', 'karyawanList'));
    }

    // ── Update Status ────────────────────────────────────────
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
        ]);

        $oldStatus = $reservation->status;
        $reservation->update(['status' => $request->status]);

        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            $reservation->user->recordWash();
        }

        if ($request->status === 'cancelled' && $oldStatus === 'completed') {
            $user = $reservation->user;
            if ($user->wash_count > 0) $user->decrement('wash_count');
            if ($user->wash_count % 10 !== 0 && $user->free_wash_earned > 0) {
                $user->decrement('free_wash_earned');
            }
        }

        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    // ── Assign Karyawan ──────────────────────────────────────
    public function assignKaryawan(Request $request, Reservation $reservation)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:users,id',
        ], [
            'karyawan_id.required' => 'Pilih karyawan terlebih dahulu.',
        ]);

        $karyawan = User::findOrFail($request->karyawan_id);

        if ($karyawan->role !== 'karyawan') {
            return back()->with('error', 'User yang dipilih bukan karyawan.');
        }

        $reservation->update([
            'karyawan_id'   => $karyawan->id,
            'assign_status' => 'waiting',
            'reject_reason' => null,
        ]);

        return back()->with('success', "Reservasi berhasil ditugaskan ke {$karyawan->name}.");
    }

    // ── Reassign Karyawan ────────────────────────────────────
    public function reassignKaryawan(Request $request, Reservation $reservation)
    {
        $request->validate([
            'karyawan_id' => 'required|exists:users,id',
        ]);

        $karyawan = User::findOrFail($request->karyawan_id);

        $reservation->update([
            'karyawan_id'   => $karyawan->id,
            'assign_status' => 'waiting',
            'reject_reason' => null,
        ]);

        return back()->with('success', "Reservasi berhasil dialihkan ke {$karyawan->name}.");
    }

    // ── Kelola Pelanggan ─────────────────────────────────────
    public function customers()
    {
        $customers = User::where('role', 'customer')
            ->withCount('reservations')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('admin.customers', compact('customers'));
    }

    // ── Kelola Karyawan ──────────────────────────────────────
    public function karyawan()
    {
        $karyawanList = User::where('role', 'karyawan')
            ->withCount('assignedReservations')
            ->orderByDesc('rating_avg')
            ->paginate(20);

        return view('admin.karyawan', compact('karyawanList'));
    }

    public function karyawanStore(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'email'          => 'required|email|unique:users',
            'phone'          => 'required|string|max:20',
            'specialization' => 'required|in:detailing,carwash,keduanya',
            'password'       => 'required|min:6',
        ]);

        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'role'           => 'karyawan',
            'specialization' => $request->specialization,
            'is_available'   => 1,
            'password'       => bcrypt($request->password),
        ]);

        return back()->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function karyawanDestroy(User $user)
    {
        if ($user->role !== 'karyawan') abort(403);
        $user->delete();
        return back()->with('success', 'Karyawan berhasil dihapus.');
    }

    // ── Kelola Layanan ───────────────────────────────────────
    public function services()
    {
        $services = Service::orderBy('category')->orderBy('name')->get();
        return view('admin.services', compact('services'));
    }

    public function toggleService(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        return back()->with('success', 'Status layanan berhasil diubah.');
    }

    // ── Laporan ──────────────────────────────────────────────
    public function laporanIndex(Request $request)
    {
        $query = Reservation::with(['user', 'service', 'karyawan'])
            ->where('status', 'completed');

        if ($request->filled('start_date')) {
            $query->whereDate('reservation_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('reservation_date', '<=', $request->end_date);
        }
        if ($request->filled('category')) {
            $query->whereHas('service', function($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        $reservations = $query->orderByDesc('reservation_date')->get();
        $totalRevenue = $reservations->sum('total_price');
        $totalCount   = $reservations->count();

        return view('admin.laporan', compact('reservations', 'totalRevenue', 'totalCount'));
    }

    public function laporanExportPdf(Request $request)
    {
        $query = Reservation::with(['user', 'service', 'karyawan'])
            ->where('status', 'completed');

        if ($request->filled('start_date')) {
            $query->whereDate('reservation_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('reservation_date', '<=', $request->end_date);
        }

        $reservations = $query->orderByDesc('reservation_date')->get();
        $totalRevenue = $reservations->sum('total_price');
        $totalCount   = $reservations->count();
        $startDate    = $request->start_date ?? 'Semua';
        $endDate      = $request->end_date   ?? 'Semua';

        $pdf = Pdf::loadView('admin.laporan-pdf', compact(
            'reservations', 'totalRevenue', 'totalCount', 'startDate', 'endDate'
        ))->setPaper('a4', 'landscape');

        return $pdf->download('laporan-transaksi-' . now()->format('Y-m-d') . '.pdf');
    }
}