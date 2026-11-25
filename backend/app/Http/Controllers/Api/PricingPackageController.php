<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PricingPackage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PricingPackageController extends Controller
{
    /**
     * GET /api/pricing-packages
     * List all active pricing packages sorted by order
     */
    public function index(Request $request): JsonResponse
    {
        $packages = PricingPackage::where("is_active", true)
            ->orderBy("order", "asc")
            ->orderBy("id", "asc")
            ->get();

        return response()->json([
            "data" => $packages,
        ]);
    }
}
