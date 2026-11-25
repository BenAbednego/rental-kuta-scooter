<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PageSeo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class PageSeoController extends Controller
{
    public function show(string $pageKey): JsonResponse
    {
        abort_unless(array_key_exists($pageKey, config('seo_pages.pages', [])), 404);

        $seo = PageSeo::query()->where('page_key', $pageKey)->firstOrFail();

        return response()->json([
            'id' => [
                'title' => $seo->title_id ?: null,
                'description' => $seo->description_id ?: null,
            ],
            'en' => [
                'title' => $seo->title_en ?: null,
                'description' => $seo->description_en ?: null,
            ],
            'image_url' => $seo->featured_image
                ? asset('storage/'.ltrim($seo->featured_image, '/'))
                : null,
        ]);
    }
}
