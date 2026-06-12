<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportCollection;
use App\Http\Resources\ReportDetailResource;
use App\Http\Resources\ReportProgramResource;
use App\Models\Report;
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

    /**
     * GET /api/reports/{report}
     * Detailed view of a single report with its core relations eager-loaded.
     */
    public function show(Request $request, string $report): JsonResponse
    {
        $model = $this->reports->findDetailById($report);

        if ($denied = $this->guardOwnership($request, $model)) {
            return $denied;
        }

        return response()->json([
            'success' => true,
            'data'    => new ReportDetailResource($model),
        ]);
    }

    /**
     * GET /api/reports/{report}/program
     * Retrieve the agricultural/fertilization program generated in this report.
     */
    public function program(Request $request, string $report): JsonResponse
    {
        $model = $this->reports->findForProgram($report);

        if ($denied = $this->guardOwnership($request, $model)) {
            return $denied;
        }

        return response()->json([
            'success' => true,
            'data'    => new ReportProgramResource($model),
        ]);
    }

    /**
     * Non-admin callers may only access their own reports. Returns a clean
     * 403 JSON response when access is denied, or null when allowed.
     */
    private function guardOwnership(Request $request, Report $report): ?JsonResponse
    {
        $user = $request->user();

        if (! $user->isAdmin() && $report->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to access this report.',
            ], 403);
        }

        return null;
    }
}

