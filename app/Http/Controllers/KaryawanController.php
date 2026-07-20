<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanController extends Controller
{
    // ── Dashboard karyawan ───────────────────────────────────
    public function dashboard()
    {
        $karyawan = Auth::user();

        $stats = [
            'menunggu'    => Reservation::where('karyawan_id', $karyawan->id)
                                ->where('assign_status', 'waiting')->count(),
            'diterima'    => Reservation::where('karyawan_id', $karyawan->id)
                                ->where('assign_status', 'accepted')
                                ->whereIn('status', ['confirmed','in_progress'])->count(),
            'selesai'     => Reservation::where('karyawan_id', $karyawan->id)
                                ->where('status', 'completed')->count(),
            'rating'      => $karyawan->rating_avg,
            'rating_count'=> $karyawan->rating_count,
        ];

        // Pesanan menunggu konfirmasi
        $pending = Reservation::with(['user', 'service'])
            ->where('karyawan_id', $karyawan->id)
            ->where('assign_status', 'waiting')
            ->orderBy('reservation_date')
            ->get();

        // Pesanan aktif (diterima)
        $aktif = Reservation::with(['user', 'service'])
            ->where('karyawan_id', $karyawan->id)
            ->where('assign_status', 'accepted')
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->orderBy('reservation_date')
            ->get();

        // Riwayat pesanan selesai
        $riwayat = Reservation::with(['user', 'service', 'rating'])
            ->where('karyawan_id', $karyawan->id)
            ->where('status', 'completed')
            ->orderByDesc('reservation_date')
            ->limit(10)
            ->get();

        return view('karyawan.dashboard', compact(
            'karyawan', 'stats', 'pending', 'aktif', 'riwayat'
        ));
    }

    // ── Terima pesanan ───────────────────────────────────────
    public function terima(Reservation $reservation)
    {
        // Pastikan pesanan ini memang ditugaskan ke karyawan ini
        if ($reservation->karyawan_id !== Auth::id()) {
            abort(403);
        }

        if ($reservation->assign_status !== 'waiting') {
            return back()->with('error', 'Pesanan ini sudah diproses.');
        }

        $reservation->update([
            'assign_status' => 'accepted',
            'status'        => 'confirmed',
        ]);

        return back()->with('success', 'Pesanan berhasil diterima!');
    }

    // ── Tolak pesanan ────────────────────────────────────────
    public function tolak(Request $request, Reservation $reservation)
    {
        if ($reservation->karyawan_id !== Auth::id()) {
            abort(403);
        }

        if ($reservation->assign_status !== 'waiting') {
            return back()->with('error', 'Pesanan ini sudah diproses.');
        }

        $request->validate([
            'reject_reason' => 'required|string|max:500',
        ], [
            'reject_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $reservation->update([
            'assign_status' => 'rejected',
            'reject_reason' => $request->reject_reason,
            'karyawan_id'   => null,
            'status'        => 'pending',
        ]);

        return back()->with('success', 'Pesanan telah ditolak. Admin akan menugaskan karyawan lain.');
    }

    // ── Update status pesanan ────────────────────────────────
    public function updateStatus(Request $request, Reservation $reservation)
    {
        if ($reservation->karyawan_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:in_progress,completed',
        ]);

        $oldStatus = $reservation->status;
        $reservation->update(['status' => $request->status]);

        // Jika selesai, tambah loyalty point customer
        if ($request->status === 'completed' && $oldStatus !== 'completed') {
            $reservation->user->recordWash();
        }

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    // ── Toggle ketersediaan ──────────────────────────────────
    public function toggleAvailability()
    {
        $karyawan = Auth::user();
        $karyawan->update(['is_available' => !$karyawan->is_available]);

        $status = $karyawan->is_available ? 'Tersedia' : 'Tidak Tersedia';
        return back()->with('success', "Status Anda diubah menjadi: {$status}");
    }
}