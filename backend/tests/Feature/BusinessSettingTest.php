<?php

namespace Tests\Feature;

use App\Models\BusinessSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_business_settings(): void
    {
        BusinessSetting::create([
            'whatsapp_primary' => '6281299998888',
            'whatsapp_secondary' => '628111112222',
            'business_email' => 'halo@sewamotorkuta.id',
            'business_address' => 'Jl. Legian No. 12, Kuta, Bali',
            'operating_hours_id' => '08:00 - 20:00 WITA',
            'operating_hours_en' => '08:00 AM - 08:00 PM',
            'instagram_url' => 'https://instagram.com/sewamotorkuta',
        ]);

        $response = $this->getJson('/api/settings');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'data' => [
                    'whatsapp_primary' => '6281299998888',
                    'whatsapp_secondary' => '628111112222',
                    'business_email' => 'halo@sewamotorkuta.id',
                    'business_address' => 'Jl. Legian No. 12, Kuta, Bali',
                    'operating_hours_id' => '08:00 - 20:00 WITA',
                    'operating_hours_en' => '08:00 AM - 08:00 PM',
                    'instagram_url' => 'https://instagram.com/sewamotorkuta',
                ],
            ]);
    }

    public function test_settings_auto_creates_default_if_table_is_empty(): void
    {
        $this->assertDatabaseCount('business_settings', 0);

        $response = $this->getJson('/api/settings');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'whatsapp_primary',
                    'operating_hours_id',
                    'operating_hours_en',
                ],
            ]);

        $this->assertDatabaseCount('business_settings', 1);
    }

    public function test_phone_numbers_are_automatically_sanitized_to_e164(): void
    {
        $setting = BusinessSetting::create([
            'whatsapp_primary' => '082380753577', // with leading 0
            'whatsapp_secondary' => '+62 859-4716-7662', // with + and dashes
            'operating_hours_id' => '08:00 - 22:00 WITA',
            'operating_hours_en' => '08:00 AM - 10:00 PM',
        ]);

        $this->assertEquals('6282380753577', $setting->whatsapp_primary);
        $this->assertEquals('6285947167662', $setting->whatsapp_secondary);
    }

    public function test_booking_clean_whatsapp_converts_local_zero_prefix(): void
    {
        $booking = new \App\Models\Booking([
            'customer_whatsapp' => '082380753577',
        ]);

        $this->assertEquals('6282380753577', $booking->clean_whatsapp);
    }
}
