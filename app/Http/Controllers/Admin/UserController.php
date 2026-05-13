<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);

        return inertia('Admin/Users/Index', [
            'users' => $users,
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function show(User $user)
    {
        return inertia('Admin/Users/Show', [
            'user' => $user,
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function create()
    {
        return inertia('Admin/Users/Create', [
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function store(Request $request)
    {
        // Implementation similar to AuthController but admin-only
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'role' => ['required', 'in:admin,agriculteur,technicien'],
            'region' => ['nullable', 'string', 'max:255'],
            'is_approved' => ['boolean'],
        ]);

        User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'region' => $validated['region'] ?? null,
            'is_approved' => $validated['is_approved'] ?? false,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé');
    }

    public function edit(User $user)
    {
        return inertia('Admin/Users/Edit', [
            'user' => $user,
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,agriculteur,technicien'],
            'region' => ['nullable', 'string', 'max:255'],
            'is_approved' => ['boolean'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.show', $user)->with('success', 'Utilisateur mis à jour');
    }

    public function approve(User $user)
    {
        $user->update(['is_approved' => true]);
        return back()->with('success', 'Utilisateur approuvé');
    }

    public function reject(User $user)
    {
        $user->update(['is_approved' => false]);
        return back()->with('warning', 'Utilisateur rejeté');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez supprimer votre propre compte');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé');
    }
}