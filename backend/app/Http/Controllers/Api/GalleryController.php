<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * GET /api/gallery
     * List all active gallery images sorted by order
     */
    public function index(Request $request): JsonResponse
    {
        $images = GalleryImage::where("is_active", true)
            ->orderBy("order", "asc")
            ->orderBy("id", "asc")
            ->get();

        return response()->json([
            "data" => $images,
        ]);
    }
}
