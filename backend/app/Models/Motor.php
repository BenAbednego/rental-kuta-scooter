<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    protected $guarded = [];

    protected $casts = [
        'price_daily' => 'decimal:2',
        'price_weekly' => 'decimal:2',
        'price_monthly' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'unit_count' => 'integer',
        'cc' => 'integer',
        'gallery_images' => 'array',
        'specs' => 'array',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
