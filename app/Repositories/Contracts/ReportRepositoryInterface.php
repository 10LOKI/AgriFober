<?php

namespace App\Repositories\Contracts;

use App\Models\Report;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ReportRepositoryInterface
{
    /**
     * Endpoint 1 — GET /api/reports
     * Retrieve a paginated, optionally filtered list of reports.
     *
     * @param  array<string, mixed>  $filters
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Endpoint 2 — GET /api/reports/{id}
     * Retrieve a single report with its core relations eager-loaded.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findDetailById(int|string $id): Report;

    /**
     * Endpoint 3 — GET /api/reports/{id}/program
     * Retrieve the report carrying its generated agricultural/fertilization
     * program payload.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findForProgram(int|string $id): Report;

    /**
     * Endpoint 4 — GET /api/reports/{id}/history
     * Retrieve the paginated change log linked to a report.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function paginateHistory(int|string $id, int $perPage = 15): LengthAwarePaginator;
}
