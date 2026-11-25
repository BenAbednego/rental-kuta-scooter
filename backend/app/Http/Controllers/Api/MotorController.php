<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MotorApiResource;
use App\Models\Motor;
use Illuminate\Http\Request;

class MotorController extends Controller
{
    /**
     * GET /api/motors
     * List all active motors, optionally filtered by ?category=
     */
    public function index(Request $request)
    {
        $query = Motor::where('status', 'active');

        if ($request->has('category') && ! empty($request->query('category'))) {
            $query->where('category', $request->query('category'));
        }

        $motors = $query->orderBy('name', 'asc')->get();

        return MotorApiResource::collection($motors);
    }

    /**
     * GET /api/motors/{slug}
     * Get single motor details by slug
     */
    public function show(string $slug)
    {
        $motor = Motor::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        return new MotorApiResource($motor);
    }

    /**
     * GET /api/motors/{id}/availability?start=&end=
     * Check if a motor is available for given date range (PRD Section 5.4)
     */
    public function checkAvailability(Request $request, int $id)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $motor = Motor::findOrFail($id);

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Overlapping bookings: (start_date <= request_end AND end_date >= request_start)
        $overlappingBookings = $motor->bookings()
            ->whereIn('status', ['confirmed', 'ongoing', 'blocked'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->where('start_date', '<=', $endDate)
                    ->where('end_date', '>=', $startDate);
            })
            ->count();

        $availableUnits = max(0, $motor->unit_count - $overlappingBookings);
        $isAvailable = $availableUnits > 0;

        return response()->json([
            'success' => true,
            'motor_id' => $motor->id,
            'unit_count' => $motor->unit_count,
            'booked_count' => $overlappingBookings,
            'available_units' => $availableUnits,
            'is_available' => $isAvailable,
        ]);
    }
}
