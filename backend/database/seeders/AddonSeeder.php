<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

class AddonSeeder extends Seeder
{
    public function run(): void
    {
        $addons = [
            [
                'name_id' => 'Helm Tambahan (SNI)',
                'name_en' => 'Additional Helmet (SNI)',
                'description_id' => 'Helm SNI bersih, higienis, dan wangi untuk kenyamanan berkendara berdua.',
                'description_en' => 'Clean, sanitized, and fresh SNI standard helmet for passenger comfort.',
                'price' => 10000,
                'price_type' => 'per_rental',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name_id' => 'Jas Hujan Tambahan',
                'name_en' => 'Additional Raincoat',
                'description_id' => 'Setelan jas hujan berkualitas tebal dan nyaman saat cuaca hujan di Bali.',
                'description_en' => 'Quality two-piece waterproof raincoat set for unexpected rainy weather.',
                'price' => 10000,
                'price_type' => 'per_rental',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name_id' => 'Phone Holder Stang',
                'name_en' => 'Handlebar Phone Holder',
                'description_id' => 'Dudukan smartphone kokoh dan aman untuk kemudahan navigasi peta rute.',
                'description_en' => 'Sturdy and secure smartphone holder for GPS and maps navigation.',
                'price' => 5000,
                'price_type' => 'per_rental',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name_id' => 'Surf Rack (Rak Papan Selancar)',
                'name_en' => 'Surfboard Rack',
                'description_id' => 'Rak samping berlapis busa empuk untuk membawa papan surfing ke pantai.',
                'description_en' => 'Padded side rack to safely transport your surfboard to Bali beach breaks.',
                'price' => 25000,
                'price_type' => 'per_rental',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($addons as $item) {
            Addon::updateOrCreate(
                ['name_id' => $item['name_id']],
                $item
            );
        }
    }
}
