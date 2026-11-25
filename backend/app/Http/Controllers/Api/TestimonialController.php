<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        $testimonials = Testimonial::query()
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('id')
            ->get([
                'id',
                'name',
                'location',
                'text_id',
                'text_en',
                'rating',
                'order',
            ]);

        return response()->json([
            'data' => $testimonials,
        ]);
    }
}
