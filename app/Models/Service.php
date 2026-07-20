<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'category', 'name', 'size', 'price', 'description', 'is_active',
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function formattedPrice(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}