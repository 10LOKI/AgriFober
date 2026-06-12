<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductIndexRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepositoryInterface $products
    ) {
    }

    /**
     * GET /api/products
     * Paginated, optionally filtered list of products.
     */
    public function index(ProductIndexRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $products = $this->products->paginate(
            filters: $validated,
            perPage: (int) ($validated['per_page'] ?? 20),
        );

        return (new ProductCollection($products))
            ->additional(['success' => true])
            ->response();
    }

    /**
     * GET /api/products/{product}
     * Detail view of a single product. Missing id yields a clean JSON 404
     * via the global handler (ModelNotFoundException).
     */
    public function show(string $product): JsonResponse
    {
        $model = $this->products->findById($product);

        return response()->json([
            'success' => true,
            'data'    => new ProductResource($model),
        ]);
    }

    /**
     * POST /api/products
     * Create a product (admin-gated at the route layer).
     */
    public function store(ProductStoreRequest $request): JsonResponse
    {
        $product = $this->products->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data'    => new ProductResource($product),
        ], 201);
    }

    /**
     * PUT /api/products/{product}
     * Update a product (admin-gated).
     */
    public function update(ProductUpdateRequest $request, string $product): JsonResponse
    {
        $model = $this->products->update($product, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
            'data'    => new ProductResource($model),
        ]);
    }

    /**
     * DELETE /api/products/{product}
     * Soft-delete (archive) a product (admin-gated).
     */
    public function destroy(string $product): JsonResponse
    {
        $this->products->delete($product);

        return response()->json([
            'success' => true,
            'message' => 'Product archived successfully.',
        ]);
    }
}
