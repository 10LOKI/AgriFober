<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportCollection;
use App\Repositories\Contracts\ReportRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(
        private readonly ReportRepositoryInterface $reports
    ) {
    }

    /**
     * GET /api/reports
     * Paginated, optionally filtered list of reports. Non-admin callers
     * are scoped to their own reports.
     */
    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type'       => ['sometimes', 'in:fertilisation,diagnostic,rendement,sol,meteo'],
            'status'     => ['sometimes', 'in:brouillon,genere,valide,archive'],
            'parcel_id'  => ['sometimes', 'integer', 'min:1'],
            'culture_id' => ['sometimes', 'integer', 'min:1'],
            'search'     => ['sometimes', 'string', 'max:100'],
            'per_page'   => ['sometimes', 'integer', 'min:5', 'max:100'],
        ]);

        // Ownership scope: admins see everything, others only their own.
        if (! $request->user()->isAdmin()) {
            $validated['user_id'] = $request->user()->id;
        }

        $reports = $this->reports->paginate(
            filters: $validated,
            perPage: (int) ($validated['per_page'] ?? 15),
        );

        return (new ReportCollection($reports))
            ->additional(['success' => true])
            ->response();
    }
}
