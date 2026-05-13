<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of all products (catalogue).
     */
    public function index(): JsonResponse
    {
        $products = Product::with(['cultures' => function ($query) {
            $query->select('cultures.id', 'nom_commun');
        }])
        ->orderBy('nom_commercial')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $products
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(string $id): JsonResponse
    {
        $product = Product::with('cultures')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $product
        ]);
    }

    /**
     * Store a newly created product (Admin only).
     */
    public function store(Request $request): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Admin access required.'
        ], 403);
    }

    /**
     * Update the specified product (Admin only).
     */
    public function update(Request $request, string $id): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Admin access required.'
        ], 403);
    }

    /**
     * Remove the specified product (Admin only).
     */
    public function destroy(string $id): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Admin access required.'
        ], 403);
    }
}
