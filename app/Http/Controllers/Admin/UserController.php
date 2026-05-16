<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = User::orderBy('created_at', 'desc');
        
        if ($request->has('filter')) {
            if ($request->filter === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->filter === 'approved') {
                $query->where('is_approved', true);
            }
        }
        
        $users = $query->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['parcels' => function ($q) {
            $q->with('culture')->orderBy('created_at', 'desc');
        }]);
        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        $user = new User();
        return view('admin.users.edit', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
            'role' => ['required', 'in:admin,agriculteur,technicien'],
            'region' => ['nullable', 'string', 'max:255'],
            'experience_level' => ['nullable', 'in:debutant,intermediaire,expert'],
            'is_approved' => ['boolean'],
        ]);

        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users,username,'.$user->id],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:admin,agriculteur,technicien'],
            'region' => ['nullable', 'string', 'max:255'],
            'experience_level' => ['nullable', 'in:debutant,intermediaire,expert'],
            'is_approved' => ['boolean'],
        ]);

        // Ne pas overwriter le password si pas fourni
        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

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
        return back()->with('warning', 'Utilisateur marqué comme non approuvé');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé');
    }
}
