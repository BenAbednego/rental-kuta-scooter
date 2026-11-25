<?php

namespace Tests\Feature;

use App\Filament\Resources\BookingResource;
use App\Filament\Resources\BookingResource\Pages\CreateBooking;
use App\Filament\Resources\BookingResource\Pages\EditBooking;
use App\Filament\Resources\FaqResource;
use App\Filament\Resources\FaqResource\Pages\EditFaq;
use App\Filament\Resources\GalleryImageResource;
use App\Filament\Resources\GalleryImageResource\Pages\EditGalleryImage;
use App\Filament\Resources\MotorResource;
use App\Filament\Resources\MotorResource\Pages\EditMotor;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Models\Booking;
use App\Models\Faq;
use App\Models\GalleryImage;
use App\Models\Motor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use App\Filament\Resources\PricingPackageResource;
use App\Filament\Resources\PricingPackageResource\Pages\EditPricingPackage;
use App\Models\PricingPackage;
use Tests\TestCase;

class FilamentRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_edit_booking_redirects_to_index_with_notification(): void
    {
        $user = User::factory()->create();
        $motor = Motor::create([
            "name" => "Honda Beat 110cc",
            "slug" => "honda-beat-110cc",
            "category" => "matic",
            "price_daily" => 75000,
            "unit_count" => 5,
            "status" => "active",
        ]);

        $booking = Booking::create([
            "motor_id" => $motor->id,
            "customer_name" => "Test User",
            "customer_whatsapp" => "08123456789",
            "customer_document_type" => "ktp",
            "start_date" => now()->toDateString(),
            "end_date" => now()->addDays(2)->toDateString(),
            "total_days" => 2,
            "total_price" => 150000,
            "status" => "pending",
            "notes" => "Test note",
        ]);

        $this->actingAs($user);

        Livewire::test(EditBooking::class, ["record" => $booking->getRouteKey()])
            ->fillForm([
                "status" => "confirmed",
            ])
            ->call("save")
            ->assertHasNoErrors()
            ->assertRedirect(BookingResource::getUrl("index"));
    }

    public function test_create_booking_redirects_to_index_with_notification(): void
    {
        $user = User::factory()->create();
        $motor = Motor::create([
            "name" => "Honda Vario 125cc",
            "slug" => "honda-vario-125cc",
            "category" => "matic",
            "price_daily" => 90000,
            "unit_count" => 5,
            "status" => "active",
        ]);

        $this->actingAs($user);

        Livewire::test(CreateBooking::class)
            ->fillForm([
                "motor_id" => $motor->id,
                "customer_name" => "New Customer",
                "customer_whatsapp" => "08198765432",
                "customer_document_type" => "ktp",
                "start_date" => now()->toDateString(),
                "end_date" => now()->addDays(2)->toDateString(),
                "total_days" => 2,
                "total_price" => 180000,
                "status" => "pending",
            ])
            ->call("create")
            ->assertHasNoErrors()
            ->assertRedirect(BookingResource::getUrl("index"));
    }

    public function test_edit_motor_redirects_to_index(): void
    {
        $user = User::factory()->create();
        $motor = Motor::create([
            "name" => "Yamaha Aerox 155cc",
            "slug" => "yamaha-aerox-155cc",
            "category" => "matic",
            "price_daily" => 120000,
            "unit_count" => 3,
            "status" => "active",
        ]);

        $this->actingAs($user);

        Livewire::test(EditMotor::class, ["record" => $motor->getRouteKey()])
            ->fillForm([
                "price_daily" => 130000,
            ])
            ->call("save")
            ->assertHasNoErrors()
            ->assertRedirect(MotorResource::getUrl("index"));
    }

    public function test_edit_faq_redirects_to_index(): void
    {
        $user = User::factory()->create();
        $faq = Faq::create([
            "question_id" => "Syarat sewa?",
            "answer_id" => "KTP / Paspor",
            "question_en" => "Rental requirements?",
            "answer_en" => "Passport / ID card",
            "order" => 1,
            "is_active" => true,
        ]);

        $this->actingAs($user);

        Livewire::test(EditFaq::class, ["record" => $faq->getRouteKey()])
            ->fillForm([
                "question_id" => "Syarat sewa motor di Kuta?",
            ])
            ->call("save")
            ->assertHasNoErrors()
            ->assertRedirect(FaqResource::getUrl("index"));
    }

    public function test_edit_gallery_image_redirects_to_index(): void
    {
        Storage::fake("public");
        $user = User::factory()->create();
        $image = GalleryImage::create([
            "image_path" => "gallery/sample.jpg",
            "caption" => "Foto armada",
            "order" => 1,
            "is_active" => true,
        ]);

        $this->actingAs($user);

        Livewire::test(EditGalleryImage::class, ["record" => $image->getRouteKey()])
            ->fillForm([
                "image_path" => UploadedFile::fake()->image("new_sample.jpg"),
                "caption" => "Foto armada terbaru",
            ])
            ->call("save")
            ->assertHasNoErrors()
            ->assertRedirect(GalleryImageResource::getUrl("index"));
    }

    public function test_edit_user_redirects_to_index(): void
    {
        $user = User::factory()->create();
        $targetUser = User::factory()->create([
            "name" => "Staff Admin",
            "email" => "staff@kutascooter.com",
        ]);

        $this->actingAs($user);

        Livewire::test(EditUser::class, ["record" => $targetUser->getRouteKey()])
            ->fillForm([
                "name" => "Staff Admin Updated",
            ])
            ->call("save")
            ->assertHasNoErrors()
            ->assertRedirect(UserResource::getUrl("index"));
    }
    public function test_edit_pricing_package_redirects_to_index(): void
    {
        $user = User::factory()->create();
        $package = PricingPackage::create([
            "name_id" => "Paket Spesial",
            "name_en" => "Special Package",
            "price" => 250000,
            "price_unit_id" => "/ 3 hari",
            "price_unit_en" => "/ 3 days",
            "benefits_id" => ["Helm SNI", "Jas Hujan"],
            "benefits_en" => ["SNI Helmet", "Raincoat"],
            "icon" => "Zap",
            "cta_label_id" => "Sewa",
            "cta_label_en" => "Rent",
            "is_featured" => false,
            "order" => 1,
            "is_active" => true,
        ]);

        $this->actingAs($user);

        Livewire::test(EditPricingPackage::class, ["record" => $package->getRouteKey()])
            ->fillForm([
                "price" => 300000,
            ])
            ->call("save")
            ->assertHasNoErrors()
            ->assertRedirect(PricingPackageResource::getUrl("index"));
    }
}
