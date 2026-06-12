<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(private readonly Product $model)
    {
    }

    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model
            ->newQuery()
            ->with(['cultures' => function ($q): void {
                $q->select('cultures.id', 'nom_commun');
            }])
            ->orderBy('nom_commercial');

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    public function findById(int|string $id): Product
    {
        return $this->model
            ->newQuery()
            ->with('cultures')
            ->findOrFail($id);
    }

    /**
     * Apply the supported list filters to the query.
     *
     * @param  Builder<Product>  $query
     * @param  array<string, mixed>  $filters
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function (Builder $q) use ($search): void {
                $q->where('nom_commercial', 'LIKE', '%' . $search . '%')
                    ->orWhere('composant_actif', 'LIKE', '%' . $search . '%');
            });
        }
    }
}
