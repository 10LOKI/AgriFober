<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Register a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::defaults()],
            'role' => ['sometimes', 'in:admin,agriculteur,technicien'],
            'region' => ['nullable', 'string', 'max:255'],
            'experience_level' => ['nullable', 'in:debutant,intermediaire,expert'],
            'surface_totale' => ['nullable', 'numeric', 'min:0'],
            'employee_code' => ['nullable', 'string', 'max:255', 'unique:users,employee_code'],
        ]);

        // Default role for regular registration
        $validated['role'] = $validated['role'] ?? RoleEnum::AGRICULTEUR->value;

        $user = User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'region' => $validated['region'] ?? null,
            'experience_level' => $validated['experience_level'] ?? null,
            'surface_totale' => $validated['surface_totale'] ?? null,
            'employee_code' => $validated['employee_code'] ?? null,
            'is_approved' => false,
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'User registered successfully',
        ], 201);
    }

    /**
     * Login user and issue API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
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
                'message' => 'Votre compte est en attente de validation.'
            ], 403);
        }

        $token = $user->createToken('api-token')->plainTextToken;
    }

    /**
     * Logout user (revoke current token).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    /**
     * Get authenticated user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
        ]);
    }

    /**
     * Get farmer profile with summary statistics.
     * GET /api/farmer/profile
     */
    public function farmerProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user->isAgriculteur() && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $parcelsCount = $user->parcels()->count();
        $surfaceTotale = $user->parcels()->sum('surface');
        $culturesUsed = $user->parcels()
            ->whereNotNull('culture_id')
            ->distinct('culture_id')
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'profile' => $user->only(['id', 'username', 'name', 'email', 'region', 'experience_level', 'surface_totale', 'created_at']),
                'statistics' => [
                    'total_parcels' => $parcelsCount,
                    'total_surface_ha' => round($surfaceTotale, 4),
                    'cultures_cultivated' => $culturesUsed,
                ]
            ]
        ]);
    }

    /**
     * Update farmer profile.
     * PUT /api/farmer/profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if (!$user->isAgriculteur() && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:255'],
            'experience_level' => ['nullable', 'in:debutant,intermediaire,expert'],
            'surface_totale' => ['nullable', 'numeric', 'min:0'],
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }
}
