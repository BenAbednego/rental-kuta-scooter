<?php

namespace Tests\Feature;

use App\Models\Testimonial;
use Database\Seeders\TestimonialSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TestimonialSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeds_the_existing_reviews_without_overwriting_admin_edits(): void
    {
        $this->seed(TestimonialSeeder::class);

        $this->assertDatabaseCount('testimonials', 3);
        $this->assertDatabaseHas('testimonials', [
            'name' => 'Budi Santoso',
            'text_id' => 'Pelayanan cepat banget! Motor Vario diantar ke hotel Kuta tepat waktu. Helm bersih & wangi, kondisi fisik motor sangat terawat.',
            'text_en' => 'Very fast service! The Vario was delivered to our Kuta hotel right on time. Helmets were clean, and the bike was in excellent condition.',
        ]);

        Testimonial::where('name', 'Budi Santoso')->update([
            'text_id' => 'Ulasan yang sudah diperbarui admin.',
        ]);

        $this->seed(TestimonialSeeder::class);

        $this->assertDatabaseCount('testimonials', 3);
        $this->assertDatabaseHas('testimonials', [
            'name' => 'Budi Santoso',
            'text_id' => 'Ulasan yang sudah diperbarui admin.',
        ]);
    }
}
