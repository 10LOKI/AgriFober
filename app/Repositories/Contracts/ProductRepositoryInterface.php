<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /**
     * Retrieve a paginated, optionally filtered list of products.
     *
     * @param  array<string, mixed>  $filters  Supported: type, search.
     */
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    /**
     * Retrieve a single product (with its linked cultures) by id.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(int|string $id): Product;

    /**
     * Create a product. A `culture_ids` key, if present, syncs the pivot.
     *
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Product;

    /**
     * Update a product by id. A `culture_ids` key, if present, syncs the pivot.
     *
     * @param  array<string, mixed>  $data
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function update(int|string $id, array $data): Product;

    /**
     * Soft-delete (archive) a product by id.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function delete(int|string $id): bool;
}
