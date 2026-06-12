<?php

namespace App\Repositories\Eloquent;

use App\Models\Report;
use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class ReportRepository implements ReportRepositoryInterface
{
    public function __construct(private readonly Report $model)
    {
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model
            ->newQuery()
            ->with([
                'user:id,name,email',
                'parcel:id,nom,surface',
                'culture:id,nom_commun,type',
            ])
            ->latest();

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    public function findDetailById(int|string $id): Report
    {
        return $this->model
            ->newQuery()
            ->with([
                'user',
                'parcel.culture',
                'culture',
            ])
            ->withCount('histories')
            ->findOrFail($id);
    }

    public function findForProgram(int|string $id): Report
    {
        return $this->model
            ->newQuery()
            ->with([
                'culture:id,nom_commun,type',
                'parcel:id,nom,surface',
            ])
            ->findOrFail($id);
    }

    public function paginateHistory(int|string $id, int $perPage = 15): LengthAwarePaginator
    {
        $report = $this->model->newQuery()->findOrFail($id);

        return $report->histories()
            ->with('user:id,name,email')
            ->latest()
            ->latest('id')
            ->paginate($perPage);
    }

    /**
     * Apply the supported list filters to the query.
     *
     * @param  Builder<Report>  $query
     * @param  array<string, mixed>  $filters
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        // Ownership scope — non-admin callers only see their own reports.
        if (! empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (! empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (! empty($filters['parcel_id'])) {
            $query->where('parcel_id', $filters['parcel_id']);
        }

        if (! empty($filters['culture_id'])) {
            $query->where('culture_id', $filters['culture_id']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function (Builder $q) use ($search): void {
                $q->where('titre', 'LIKE', '%' . $search . '%')
                    ->orWhere('resume', 'LIKE', '%' . $search . '%');
            });
        }
    }
}
