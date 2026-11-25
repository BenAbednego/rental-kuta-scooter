<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewSetting extends Model
{
    protected $fillable = [
        'display_mode',
        'iframe_src',
        'iframe_provider',
        'iframe_title',
        'iframe_width',
        'iframe_height',
    ];

    protected function casts(): array
    {
        return [
            'iframe_width' => 'integer',
            'iframe_height' => 'integer',
        ];
    }
}
