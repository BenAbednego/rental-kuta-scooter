<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        "image_path",
        "caption",
        "order",
        "is_active",
    ];

    protected $appends = ["image_url"];

    protected function casts(): array
    {
        return [
            "order" => "integer",
            "is_active" => "boolean",
        ];
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return "";
        }

        if (str_starts_with($this->image_path, "http://") || str_starts_with($this->image_path, "https://")) {
            return $this->image_path;
        }

        return asset("storage/".ltrim($this->image_path, "/"));
    }
}
