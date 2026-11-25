<?php

namespace Database\Seeders;

use App\Models\PricingPackage;
use Illuminate\Database\Seeder;

class PricingPackageSeeder extends Seeder
{
    public function run(): void
    {
        PricingPackage::truncate();

        PricingPackage::create([
            "name_id" => "Paket Harian",
            "name_en" => "Daily Package",
            "subtitle_id" => "Pilihan sewa singkat 1-3 hari.",
            "subtitle_en" => "Short rental option for 1-3 days.",
            "price" => 85000.00,
            "price_unit_id" => "/ hari",
            "price_unit_en" => "/ day",
            "benefits_id" => [
                "2 Helm Higienis SNI",
                "Jas Hujan 2 Set",
                "Layanan Darurat 24 Jam",
                "Free Delivery Kuta Area",
            ],
            "benefits_en" => [
                "2 Sanitized SNI Helmets",
                "2 Sets of Raincoats",
                "24/7 Roadside Assistance",
                "Free Delivery Kuta Area",
            ],
            "icon" => "Bike",
            "cta_label_id" => "Sewa Harian",
            "cta_label_en" => "Rent Daily",
            "is_featured" => false,
            "order" => 1,
            "is_active" => true,
        ]);

        PricingPackage::create([
            "name_id" => "Paket Mingguan",
            "name_en" => "Weekly Package",
            "subtitle_id" => "Paling hemat untuk liburan 7 hari.",
            "subtitle_en" => "Best value for 7 days holiday.",
            "price" => 550000.00,
            "price_unit_id" => "/ minggu",
            "price_unit_en" => "/ week",
            "benefits_id" => [
                "Diskon Paket Spesial",
                "2 Helm Higienis SNI",
                "Jas Hujan & Phone Holder",
                "Free Airport Delivery (DPS)",
            ],
            "benefits_en" => [
                "Special Package Discount",
                "2 Sanitized SNI Helmets",
                "Raincoats & Phone Holder",
                "Free Airport Delivery (DPS)",
            ],
            "icon" => "Sparkles",
            "cta_label_id" => "Sewa Mingguan",
            "cta_label_en" => "Rent Weekly",
            "is_featured" => true,
            "order" => 2,
            "is_active" => true,
        ]);

        PricingPackage::create([
            "name_id" => "Paket Bulanan",
            "name_en" => "Monthly Package",
            "subtitle_id" => "Solusi hemat untuk stay panjang.",
            "subtitle_en" => "Best solution for long stay.",
            "price" => 1800000.00,
            "price_unit_id" => "/ bulan",
            "price_unit_en" => "/ month",
            "benefits_id" => [
                "Hemat Hingga 40%",
                "Perawatan Service Rutin",
                "2 Helm & Jas Hujan",
                "Replacement Unit Priority",
            ],
            "benefits_en" => [
                "Save Up to 40%",
                "Routine Maintenance Service",
                "2 Helmets & Raincoats",
                "Priority Replacement Unit",
            ],
            "icon" => "Crown",
            "cta_label_id" => "Sewa Bulanan",
            "cta_label_en" => "Rent Monthly",
            "is_featured" => false,
            "order" => 3,
            "is_active" => true,
        ]);
    }
}
