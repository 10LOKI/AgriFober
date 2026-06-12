<?php

namespace App\Repositories\Contracts;

use App\Models\Culture;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CultureRepositoryInterface
{
    /**
     * Retrieve a paginated, optionally filtered list of cultures.
     *
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator;

    /**
     * Retrieve a single culture by its identifier.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findById(int|string $id): Culture;

    /**
     * Retrieve a culture with its associated relations eager-loaded
     * (linked products and the parcels currently growing it).
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findWithAssociations(int|string $id): Culture;
}
