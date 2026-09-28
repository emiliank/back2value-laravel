<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Battery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BatteryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Battery::query();

        if ($request->filled('capacity')) {
            $capacity = $request->input('capacity');

            if (str_contains((string) $capacity, ',')) {
                $values = array_map('intval', explode(',', $capacity));
                $query->whereIn('capacity_ah', $values);
            } elseif (str_contains((string) $capacity, '-')) {
                [$min, $max] = array_map('intval', explode('-', $capacity, 2));
                $query->whereBetween('capacity_ah', [$min, $max]);
            } else {
                $query->where('capacity_ah', (int) $capacity);
            }
        }

        if ($request->filled('application_type')) {
            $query->where('application_type', $request->input('application_type'));
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('status', $status);
        }

        $perPage = max(1, min(100, (int) $request->input('per_page', 15)));

        $batteries = $query->orderBy('capacity_ah')->paginate($perPage);

        return response()->json([
            'data' => $batteries->items(),
            'meta' => [
                'current_page' => $batteries->currentPage(),
                'per_page' => $batteries->perPage(),
                'total' => $batteries->total(),
                'last_page' => $batteries->lastPage(),
            ],
            'links' => [
                'first' => $batteries->url(1),
                'last' => $batteries->url($batteries->lastPage()),
                'prev' => $batteries->previousPageUrl(),
                'next' => $batteries->nextPageUrl(),
            ],
        ]);
    }
}
