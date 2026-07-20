<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $services  = Service::where('is_active', true)->get()->groupBy('category');
        $user      = Auth::user();

        // Ambil karyawan yang tersedia
        $karyawanList = User::where('role', 'karyawan')
            ->where('is_available', 1)
            ->orderByDesc('rating_avg')
            ->get();

        return view('reservasi', compact('services', 'user', 'karyawanList'));
    }

    public function checkSlots(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
        ]);

        $times = [
            '08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30',
            '13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30'
        ];

        $maxSlots = 15;
        $result   = [];

        foreach ($times as $time) {
            $booked = Reservation::where('reservation_date', $request->date)
                ->where('reservation_time', $time)
                ->whereNotIn('status', ['cancelled'])
                ->count();

            $result[] = [
                'time'      => $time,
                'booked'    => $booked,
                'available' => $maxSlots - $booked,
                'is_full'   => $booked >= $maxSlots,
            ];
        }

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name'    => 'required|string|max:255',
            'customer_phone'   => 'required|string|max:20',
            'customer_email'   => 'required|email',
            'vehicle_type'     => 'required|string',
            'car_brand'        => 'required|string|max:100',
            'plate_number'     => 'required|string|max:20',
            'service_id'       => 'required|exists:services,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'reservation_time' => 'required',
            'notes'            => 'nullable|string|max:500',
            'karyawan_id'      => 'nullable|exists:users,id',
        ]);

        // Cek slot tersedia
        $maxSlots = 15;
        $booked   = Reservation::where('reservation_date', $request->reservation_date)
            ->where('reservation_time', $request->reservation_time)
            ->whereNotIn('status', ['cancelled'])
            ->count();

        if ($booked >= $maxSlots) {
            return back()->withInput()->withErrors([
                'reservation_time' => 'Maaf, slot waktu ' . $request->reservation_time . ' sudah penuh.',
            ]);
        }

        $user          = Auth::user();
        $service       = Service::findOrFail($request->service_id);
        $isRegularWash = strtolower($service->name) === 'regular wash';
        $useFreeWash   = $request->boolean('use_free_wash')
                         && $user->freeWashAvailable() > 0
                         && $isRegularWash;
        $totalPrice    = $useFreeWash ? 0 : $service->price;

        // Validasi karyawan jika dipilih
        $karyawanId    = null;
        $assignStatus  = 'waiting';

        if ($request->filled('karyawan_id')) {
            $karyawan = User::where('id', $request->karyawan_id)
                ->where('role', 'karyawan')
                ->where('is_available', 1)
                ->first();

            if ($karyawan) {
                $karyawanId = $karyawan->id;
            }
        }

        $reservation = Reservation::create([
            'user_id'          => $user->id,
            'service_id'       => $service->id,
            'karyawan_id'      => $karyawanId,
            'customer_name'    => $request->customer_name,
            'customer_phone'   => $request->customer_phone,
            'customer_email'   => $request->customer_email,
            'vehicle_type'     => $request->vehicle_type,
            'car_brand'        => $request->car_brand,
            'plate_number'     => strtoupper($request->plate_number),
            'reservation_date' => $request->reservation_date,
            'reservation_time' => $request->reservation_time,
            'notes'            => $request->notes,
            'status'           => 'pending',
            'assign_status'    => $karyawanId ? 'waiting' : 'waiting',
            'use_free_wash'    => $useFreeWash,
            'total_price'      => $totalPrice,
        ]);

        if ($useFreeWash) {
            $user->increment('free_wash_used');
        }

        return redirect()->route('reservasi.success', $reservation)
            ->with('success', 'Reservasi berhasil dibuat!');
    }

    public function success(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) abort(403);
        return view('reservasi-success', compact('reservation'));
    }

    public function history()
    {
        $reservations = Auth::user()->reservations()
            ->with(['service', 'karyawan', 'rating'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('customer.history', compact('reservations'));
    }
}