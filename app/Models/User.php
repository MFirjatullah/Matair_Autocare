<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'role', 'password',
        'specialization', 'is_available',
        'rating_avg', 'rating_count',
        'wash_count', 'free_wash_earned', 'free_wash_used',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_available'      => 'boolean',
    ];

    // ── Role checks ──────────────────────────────────────────
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKaryawan(): bool
    {
        return $this->role === 'karyawan';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // ── Loyalty ──────────────────────────────────────────────
    public function freeWashAvailable(): int
    {
        return max(0, $this->free_wash_earned - $this->free_wash_used);
    }

    public function washesUntilNextFree(): int
    {
        return 10 - ($this->wash_count % 10);
    }

    public function loyaltyProgress(): int
    {
        return ($this->wash_count % 10) * 10;
    }

    public function recordWash(): void
    {
        $this->increment('wash_count');
        if ($this->wash_count % 10 === 0) {
            $this->increment('free_wash_earned');
        }
    }

    // ── Rating ───────────────────────────────────────────────
    public function updateRating(): void
    {
        $avg   = $this->ratingsReceived()->avg('rating') ?? 0;
        $count = $this->ratingsReceived()->count();
        $this->update([
            'rating_avg'   => round($avg, 2),
            'rating_count' => $count,
        ]);
    }

    public function formattedRating(): string
    {
        if ($this->rating_count === 0) return 'Belum ada rating';
        return number_format($this->rating_avg, 1) . ' ★ (' . $this->rating_count . ' ulasan)';
    }

    // ── Specialization label ─────────────────────────────────
    public function specializationLabel(): string
    {
        return match($this->specialization) {
            'detailing' => 'Detailing',
            'carwash'   => 'Carwash',
            'keduanya'  => 'Detailing & Carwash',
            default     => '-',
        };
    }

    // ── Relations ────────────────────────────────────────────
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }

    public function assignedReservations()
    {
        return $this->hasMany(Reservation::class, 'karyawan_id');
    }

    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'karyawan_id');
    }

    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'customer_id');
    }
}