<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use Illuminate\Http\JsonResponse;

class AddonController extends Controller
{
    /**
     * GET /api/addons
     * List all active add-ons for the reservation form
     */
    public function index(): JsonResponse
    {
        $addons = Addon::active()
            ->get()
            ->map(function ($addon) {
                return [
                    'id' => $addon->id,
                    'name_id' => $addon->name_id,
                    'name_en' => $addon->name_en,
                    'description_id' => $addon->description_id,
                    'description_en' => $addon->description_en,
                    'price' => (float) $addon->price,
                    'price_type' => $addon->price_type,
                    'sort_order' => $addon->sort_order,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $addons,
        ]);
    }
}
