<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    // ── Form rating ──────────────────────────────────────────
    public function create(Reservation $reservation)
    {
        // Hanya pemilik reservasi
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        // Hanya reservasi yang sudah selesai dan belum dirating
        if (!$reservation->canBeRated()) {
            return redirect()->route('reservasi.history')
                ->with('error', 'Reservasi ini tidak dapat diberi rating.');
        }

        return view('customer.rating', compact('reservation'));
    }

    // ── Simpan rating ────────────────────────────────────────
    public function store(Request $request, Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$reservation->canBeRated()) {
            return redirect()->route('reservasi.history')
                ->with('error', 'Reservasi ini tidak dapat diberi rating.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'ulasan' => 'nullable|string|max:500',
        ], [
            'rating.required' => 'Rating wajib dipilih.',
            'rating.min'      => 'Rating minimal 1 bintang.',
            'rating.max'      => 'Rating maksimal 5 bintang.',
        ]);

        Rating::create([
            'reservation_id' => $reservation->id,
            'customer_id'    => Auth::id(),
            'karyawan_id'    => $reservation->karyawan_id,
            'rating'         => $request->rating,
            'ulasan'         => $request->ulasan,
        ]);

        // Update rata-rata rating karyawan
        $reservation->karyawan->updateRating();

        return redirect()->route('reservasi.history')
            ->with('success', 'Terima kasih! Rating Anda telah disimpan.');
    }
}