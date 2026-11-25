<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Motor;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            FaqSeeder::class,
            GallerySeeder::class,
            PricingPackageSeeder::class,
            TestimonialSeeder::class,
            BusinessSettingSeeder::class,
        ]);

        // 1. Admin User for Filament Dashboard
        User::updateOrCreate(
            ['email' => 'admin@sewamotorkuta.id'],
            [
                'name' => 'Admin Sewa Motor Kuta',
                'password' => Hash::make('password123'),
            ]
        );

        // 2. Initial Motor Lineup (Kuta / Bali Fleet)
        $motorsData = [
            [
                'name' => 'Honda Scoopy 110cc',
                'slug' => 'honda-scoopy-110cc',
                'category' => 'matic',
                'cc' => 110,
                'description_id' => 'Skutik retro stylish yang lincah dan hemat bahan bakar. Sangat cocok untuk menjelajahi jalanan Kuta, Seminyak, dan Legian.',
                'description_en' => 'Stylish retro scooter that is nimble and fuel efficient. Perfect for exploring the streets of Kuta, Seminyak, and Legian.',
                'price_daily' => 85000,
                'price_weekly' => 500000,
                'price_monthly' => 1800000,
                'deposit_amount' => 200000,
                'unit_count' => 5,
                'main_image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=600&auto=format&fit=crop&q=80',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=600&auto=format&fit=crop&q=80',
                ],
                'status' => 'active',
                'specs' => [
                    'Transmisi' => 'Otomatis',
                    'Helm Gratis' => '2 Helm SNI',
                    'Jas Hujan' => 'Termasuk 2 Set',
                    'Fitur' => 'Keyless & USB Charger',
                ],
            ],
            [
                'name' => 'Honda Vario 160cc ABS',
                'slug' => 'honda-vario-160cc-abs',
                'category' => 'matic',
                'cc' => 160,
                'description_id' => 'Motor matic bertenaga 160cc dengan pengereman ABS untuk keamanan ekstra di perjalanan jauh menuju Uluwatu atau Canggu.',
                'description_en' => 'Powerful 160cc scooter equipped with ABS brakes for extra safety on longer rides to Uluwatu or Canggu.',
                'price_daily' => 110000,
                'price_weekly' => 680000,
                'price_monthly' => 2400000,
                'deposit_amount' => 300000,
                'unit_count' => 4,
                'main_image' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=600&auto=format&fit=crop&q=80',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=600&auto=format&fit=crop&q=80',
                ],
                'status' => 'active',
                'specs' => [
                    'Transmisi' => 'Otomatis CVT',
                    'Rem' => 'ABS Front Disc',
                    'Helm Gratis' => '2 Helm Clean',
                    'Fitur' => 'Bagasi Luas 18L',
                ],
            ],
            [
                'name' => 'Yamaha NMAX 155cc ABS',
                'slug' => 'yamaha-nmax-155cc-abs',
                'category' => 'premium',
                'cc' => 155,
                'description_id' => 'Skuter maxi paling populer di Bali. Sangat nyaman untuk dua orang dengan posisi berkendara ergonomis dan bagasi super luas.',
                'description_en' => 'The most popular maxi scooter in Bali. Super comfortable for two riders with ergonomic seating and huge under-seat storage.',
                'price_daily' => 150000,
                'price_weekly' => 950000,
                'price_monthly' => 3200000,
                'deposit_amount' => 500000,
                'unit_count' => 6,
                'main_image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=600&auto=format&fit=crop&q=80',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=600&auto=format&fit=crop&q=80',
                ],
                'status' => 'active',
                'specs' => [
                    'Kapasitas Mesin' => '155cc VVA Engine',
                    'Kenyamanan' => 'Dual Shock Absorber',
                    'Helm Gratis' => '2 Helm Premium',
                    'Konektivitas' => 'Y-Connect App',
                ],
            ],
            [
                'name' => 'Honda PCX 160cc ABS',
                'slug' => 'honda-pcx-160cc-abs',
                'category' => 'premium',
                'cc' => 160,
                'description_id' => 'Skuter mewah bertenaga dengan kenyamanan ekstra, sistem Smart Key, dan tampilan elegan untuk keliling Bali.',
                'description_en' => 'Luxury scooter with smooth power delivery, Smart Key system, and elegant design for cruising around Bali.',
                'price_daily' => 160000,
                'price_weekly' => 1000000,
                'price_monthly' => 3400000,
                'deposit_amount' => 500000,
                'unit_count' => 3,
                'main_image' => 'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=600&auto=format&fit=crop&q=80',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=600&auto=format&fit=crop&q=80',
                ],
                'status' => 'active',
                'specs' => [
                    'Mesin' => '160cc eSP+',
                    'Sistem Kunci' => 'Honda Smart Key',
                    'Kapasitas Tangki' => '8.1 Liter',
                    'Helm' => '2 Helm SNI Clean',
                ],
            ],
            [
                'name' => 'Yamaha XMAX 250cc',
                'slug' => 'yamaha-xmax-250cc',
                'category' => 'premium',
                'cc' => 250,
                'description_id' => 'Maxi scooter kelas atas 250cc bertenaga besar untuk perjalanan touring Bali dari Kuta hingga Bedugul & Kintamani.',
                'description_en' => 'Ultimate 250cc maxi scooter built for comfortable long-distance touring across Bali from Kuta to Kintamani.',
                'price_daily' => 300000,
                'price_weekly' => 1900000,
                'price_monthly' => 6500000,
                'deposit_amount' => 1000000,
                'unit_count' => 2,
                'main_image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=600&auto=format&fit=crop&q=80',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=600&auto=format&fit=crop&q=80',
                ],
                'status' => 'active',
                'specs' => [
                    'Kapasitas' => '250cc Blue Core',
                    'Traction Control' => 'TCS & Dual ABS',
                    'Layar' => 'TFT Infotainment System',
                    'Bagasi' => 'Muat 2 Helm Full Face',
                ],
            ],
            [
                'name' => 'Vespa Primavera 150cc i-Get',
                'slug' => 'vespa-primavera-150cc-iget',
                'category' => 'sport',
                'cc' => 150,
                'description_id' => 'Ikon gaya Italia yang estetik dan Instagrammable. Pilihan paling hits untuk foto liburan di pantai Kuta & Seminyak.',
                'description_en' => 'Iconic Italian aesthetic scooter. Most stylish choice for your Instagram vacation photos around Kuta beach.',
                'price_daily' => 220000,
                'price_weekly' => 1400000,
                'price_monthly' => 4800000,
                'deposit_amount' => 750000,
                'unit_count' => 3,
                'main_image' => 'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=600&auto=format&fit=crop&q=80',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1558981403-c5f9899a28bc?w=600&auto=format&fit=crop&q=80',
                ],
                'status' => 'active',
                'specs' => [
                    'Gaya' => 'Classic Italian Design',
                    'Mesin' => '150cc i-Get 3V',
                    'Pengereman' => 'Front ABS Brake',
                    'Helm' => '2 Helm Vespa Retro',
                ],
            ],
        ];

        foreach ($motorsData as $data) {
            $motor = Motor::updateOrCreate(['slug' => $data['slug']], $data);

            // Create initial dummy booking for demo
            if ($data['slug'] === 'yamaha-nmax-155cc-abs') {
                Booking::create([
                    'motor_id' => $motor->id,
                    'customer_name' => 'John Smith',
                    'customer_whatsapp' => '+61412345678',
                    'customer_document_type' => 'passport',
                    'start_date' => now()->toDateString(),
                    'end_date' => now()->addDays(3)->toDateString(),
                    'total_days' => 3,
                    'total_price' => 450000,
                    'status' => 'confirmed',
                    'notes' => 'Hotel delivery to Grand Inna Kuta',
                ]);
            }
        }
    }
}
