<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parcel;
use App\Models\WeatherData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WeatherDataController extends Controller
{
    /**
     * Get current weather for a specific parcel.
     * GET /api/weather/parcel/{parcel}
     */
    public function current(Request $request, string $parcel): JsonResponse
    {
        $user = $request->user();
        
        $parcelModel = Parcel::findOrFail($parcel);
        
        // Check ownership
        if ($parcelModel->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Get latest weather data for parcel
        $weather = $parcelModel->weatherData()
            ->orderBy('recorded_at', 'desc')
            ->first();

        if (!$weather) {
            // If no weather data exists, return simulated data (until API integration)
            $weather = (object) [
                'temp' => 25.0,
                'humidity' => 65,
                'precipitation' => 0,
                'wind_speed' => 10,
                'condition' => 'Ensoleillé',
                'source' => 'simulated',
                'recorded_at' => now(),
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'parcel' => $parcelModel->nom,
                'location' => [
                    'latitude' => $parcelModel->latitude,
                    'longitude' => $parcelModel->longitude
                ],
                'weather' => $weather
            ]
        ]);
    }

    /**
     * Get weather forecast for a parcel (5 days).
     * GET /api/weather/forecast/{parcel}
     */
    public function forecast(Request $request, string $parcel): JsonResponse
    {
        $user = $request->user();
        
        $parcelModel = Parcel::findOrFail($parcel);
        
        // Check ownership
        if ($parcelModel->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Simulated forecast data (will be replaced with OpenWeather API)
        $forecast = [];
        for ($i = 0; $i < 5; $i++) {
            $date = now()->addDays($i);
            $forecast[] = [
                'date' => $date->format('Y-m-d'),
                'temp_max' => rand(25, 32),
                'temp_min' => rand(18, 24),
                'humidity' => rand(50, 90),
                'precipitation_chance' => rand(0, 80),
                'condition' => ['Ensoleillé', 'Nuageux', 'Pluie'][rand(0, 2)],
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'parcel' => $parcelModel->nom,
                'forecast' => $forecast
            ]
        ]);
    }

    /**
     * Store weather data for a parcel (internal use).
     * POST /api/weather/store (Admin or system)
     */
    public function store(Request $request): JsonResponse
    {
        // This endpoint will be used by a scheduler or admin to fetch/store weather
        return response()->json([
            'success' => false,
            'message' => 'Not implemented yet'
        ], 501);
    }
}
