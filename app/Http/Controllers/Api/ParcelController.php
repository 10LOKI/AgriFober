<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParcelStoreRequest;
use App\Http\Requests\ParcelUpdateRequest;
use App\Models\Parcel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParcelController extends Controller
{
    /**
     * Display a listing of the user's parcels.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $parcels = $user->parcels()
            ->with(['culture'])
            ->withCount('weatherData')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $parcels
        ]);
    }

    /**
     * Store a newly created parcel for the authenticated user.
     * Policy: create
     */
    public function store(ParcelStoreRequest $request): JsonResponse
    {
        $this->authorize('create', Parcel::class);
        
        $validated = $request->validated();
        $parcel = $request->user()->parcels()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Parcel created successfully',
            'data' => $parcel->load('culture')
        ], 201);
    }

    /**
     * Display the specified parcel.
     * Policy: view
     */
    public function show(string $id, Request $request): JsonResponse
    {
        $parcel = Parcel::with(['culture', 'interactionIas', 'weatherData' => function ($query) {
            $query->latest()->limit(10);
        }])->findOrFail($id);

        $this->authorize('view', $parcel);

        return response()->json([
            'success' => true,
            'data' => $parcel
        ]);
    }

    /**
     * Update the specified parcel.
     * Policy: update
     */
    public function update(ParcelUpdateRequest $request, string $id): JsonResponse
    {
        $parcel = Parcel::findOrFail($id);
        $this->authorize('update', $parcel);

        $parcel->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Parcel updated successfully',
            'data' => $parcel->load('culture')
        ]);
    }

    /**
     * Remove the specified parcel.
     * Policy: delete
     */
    public function destroy(string $id, Request $request): JsonResponse
    {
        $parcel = Parcel::findOrFail($id);
        $this->authorize('delete', $parcel);

        $parcel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Parcel deleted successfully'
        ]);
    }
}
