<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    use HasFactory;

    protected $table = 'business_settings';

    protected $fillable = [
        'whatsapp_primary',
        'whatsapp_secondary',
        'business_email',
        'business_address',
        'operating_hours_id',
        'operating_hours_en',
        'instagram_url',
    ];

    /**
     * Sanitize phone number to standard international WhatsApp format without '+'
     * Example: '0859-4716-7662' -> '6285947167662'
     *          '+62 823 8075 3577' -> '6282380753577'
     */
    public static function sanitizePhone(?string $phone): ?string
    {
        if (blank($phone)) {
            return null;
        }

        $clean = preg_replace('/\D/', '', $phone);
        if (str_starts_with($clean, '0')) {
            $clean = '62'.substr($clean, 1);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '62'.$clean;
        }

        return $clean;
    }

    public function setWhatsappPrimaryAttribute($value): void
    {
        $this->attributes['whatsapp_primary'] = self::sanitizePhone($value);
    }

    public function setWhatsappSecondaryAttribute($value): void
    {
        $this->attributes['whatsapp_secondary'] = self::sanitizePhone($value);
    }
}
