<?php

namespace App\Http\Controllers\Api;

use App\Enums\ParcelStatusEnum;
use App\Enums\RoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\ParcelResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username'         => ['required', 'string', 'max:255', 'unique:users,username'],
            'name'             => ['required', 'string', 'max:255'],
            'email'            => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'         => ['required', 'string', 'confirmed', Password::defaults()],
            'role'             => ['sometimes', 'in:admin,agriculteur,technicien'],
            'region'           => ['nullable', 'string', 'max:255'],
            'experience_level' => ['nullable', 'in:debutant,intermediaire,expert'],
            'surface_totale'   => ['nullable', 'numeric', 'min:0'],
            'employee_code'    => ['nullable', 'string', 'max:255', 'unique:users,employee_code'],
        ]);

        $validated['role'] = $validated['role'] ?? RoleEnum::AGRICULTEUR->value;

        $user = User::create([
            'username'         => $validated['username'],
            'name'             => $validated['name'],
            'email'            => $validated['email'],
            'password'         => Hash::make($validated['password']),
            'role'             => $validated['role'],
            'region'           => $validated['region'] ?? null,
            'experience_level' => $validated['experience_level'] ?? null,
            'surface_totale'   => $validated['surface_totale'] ?? null,
            'employee_code'    => $validated['employee_code'] ?? null,
            'is_approved'      => false,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully. Account awaiting approval.',
            'data'    => [
                'user'  => new UserResource($user),
                'token' => $token,
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->is_approved) {
            return response()->json([
                'success' => false,
                'message' => 'Votre compte est en attente de validation.',
            ], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data'    => [
                'user'  => new UserResource($user),
                'token' => $token,
            ],
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new UserResource($request->user()),
        ]);
    }

    public function farmerProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->isAgriculteur() && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $parcelsCount   = $user->parcels()->count();
        $surfaceTotale  = $user->parcels()->sum('surface');
        $culturesUsed   = $user->parcels()
            ->whereNotNull('culture_id')
            ->distinct('culture_id')
            ->count();

        return response()->json([
            'success' => true,
            'data'    => [
                'profile'    => new UserResource($user),
                'statistics' => [
                    'total_parcels'      => $parcelsCount,
                    'total_surface_ha'   => round((float) $surfaceTotale, 4),
                    'cultures_cultivated' => $culturesUsed,
                ],
            ],
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->isAgriculteur() && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'name'             => ['sometimes', 'string', 'max:255'],
            'region'           => ['nullable', 'string', 'max:255'],
            'experience_level' => ['nullable', 'in:debutant,intermediaire,expert'],
            'surface_totale'   => ['nullable', 'numeric', 'min:0'],
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data'    => new UserResource($user->fresh()),
        ]);
    }

    public function dashboard(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->isAgriculteur() && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $parcels = $user->parcels()->with('culture:id,nom_commun')->get();

        $activeParcels     = $parcels->filter(fn($p) => $p->status === ParcelStatusEnum::GROW)->count();
        $culturesUsed      = $parcels->whereNotNull('culture_id')->pluck('culture_id')->unique()->count();
        $avgHealthScore    = $parcels->avg('health_score');
        $aiInteractionsTotal = $user->interactionIas()->count();

        $recentParcels = $parcels->sortByDesc('created_at')->take(5)->values();

        return response()->json([
            'success' => true,
            'data'    => [
                'statistics'   => [
                    'total_parcels'         => $parcels->count(),
                    'active_parcels'        => $activeParcels,
                    'total_surface_ha'      => round((float) $parcels->sum('surface'), 4),
                    'cultures_cultivated'   => $culturesUsed,
                    'avg_health_score'      => $avgHealthScore ? round($avgHealthScore, 1) : null,
                    'ai_interactions_total' => $aiInteractionsTotal,
                ],
                'recent_parcels' => ParcelResource::collection($recentParcels),
                'profile_complete' => (bool) ($user->region && $user->experience_level),
            ],
        ]);
    }
}
