<?php

namespace Tests\Feature;

use App\Models\Motor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MotorBookingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_active_motors_list(): void
    {
        Motor::create([
            'name' => 'Honda Scoopy 110cc',
            'brand' => 'Honda',
            'slug' => 'honda-scoopy-110cc',
            'category' => 'matic',
            'price_daily' => 85000,
            'unit_count' => 3,
            'status' => 'active',
        ]);

        $response = $this->getJson('/api/motors');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'brand', 'slug', 'category', 'price_daily', 'unit_count', 'status'],
                ],
            ])
            ->assertJsonPath('data.0.brand', 'Honda');
    }

    public function test_can_fetch_single_motor_by_slug(): void
    {
        $motor = Motor::create([
            'name' => 'Yamaha NMAX 155cc ABS',
            'brand' => 'Yamaha',
            'slug' => 'yamaha-nmax-155cc-abs',
            'category' => 'premium',
            'price_daily' => 150000,
            'unit_count' => 4,
            'status' => 'active',
        ]);

        $response = $this->getJson('/api/motors/'.$motor->slug);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $motor->id,
                    'brand' => 'Yamaha',
                    'slug' => 'yamaha-nmax-155cc-abs',
                    'name' => 'Yamaha NMAX 155cc ABS',
                ],
            ]);
    }

    public function test_can_submit_booking(): void
    {
        $motor = Motor::create([
            'name' => 'Honda Vario 160cc',
            'slug' => 'honda-vario-160cc',
            'category' => 'matic',
            'price_daily' => 110000,
            'unit_count' => 2,
            'status' => 'active',
        ]);

        $bookingData = [
            'motor_id' => $motor->id,
            'customer_name' => 'Alice Johnson',
            'customer_email' => 'alice@example.com',
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(4)->toDateString(),
            'lang' => 'en',
            'whatsapp_message' => "Hello! I would like to book the following bike:\n\n*Bike:* Honda PCX 160cc\n*Email:* alice@example.com",
        ];

        $response = $this->postJson('/api/bookings', $bookingData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => 1,
                    'booking_id' => 1,
                    'customer_name' => 'Alice Johnson',
                    'customer_email' => 'alice@example.com',
                    'customer_whatsapp' => null,
                    'customer_document_type' => null,
                    'total_days' => 3,
                    'total_price' => 330000,
                    'status' => 'pending',
                ],
            ]);

        $this->assertDatabaseHas('bookings', [
            'customer_name' => 'Alice Johnson',
            'customer_email' => 'alice@example.com',
            'customer_whatsapp' => null,
            'motor_id' => $motor->id,
            'status' => 'pending',
        ]);

        parse_str((string) parse_url($response->json('data.whatsapp_url'), PHP_URL_QUERY), $whatsappQuery);
        $this->assertSame("Hello! I would like to book the following bike:\n\n*Bike:* Honda PCX 160cc\n*Email:* alice@example.com", $whatsappQuery['text']);

        $this->getJson('/api/bookings/'.$response->json('data.id'))
            ->assertOk()
            ->assertJsonPath('data.customer_email', 'alice@example.com');
    }

    public function test_booking_requires_customer_email(): void
    {
        $motor = Motor::create([
            'name' => 'Honda Vario 160cc',
            'slug' => 'honda-vario-160cc',
            'category' => 'matic',
            'price_daily' => 110000,
            'unit_count' => 2,
            'status' => 'active',
        ]);

        $this->postJson('/api/bookings', [
            'motor_id' => $motor->id,
            'customer_name' => 'Alice Johnson',
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'lang' => 'en',
            'whatsapp_message' => 'Booking message',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('customer_email');
    }

    public function test_booking_rejects_invalid_customer_email(): void
    {
        $motor = Motor::create([
            'name' => 'Honda Vario 160cc',
            'slug' => 'honda-vario-160cc',
            'category' => 'matic',
            'price_daily' => 110000,
            'unit_count' => 2,
            'status' => 'active',
        ]);

        $this->postJson('/api/bookings', [
            'motor_id' => $motor->id,
            'customer_name' => 'Alice Johnson',
            'customer_email' => 'invalid-email',
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'lang' => 'en',
            'whatsapp_message' => 'Booking message',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors('customer_email');
    }
}
