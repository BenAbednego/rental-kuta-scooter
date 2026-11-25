<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ReviewSetting;
use App\Services\IframeParser;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class ReviewSettingController extends Controller
{
    public function show(IframeParser $parser): JsonResponse
    {
        $setting = ReviewSetting::query()->first();

        if (! $setting || $setting->display_mode !== 'iframe' || blank($setting->iframe_src)) {
            return response()->json(['mode' => 'manual']);
        }

        try {
            $iframeCode = '<iframe src="'.e($setting->iframe_src).'"'
                .' title="'.e($setting->iframe_title ?? '').'"'
                .' width="'.($setting->iframe_width ?? '').'"'
                .' height="'.($setting->iframe_height ?? '').'"></iframe>';
            $parsed = $parser->parse($iframeCode);
        } catch (InvalidArgumentException) {
            return response()->json(['mode' => 'manual']);
        }

        if ($parsed['provider_label'] !== $setting->iframe_provider) {
            return response()->json(['mode' => 'manual']);
        }

        return response()->json([
            'mode' => 'iframe',
            'iframe' => [
                'src' => $parsed['src'],
                'title' => $parsed['title'],
                'height' => $parsed['height'],
                'provider_label' => $parsed['provider_label'],
            ],
        ]);
    }
}
