<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * GET /api/faqs
     * List all active FAQs sorted by order
     */
    public function index(Request $request): JsonResponse
    {
        $faqs = Faq::where("is_active", true)
            ->orderBy("order", "asc")
            ->orderBy("id", "asc")
            ->get();

        return response()->json([
            "data" => $faqs,
        ]);
    }
}
