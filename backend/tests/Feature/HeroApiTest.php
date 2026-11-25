<?php

namespace Tests\Feature;

use App\Filament\Pages\ManageHeroSettings;
use App\Models\HeroSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class HeroApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
    }

    public function test_missing_hero_settings_returns_default(): void
    {
        $this->getJson('/api/hero')
            ->assertOk()
            ->assertExactJson([
                'type' => 'default',
                'image_url' => null,
                'slides' => null,
                'slide_interval' => null,
                'video' => null,
                'poster_url' => null,
            ]);
    }

    public function test_single_image_configuration_returns_public_image_url(): void
    {
        Storage::disk('public')->put('hero/images/home.jpg', 'image');
        HeroSetting::forceCreate([
            'id' => 5,
            'type' => 'single_image',
            'image' => 'hero/images/home.jpg',
        ]);

        $this->getJson('/api/hero')
            ->assertOk()
            ->assertJsonPath('type', 'single_image')
            ->assertJsonPath('image_url', Storage::disk('public')->url('hero/images/home.jpg'))
            ->assertJsonPath('slides', null)
            ->assertJsonPath('video', null);
    }

    public function test_slider_configuration_returns_ordered_image_urls_and_interval(): void
    {
        Storage::disk('public')->put('hero/slides/one.jpg', 'image');
        Storage::disk('public')->put('hero/slides/two.jpg', 'image');
        Storage::disk('public')->put('hero/slides/three.jpg', 'image');
        HeroSetting::create([
            'id' => 1,
            'type' => 'slider',
            'slides' => [
                'hero/slides/one.jpg',
                'hero/slides/two.jpg',
                'hero/slides/three.jpg',
            ],
            'slide_interval' => 7,
        ]);

        $this->getJson('/api/hero')
            ->assertOk()
            ->assertJsonPath('type', 'slider')
            ->assertJsonPath('slides.0', Storage::disk('public')->url('hero/slides/one.jpg'))
            ->assertJsonPath('slides.1', Storage::disk('public')->url('hero/slides/two.jpg'))
            ->assertJsonPath('slides.2', Storage::disk('public')->url('hero/slides/three.jpg'))
            ->assertJsonPath('slide_interval', 7)
            ->assertJsonPath('image_url', null);
    }

    public function test_uploaded_video_configuration_returns_video_and_optional_poster(): void
    {
        Storage::disk('public')->put('hero/videos/background.mp4', 'video');
        Storage::disk('public')->put('hero/posters/background.jpg', 'image');
        HeroSetting::create([
            'id' => 1,
            'type' => 'video',
            'video_source' => 'upload',
            'video_path' => 'hero/videos/background.mp4',
            'poster' => 'hero/posters/background.jpg',
        ]);

        $this->getJson('/api/hero')
            ->assertOk()
            ->assertJsonPath('type', 'video')
            ->assertJsonPath('video.source', 'upload')
            ->assertJsonPath('video.url', Storage::disk('public')->url('hero/videos/background.mp4'))
            ->assertJsonPath('video.youtube_id', null)
            ->assertJsonPath('poster_url', Storage::disk('public')->url('hero/posters/background.jpg'));
    }

    public function test_youtube_video_configuration_returns_only_validated_video_id(): void
    {
        HeroSetting::create([
            'id' => 1,
            'type' => 'video',
            'video_source' => 'youtube',
            'youtube_id' => 'Abc_def-123',
        ]);

        $this->getJson('/api/hero')
            ->assertOk()
            ->assertJsonPath('type', 'video')
            ->assertJsonPath('video.source', 'youtube')
            ->assertJsonPath('video.url', null)
            ->assertJsonPath('video.youtube_id', 'Abc_def-123');
    }

    public function test_incomplete_slider_configuration_returns_default(): void
    {
        Storage::disk('public')->put('hero/slides/one.jpg', 'image');
        HeroSetting::create([
            'id' => 1,
            'type' => 'slider',
            'slides' => ['hero/slides/one.jpg', 'hero/slides/missing.jpg'],
            'slide_interval' => 5,
        ]);

        $this->getJson('/api/hero')
            ->assertOk()
            ->assertJsonPath('type', 'default')
            ->assertJsonPath('slides', null);
    }

    public function test_invalid_youtube_link_is_rejected_by_filament_validation(): void
    {
        Livewire::test(ManageHeroSettings::class)
            ->set('data.type', 'video')
            ->set('data.video_source', 'youtube')
            ->set('data.youtube_url', 'https://example.com/watch?v=Abc_def-123')
            ->call('save')
            ->assertHasErrors(['data.youtube_url']);
    }

    public function test_youtube_link_forms_are_recognized_and_other_hosts_are_rejected(): void
    {
        foreach ([
            'https://www.youtube.com/watch?v=Abc_def-123',
            'https://youtu.be/Abc_def-123',
            'https://www.youtube.com/embed/Abc_def-123',
            'https://www.youtube.com/shorts/Abc_def-123',
        ] as $url) {
            $this->assertSame('Abc_def-123', HeroSetting::youtubeIdFromUrl($url));
        }

        $this->assertNull(HeroSetting::youtubeIdFromUrl('https://example.com/watch?v=Abc_def-123'));
    }

    public function test_filament_video_source_fields_change_with_selected_source(): void
    {
        Livewire::test(ManageHeroSettings::class)
            ->set('data.type', 'video')
            ->set('data.video_source', 'youtube')
            ->assertSee('Link YouTube')
            ->set('data.video_source', 'upload')
            ->assertSee('File Video');

        Livewire::test(ManageHeroSettings::class)
            ->set('data.type', 'slider')
            ->assertSee('Gambar Slide')
            ->assertSee('Interval Slide (detik)');
    }

    public function test_valid_youtube_link_saves_only_the_video_id(): void
    {
        Livewire::test(ManageHeroSettings::class)
            ->set('data.type', 'video')
            ->set('data.video_source', 'youtube')
            ->set('data.youtube_url', 'https://youtu.be/Abc_def-123')
            ->call('save')
            ->assertHasNoErrors();

        $setting = HeroSetting::query()->firstOrFail();

        $this->assertSame('Abc_def-123', $setting->youtube_id);
        $this->assertSame(1, HeroSetting::query()->count());
    }
}
