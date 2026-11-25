<?php

namespace Database\Seeders;

use App\Models\GalleryImage;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            [
                "image_path" => "https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=600&auto=format&fit=crop&q=80",
                "caption" => "Pelanggan puas sewa Scoopy di Pantai Kuta",
                "order" => 1,
                "is_active" => true,
            ],
            [
                "image_path" => "https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=600&auto=format&fit=crop&q=80",
                "caption" => "Antar motor NMAX langsung ke Bandara Ngurah Rai (DPS)",
                "order" => 2,
                "is_active" => true,
            ],
            [
                "image_path" => "https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=600&auto=format&fit=crop&q=80",
                "caption" => "Unit Honda PCX siap touring keliling Kuta & Canggu",
                "order" => 3,
                "is_active" => true,
            ],
            [
                "image_path" => "https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=600&auto=format&fit=crop&q=80",
                "caption" => "Wisatawan asing menikmati kebebasan berkendara di Kuta Bali",
                "order" => 4,
                "is_active" => true,
            ],
            [
                "image_path" => "https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=600&auto=format&fit=crop&q=80",
                "caption" => "Garasi utama RMB Sewa Motor Kuta",
                "order" => 5,
                "is_active" => true,
            ],
        ];

        foreach ($images as $item) {
            GalleryImage::updateOrCreate(
                ["caption" => $item["caption"]],
                $item
            );
        }
    }
}
