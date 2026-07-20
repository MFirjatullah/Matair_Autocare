<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id', 'service_id', 'karyawan_id',
        'customer_name', 'customer_phone', 'customer_email',
        'vehicle_type', 'car_brand', 'plate_number',
        'reservation_date', 'reservation_time', 'notes',
        'status', 'assign_status', 'reject_reason',
        'use_free_wash', 'total_price',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'use_free_wash'    => 'boolean',
    ];

    // ── Relations ────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function karyawan()
    {
        return $this->belongsTo(User::class, 'karyawan_id');
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    // ── Helpers ──────────────────────────────────────────────
    public function formattedPrice(): string
    {
        return 'Rp ' . number_format($this->total_price, 0, ',', '.');
    }

    public function statusLabel(): string
    {
        return match($this->status) {
            'pending'     => 'Menunggu',
            'confirmed'   => 'Dikonfirmasi',
            'in_progress' => 'Diproses',
            'completed'   => 'Selesai',
            'cancelled'   => 'Dibatalkan',
            default       => $this->status,
        };
    }

    public function assignStatusLabel(): string
    {
        return match($this->assign_status) {
            'waiting'    => 'Menunggu Konfirmasi',
            'accepted'   => 'Diterima',
            'rejected'   => 'Ditolak',
            'reassigned' => 'Dialihkan',
            default      => $this->assign_status,
        };
    }

    public function hasRating(): bool
    {
        return $this->rating()->exists();
    }

    public function canBeRated(): bool
    {
        return $this->status === 'completed'
            && $this->karyawan_id !== null
            && !$this->hasRating();
    }
}