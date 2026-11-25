<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class HeroController extends Controller
{
    public function show(): JsonResponse
    {
        $setting = HeroSetting::query()->first();

        if (! $setting) {
            return $this->defaultResponse();
        }

        $disk = Storage::disk('public');

        if ($setting->type === 'single_image' && $setting->image && $disk->exists($setting->image)) {
            return response()->json([
                'type' => 'single_image',
                'image_url' => asset('storage/'.ltrim($setting->image, '/')),
                'slides' => null,
                'slide_interval' => null,
                'video' => null,
                'poster_url' => null,
            ]);
        }

        if ($setting->type === 'slider') {
            $slides = collect($setting->slides ?? [])
                ->filter(fn ($path) => is_string($path) && $disk->exists($path))
                ->map(fn ($path) => asset('storage/'.ltrim($path, '/')))
                ->values();

            if ($slides->count() >= 2) {
                return response()->json([
                    'type' => 'slider',
                    'image_url' => null,
                    'slides' => $slides,
                    'slide_interval' => $setting->slide_interval,
                    'video' => null,
                    'poster_url' => null,
                ]);
            }
        }

        if ($setting->type === 'video') {
            $posterUrl = $setting->poster && $disk->exists($setting->poster)
                ? asset('storage/'.ltrim($setting->poster, '/'))
                : null;

            if ($setting->video_source === 'upload' && $setting->video_path && $disk->exists($setting->video_path)) {
                return response()->json([
                    'type' => 'video',
                    'image_url' => null,
                    'slides' => null,
                    'slide_interval' => null,
                    'video' => [
                        'source' => 'upload',
                        'url' => asset('storage/'.ltrim($setting->video_path, '/')),
                        'youtube_id' => null,
                    ],
                    'poster_url' => $posterUrl,
                ]);
            }

            if ($setting->video_source === 'youtube' && HeroSetting::youtubeIdFromUrl(
                'https://www.youtube.com/watch?v='.$setting->youtube_id
            ) === $setting->youtube_id) {
                return response()->json([
                    'type' => 'video',
                    'image_url' => null,
                    'slides' => null,
                    'slide_interval' => null,
                    'video' => [
                        'source' => 'youtube',
                        'url' => null,
                        'youtube_id' => $setting->youtube_id,
                    ],
                    'poster_url' => $posterUrl,
                ]);
            }
        }

        return $this->defaultResponse();
    }

    private function defaultResponse(): JsonResponse
    {
        return response()->json([
            'type' => 'default',
            'image_url' => null,
            'slides' => null,
            'slide_interval' => null,
            'video' => null,
            'poster_url' => null,
        ]);
    }
}
