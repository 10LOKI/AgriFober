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

    public function create(array $data): Product
    {
        $cultureIds = $this->pullCultureIds($data);

        $product = $this->model->newQuery()->create($data);

        if ($cultureIds !== null) {
            $product->cultures()->sync($cultureIds);
        }

        return $product->load('cultures');
    }

    public function update(int|string $id, array $data): Product
    {
        $product    = $this->model->newQuery()->findOrFail($id);
        $cultureIds = $this->pullCultureIds($data);

        $product->update($data);

        if ($cultureIds !== null) {
            $product->cultures()->sync($cultureIds);
        }

        return $product->load('cultures');
    }

    public function delete(int|string $id): bool
    {
        $product = $this->model->newQuery()->findOrFail($id);

        return (bool) $product->delete();
    }

    /**
     * Extract and remove the optional culture_ids key from the payload.
     *
     * @param  array<string, mixed>  $data
     * @return array<int, int>|null
     */
    private function pullCultureIds(array &$data): ?array
    {
        if (! array_key_exists('culture_ids', $data)) {
            return null;
        }

        $ids = $data['culture_ids'];
        unset($data['culture_ids']);

        return is_array($ids) ? array_map('intval', $ids) : [];
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
