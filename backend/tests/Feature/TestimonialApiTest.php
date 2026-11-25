<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_only_active_testimonials_in_display_order(): void
    {
        Testimonial::create([
            'name' => 'Customer Two',
            'location' => 'Australia',
            'text_id' => 'Ulasan dua',
            'text_en' => 'Review two',
            'rating' => 4,
            'order' => 2,
            'is_active' => true,
        ]);

        Testimonial::create([
            'name' => 'Customer One',
            'location' => 'Jakarta',
            'text_id' => 'Ulasan satu',
            'text_en' => 'Review one',
            'rating' => 5,
            'order' => 1,
            'is_active' => true,
        ]);

        Testimonial::create([
            'name' => 'Hidden Customer',
            'location' => 'Singapore',
            'text_id' => 'Ulasan tersembunyi',
            'text_en' => 'Hidden review',
            'rating' => 1,
            'order' => 0,
            'is_active' => false,
        ]);

        $response = $this->getJson('/api/testimonials');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'location', 'text_id', 'text_en', 'rating', 'order'],
                ],
            ])
            ->assertJsonPath('data.0.name', 'Customer One')
            ->assertJsonPath('data.0.rating', 5)
            ->assertJsonPath('data.1.name', 'Customer Two')
            ->assertJsonPath('data.1.rating', 4);

        $this->assertCount(2, $response->json('data'));
    }
}
