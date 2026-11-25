<?php

namespace Tests\Feature;

use App\Models\PricingPackage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PricingPackageApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_active_pricing_packages_ordered(): void
    {
        PricingPackage::create([
            "name_id" => "Paket Kedua",
            "name_en" => "Second Package",
            "price" => 500000,
            "price_unit_id" => "/ minggu",
            "price_unit_en" => "/ week",
            "benefits_id" => ["Benefit A", "Benefit B"],
            "benefits_en" => ["Benefit A EN", "Benefit B EN"],
            "icon" => "Sparkles",
            "cta_label_id" => "Sewa",
            "cta_label_en" => "Rent",
            "is_featured" => true,
            "order" => 2,
            "is_active" => true,
        ]);

        PricingPackage::create([
            "name_id" => "Paket Pertama",
            "name_en" => "First Package",
            "price" => 100000,
            "price_unit_id" => "/ hari",
            "price_unit_en" => "/ day",
            "benefits_id" => ["Benefit 1", "Benefit 2"],
            "benefits_en" => ["Benefit 1 EN", "Benefit 2 EN"],
            "icon" => "Bike",
            "cta_label_id" => "Sewa",
            "cta_label_en" => "Rent",
            "is_featured" => false,
            "order" => 1,
            "is_active" => true,
        ]);

        PricingPackage::create([
            "name_id" => "Paket Nonaktif",
            "name_en" => "Inactive Package",
            "price" => 2000000,
            "price_unit_id" => "/ bulan",
            "price_unit_en" => "/ month",
            "benefits_id" => ["Nonaktif"],
            "benefits_en" => ["Inactive"],
            "icon" => "Crown",
            "cta_label_id" => "Sewa",
            "cta_label_en" => "Rent",
            "is_featured" => false,
            "order" => 3,
            "is_active" => false,
        ]);

        $response = $this->getJson("/api/pricing-packages");

        $response->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "*" => [
                        "id",
                        "name_id",
                        "name_en",
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
                    ],
                ],
            ]);

        $data = $response->json("data");
        // VALIDASI:
        // 1. Memastikan paket non-aktif difilter keluar (hanya 2 dari 3 yang keluar)
        $this->assertCount(2, $data);
        // 2. Memastikan urutan ascending sesuai kolom order (Paket Pertama di index 0)
        $this->assertEquals("Paket Pertama", $data[0]["name_id"]);
        $this->assertEquals("Paket Kedua", $data[1]["name_id"]);
    }
}
