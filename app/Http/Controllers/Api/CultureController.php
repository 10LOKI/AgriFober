<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CultureResource;
use App\Models\Culture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CultureController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'region'   => ['sometimes', 'string', 'max:100'],
            'type'     => ['sometimes', 'in:fruit,legume,cereale,legumineuse,autre'],
            'saison'   => ['sometimes', 'in:printemps,ete,automne,hiver,toute_annee'],
            'search'   => ['sometimes', 'string', 'max:100'],
            'per_page' => ['sometimes', 'integer', 'min:5', 'max:100'],
        ]);

        $query = Culture::with(['products' => function ($q) {
            $q->select('products.id', 'nom_commercial', 'type');
        }])->orderBy('nom_commun');

        if ($request->filled('region')) {
            $query->where('region', 'LIKE', '%' . $request->region . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('saison')) {
            $query->where('saison', $request->saison);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nom_commun', 'LIKE', '%' . $search . '%')
                  ->orWhere('nom_scientifique', 'LIKE', '%' . $search . '%');
            });
        }

        $cultures = $query->paginate($request->integer('per_page', 20));

        return response()->json([
            'success' => true,
            'data'    => CultureResource::collection($cultures),
            'meta'    => [
                'current_page' => $cultures->currentPage(),
                'last_page'    => $cultures->lastPage(),
                'per_page'     => $cultures->perPage(),
                'total'        => $cultures->total(),
            ],
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $culture = Culture::with('products')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => new CultureResource($culture),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Admin access required.'
        ], 403);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Admin access required.'
        ], 403);
    }

    public function destroy(string $id): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Admin access required.'
        ], 403);
    }
}
