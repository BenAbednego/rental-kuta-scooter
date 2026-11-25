<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Budi Santoso',
                'location' => 'Jakarta',
                'text_id' => 'Pelayanan cepat banget! Motor Vario diantar ke hotel Kuta tepat waktu. Helm bersih & wangi, kondisi fisik motor sangat terawat.',
                'text_en' => 'Very fast service! The Vario was delivered to our Kuta hotel right on time. Helmets were clean, and the bike was in excellent condition.',
                'rating' => 5,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Sarah Jenkins',
                'location' => 'Australia',
                'text_id' => 'Sewa NMAX selama 5 hari. Proses mudah via WhatsApp, staf ramah, tanpa biaya tersembunyi. Pasti sewa di sini lagi saat ke Bali.',
                'text_en' => 'Rented an NMAX for 5 days. Smooth process via WhatsApp, friendly team, no hidden fees. Will definitely rent here again on my next Bali trip.',
                'rating' => 5,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Michael Chen',
                'location' => 'Singapore',
                'text_id' => 'Serah terima sangat cepat di Bandara Ngurah Rai. Motor berjalan mulus sepanjang perjalanan, dan deposit dikembalikan tepat waktu.',
                'text_en' => 'Very fast airport drop-off at Ngurah Rai. The scooter ran smoothly throughout our entire trip. Transparent deposit policy.',
                'rating' => 5,
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::firstOrCreate(
                [
                    'name' => $testimonial['name'],
                    'location' => $testimonial['location'],
                ],
                $testimonial
            );
        }
    }
}
