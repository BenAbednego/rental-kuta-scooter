<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageReviewSettings;
use App\Models\ReviewSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReviewSettingApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_missing_review_setting_returns_manual_mode(): void
    {
        $this->getJson('/api/review-settings')
            ->assertOk()
            ->assertExactJson(['mode' => 'manual']);
    }

    public function test_valid_iframe_setting_is_returned_as_parsed_data(): void
    {
        ReviewSetting::create([
            'display_mode' => 'iframe',
            'iframe_src' => 'https://www.google.com/maps/embed?q=Kuta',
            'iframe_provider' => 'Google Maps',
            'iframe_title' => 'Kuta review map',
            'iframe_width' => 600,
            'iframe_height' => 450,
        ]);

        $this->getJson('/api/review-settings')
            ->assertOk()
            ->assertExactJson([
                'mode' => 'iframe',
                'iframe' => [
                    'src' => 'https://www.google.com/maps/embed?q=Kuta',
                    'title' => 'Kuta review map',
                    'height' => 450,
                    'provider_label' => 'Google Maps',
                ],
            ]);
    }

    public function test_invalid_or_incomplete_iframe_configuration_falls_back_to_manual(): void
    {
        ReviewSetting::create([
            'display_mode' => 'iframe',
            'iframe_src' => 'https://evil.example/reviews',
            'iframe_provider' => 'Unknown Provider',
        ]);

        $this->getJson('/api/review-settings')
            ->assertOk()
            ->assertExactJson(['mode' => 'manual']);
    }

    public function test_iframe_mode_validates_and_saves_only_parsed_fields(): void
    {
        Livewire::test(ManageReviewSettings::class)
            ->set('data.display_mode', 'iframe')
            ->set('data.iframe_code', '<iframe src="https://www.google.com/maps/embed?q=Kuta" width="600" height="450" title="Kuta map" onload="alert(1)"></iframe>')
            ->assertSet('iframeReading.provider_label', 'Google Maps')
            ->assertSet('iframeReading.host', 'www.google.com')
            ->assertSet('iframeReading.width', 600)
            ->assertSet('iframeReading.height', 450)
            ->assertSee('Hasil bacaan')
            ->assertSee('Kuta')
            ->call('save')
            ->assertHasNoErrors();

        $setting = ReviewSetting::query()->firstOrFail();

        $this->assertSame('iframe', $setting->display_mode);
        $this->assertSame('Google Maps', $setting->iframe_provider);
        $this->assertSame(600, $setting->iframe_width);
        $this->assertSame(450, $setting->iframe_height);
        $this->assertSame('Kuta map', $setting->iframe_title);
        $this->assertArrayNotHasKey('iframe_code', $setting->getAttributes());
    }

    public function test_iframe_mode_rejects_unapproved_hosts_on_save(): void
    {
        Livewire::test(ManageReviewSettings::class)
            ->set('data.display_mode', 'iframe')
            ->set('data.iframe_code', '<iframe src="https://evil.example/reviews"></iframe>')
            ->call('save')
            ->assertHasErrors(['data.iframe_code']);

        $this->assertDatabaseCount('review_settings', 0);
    }
}
