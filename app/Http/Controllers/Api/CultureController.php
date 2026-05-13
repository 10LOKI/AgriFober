<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Culture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CultureController extends Controller
{
    /**
     * Display a listing of all cultures (catalogue).
     */
    public function index(): JsonResponse
    {
        $cultures = Culture::with(['products' => function ($query) {
            $query->select('products.id', 'nom_commercial', 'type');
        }])->orderBy('nom_commun')->get();

        return response()->json([
            'success' => true,
            'data' => $cultures
        ]);
    }

    /**
     * Display the specified culture.
     */
    public function show(string $id): JsonResponse
    {
        $culture = Culture::with('products')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $culture
        ]);
    }

    /**
     * Store a newly created culture (Admin only).
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Admin access required.'
        ], 403);
    }

    /**
     * Update the specified culture (Admin only).
     */
    public function update(Request $request, string $id): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Admin access required.'
        ], 403);
    }

    /**
     * Remove the specified culture (Admin only).
     */
    public function destroy(string $id): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Admin access required.'
        ], 403);
    }
}
