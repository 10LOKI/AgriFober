<?php

namespace App\Repositories\Eloquent;

use App\Models\Culture;
use App\Repositories\Contracts\CultureRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CultureRepository implements CultureRepositoryInterface
{
    public function __construct(private readonly Culture $model)
    {
    }

    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = $this->model
            ->newQuery()
            ->with(['products' => function ($q): void {
                $q->select('products.id', 'nom_commercial', 'type');
            }])
            ->orderBy('nom_commun');

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    public function findById(int|string $id): Culture
    {
        return $this->model
            ->newQuery()
            ->with('products')
            ->findOrFail($id);
    }

    public function findWithAssociations(int|string $id): Culture
    {
        return $this->model
            ->newQuery()
            ->with([
                'products',
                'parcels' => function ($q): void {
                    $q->with('culture')->latest();
                },
            ])
            ->withCount('parcels')
            ->findOrFail($id);
    }

    /**
     * Apply the supported list filters to the query.
     *
     * @param  Builder<Culture>  $query
     * @param  array<string, mixed>  $filters
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['region'])) {
            $query->where('region', 'LIKE', '%' . $filters['region'] . '%');
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['saison'])) {
            $query->where('saison', $filters['saison']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function (Builder $q) use ($search): void {
                $q->where('nom_commun', 'LIKE', '%' . $search . '%')
                    ->orWhere('nom_scientifique', 'LIKE', '%' . $search . '%');
            });
        }
    }
}
