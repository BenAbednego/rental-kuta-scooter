<?php

namespace Tests\Feature;

use App\Models\PageSeo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageSeoApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_known_page_returns_localized_seo_and_absolute_featured_image_url(): void
    {
        PageSeo::query()->where('page_key', 'home')->update([
            'title_id' => 'Judul Indonesia',
            'title_en' => 'English title',
            'description_id' => 'Deskripsi Indonesia',
            'description_en' => 'English description',
            'featured_image' => 'page-seo/home.webp',
        ]);

        $response = $this->getJson('/api/seo/home')
            ->assertOk()
            ->assertJsonPath('id.title', 'Judul Indonesia')
            ->assertJsonPath('id.description', 'Deskripsi Indonesia')
            ->assertJsonPath('en.title', 'English title')
            ->assertJsonPath('en.description', 'English description');

        $this->assertStringStartsWith('http', $response->json('image_url'));
    }

    public function test_unknown_page_key_returns_not_found(): void
    {
        $this->getJson('/api/seo/not-a-page')->assertNotFound();
    }

    public function test_empty_seo_values_are_returned_as_null(): void
    {
        PageSeo::query()->where('page_key', 'home')->update([
            'title_id' => '',
            'title_en' => null,
            'description_id' => '',
            'description_en' => null,
            'featured_image' => null,
        ]);

        $this->getJson('/api/seo/home')
            ->assertOk()
            ->assertExactJson([
                'id' => ['title' => null, 'description' => null],
                'en' => ['title' => null, 'description' => null],
                'image_url' => null,
            ]);
    }
}
