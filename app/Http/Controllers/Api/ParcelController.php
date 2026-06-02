<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ParcelStoreRequest;
use App\Http\Requests\ParcelUpdateRequest;
use App\Http\Resources\ParcelResource;
use App\Http\Resources\RecommendationResource;
use App\Models\Parcel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ParcelController extends Controller
{
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
            'data'    => ParcelResource::collection($parcels),
            'meta'    => [
                'current_page' => $parcels->currentPage(),
                'last_page'    => $parcels->lastPage(),
                'per_page'     => $parcels->perPage(),
                'total'        => $parcels->total(),
            ],
        ]);
    }

    public function store(ParcelStoreRequest $request): JsonResponse
    {
        $this->authorize('create', Parcel::class);

        $validated = $request->validated();
        $parcel = $request->user()->parcels()->create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Parcel created successfully',
            'data'    => new ParcelResource($parcel->load('culture')),
        ], 201);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        $parcel = Parcel::with(['culture', 'interactionIas', 'weatherData' => function ($query) {
            $query->latest()->limit(10);
        }])->findOrFail($id);

        $this->authorize('view', $parcel);

        return response()->json([
            'success' => true,
            'data'    => new ParcelResource($parcel),
        ]);
    }

    public function update(ParcelUpdateRequest $request, string $id): JsonResponse
    {
        $parcel = Parcel::findOrFail($id);
        $this->authorize('update', $parcel);

        $parcel->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Parcel updated successfully',
            'data'    => new ParcelResource($parcel->load('culture')),
        ]);
    }

    public function destroy(string $id, Request $request): JsonResponse
    {
        $parcel = Parcel::findOrFail($id);
        $this->authorize('delete', $parcel);

        $parcel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Parcel deleted successfully',
        ]);
    }

    public function recommendations(string $id, Request $request): JsonResponse
    {
        $parcel = Parcel::with('culture.products')->findOrFail($id);
        $this->authorize('view', $parcel);

        if (!$parcel->culture_id) {
            return response()->json([
                'success' => true,
                'message' => 'No culture assigned to this parcel',
                'data'    => [],
            ]);
        }

        return response()->json([
            'success' => true,
            'data'    => RecommendationResource::collection($parcel->culture->products),
        ]);
    }
}
