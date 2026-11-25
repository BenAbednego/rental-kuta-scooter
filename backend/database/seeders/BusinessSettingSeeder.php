<?php

namespace Database\Seeders;

use App\Models\BusinessSetting;
use Illuminate\Database\Seeder;

class BusinessSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BusinessSetting::updateOrCreate(
            ['id' => 1],
            [
                'whatsapp_primary' => '6285947167662',
                'whatsapp_secondary' => null,
                'business_email' => 'info@sewamotorkuta.id',
                'business_address' => 'Jl. Pantai Kuta No. 88, Badung, Bali 80361',
                'operating_hours_id' => 'Setiap Hari (Senin - Minggu): 08:00 - 22:00 WITA',
                'operating_hours_en' => 'Everyday (Monday - Sunday): 08:00 - 22:00 WITA',
                'instagram_url' => 'https://instagram.com/rmb_kutabali',
            ]
        );
    }
}
