<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Booking;
use App\Models\BusinessSetting;
use App\Models\Motor;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * POST /api/bookings
     * Submit a new rental booking request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'customer_name' => 'required|string|max:100',
            'customer_email' => 'required|email|max:255',
            'customer_whatsapp' => 'nullable|string|max:30',
            'customer_document_type' => 'nullable|string|max:30',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'notes' => 'nullable|string',
            'lang' => 'required|string|in:id,en',
            'whatsapp_message' => 'required|string',
            'addons' => 'nullable|array',
            'addons.*.addon_id' => 'required|integer|exists:addons,id',
            'addons.*.quantity' => 'required|integer|min:1|max:20',
        ]);

        $motor = Motor::findOrFail($validated['motor_id']);

        $startDate = !empty($validated['start_date']) ? Carbon::parse($validated['start_date']) : Carbon::today();
        $endDate = !empty($validated['end_date']) ? Carbon::parse($validated['end_date']) : $startDate->copy();
        $totalDays = max(1, $startDate->diffInDays($endDate));
        $motorPriceTotal = $totalDays * (float) $motor->price_daily;

        // Process Add-ons
        $addonsSnapshot = [];
        $addonsTotal = 0;

        if (!empty($validated['addons'])) {
            $addonIds = collect($validated['addons'])->pluck('addon_id')->unique()->toArray();
            $addonsDb = Addon::whereIn('id', $addonIds)->get()->keyBy('id');

            foreach ($validated['addons'] as $addonInput) {
                $addon = $addonsDb->get($addonInput['addon_id']);
                if ($addon) {
                    $qty = (int) $addonInput['quantity'];
                    $price = (float) $addon->price;
                    $subtotal = $price * $qty;

                    $addonsTotal += $subtotal;
                    $addonsSnapshot[] = [
                        'id' => $addon->id,
                        'name' => $addon->name_id,
                        'name_en' => $addon->name_en,
                        'price' => $price,
                        'price_type' => $addon->price_type,
                        'quantity' => $qty,
                        'subtotal' => $subtotal,
                    ];
                }
            }
        }

        $totalPrice = $motorPriceTotal + $addonsTotal;

        $booking = Booking::create([
            'motor_id' => $motor->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'customer_whatsapp' => $validated['customer_whatsapp'] ?? null,
            'customer_document_type' => $validated['customer_document_type'] ?? null,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'total_days' => $totalDays,
            'total_price' => $totalPrice,
            'addons' => !empty($addonsSnapshot) ? $addonsSnapshot : null,
            'addons_total' => $addonsTotal,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
        ]);

        $waMessage = $validated['whatsapp_message'];

        $setting = BusinessSetting::find(1);
        $rawWa = $setting?->whatsapp_primary ?? '6285947167662';
        $cleanWaNumber = BusinessSetting::sanitizePhone($rawWa) ?? '6285947167662';
        $whatsappUrl = "https://wa.me/{$cleanWaNumber}?text=".urlencode($waMessage);

        return response()->json([
            'success' => true,
            'message' => 'Booking submitted successfully',
            'data' => [
                'id' => $booking->id,
                'booking_id' => $booking->id,
                'motor' => [
                    'id' => $motor->id,
                    'name' => $motor->name,
                    'main_image' => $motor->main_image ? (str_starts_with($motor->main_image, 'http') ? $motor->main_image : asset('storage/'.$motor->main_image)) : null,
                ],
                'customer_name' => $booking->customer_name,
                'customer_email' => $booking->customer_email,
                'customer_whatsapp' => $booking->customer_whatsapp,
                'customer_document_type' => $booking->customer_document_type,
                'start_date' => $booking->start_date->toDateString(),
                'end_date' => $booking->end_date->toDateString(),
                'total_days' => $booking->total_days,
                'total_price' => (float) $booking->total_price,
                'addons' => $booking->addons ?? [],
                'addons_total' => (float) $booking->addons_total,
                'status' => $booking->status,
                'whatsapp_url' => $whatsappUrl,
                'created_at' => $booking->created_at->toIso8601String(),
            ],
        ], 201);
    }

    /**
     * GET /api/bookings/{id}
     * Get booking details for confirmation page
     */
    public function show(int $id)
    {
        $booking = Booking::with('motor')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $booking->id,
                'motor' => [
                    'id' => $booking->motor->id,
                    'name' => $booking->motor->name,
                    'category' => $booking->motor->category,
                    'main_image' => $booking->motor->main_image ? (str_starts_with($booking->motor->main_image, 'http') ? $booking->motor->main_image : asset('storage/'.$booking->motor->main_image)) : null,
                ],
                'customer_name' => $booking->customer_name,
                'customer_email' => $booking->customer_email,
                'customer_whatsapp' => $booking->customer_whatsapp,
                'customer_document_type' => $booking->customer_document_type,
                'start_date' => $booking->start_date->toDateString(),
                'end_date' => $booking->end_date->toDateString(),
                'total_days' => $booking->total_days,
                'total_price' => (float) $booking->total_price,
                'addons' => $booking->addons ?? [],
                'addons_total' => (float) ($booking->addons_total ?? 0),
                'status' => $booking->status,
                'notes' => $booking->notes,
                'created_at' => $booking->created_at->toIso8601String(),
            ],
        ]);
    }
}
