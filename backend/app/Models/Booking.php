<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'total_days' => 'integer',
        'total_price' => 'decimal:2',
        'addons' => 'array',
        'addons_total' => 'decimal:2',
    ];

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }

    /**
     * Get sanitized customer WhatsApp number in international format
     * Example: '082380753577' -> '6282380753577'
     */
    public function getCleanWhatsappAttribute(): string
    {
        $clean = preg_replace('/\D/', '', $this->customer_whatsapp ?? '');
        if (str_starts_with($clean, '0')) {
            $clean = '62'.substr($clean, 1);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '62'.$clean;
        }

        return $clean;
    }
}
