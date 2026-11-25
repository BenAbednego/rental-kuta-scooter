<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        "name_id",
        "name_en",
        "subtitle_id",
        "subtitle_en",
        "price",
        "price_unit_id",
        "price_unit_en",
        "benefits_id",
        "benefits_en",
        "icon",
        "cta_label_id",
        "cta_label_en",
        "is_featured",
        "order",
        "is_active",
    ];

    protected function casts(): array
    {
        return [
            "benefits_id" => "array",
            "benefits_en" => "array",
            "is_featured" => "boolean",
            "is_active" => "boolean",
            "order" => "integer",
            "price" => "decimal:2",
        ];
    }
}
