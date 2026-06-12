<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CultureCollection;
use App\Http\Resources\CultureResource;
use App\Http\Resources\ParcelResource;
use App\Http\Resources\ProductResource;
use App\Repositories\Contracts\CultureRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CultureController extends Controller
{
    public function __construct(
        private readonly CultureRepositoryInterface $cultures
    ) {
    }

    /**
     * GET /api/cultures
     * Paginated, optionally filtered list of cultures.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'region'   => ['sometimes', 'string', 'max:100'],
            'type'     => ['sometimes', 'in:fruit,legume,cereale,legumineuse,autre'],
            'saison'   => ['sometimes', 'in:printemps,ete,automne,hiver,toute_annee'],
            'search'   => ['sometimes', 'string', 'max:100'],
            'per_page' => ['sometimes', 'integer', 'min:5', 'max:100'],
        ]);

        $cultures = $this->cultures->paginate(
            filters: $validated,
            perPage: (int) ($validated['per_page'] ?? 20),
        );

        return (new CultureCollection($cultures))
            ->additional(['success' => true])
            ->response();
    }

    /**
     * GET /api/cultures/{culture}
     * Details of a single culture.
     */
    public function show(string $culture): JsonResponse
    {
        $model = $this->cultures->findById($culture);

        return response()->json([
            'success' => true,
            'data'    => new CultureResource($model),
        ]);
    }

    /**
     * GET /api/cultures/{culture}/associated
     * Data linked to a culture: recommended products and the parcels growing it.
     */
    public function associated(string $culture): JsonResponse
    {
        $model = $this->cultures->findWithAssociations($culture);

        return response()->json([
            'success' => true,
            'data'    => [
                'culture'    => new CultureResource($model),
                'associated' => [
                    'parcels_count' => $model->parcels_count,
                    'products'      => ProductResource::collection($model->products),
                    'parcels'       => ParcelResource::collection($model->parcels),
                ],
            ],
        ]);
    }

    /**
     * Admin-only write operations are gated at the route layer; these
     * stubs keep the apiResource contract intact and return a clean 403.
     */
    public function store(Request $request): JsonResponse
    {
        return $this->forbidden();
    }

    public function update(Request $request, string $culture): JsonResponse
    {
        return $this->forbidden();
    }

    public function destroy(string $culture): JsonResponse
    {
        return $this->forbidden();
    }

    private function forbidden(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Admin access required.',
        ], 403);
    }
}
