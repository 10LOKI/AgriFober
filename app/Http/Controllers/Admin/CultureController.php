<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Culture;
use Illuminate\Http\Request;

class CultureController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Request $request)
    {
        $cultures = Culture::withCount('parcels', 'products')
            ->orderBy('nom_commun')
            ->paginate(15);

        return inertia('Admin/Cultures/Index', [
            'cultures' => $cultures,
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function create()
    {
        return inertia('Admin/Cultures/Create', [
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_commun' => ['required', 'string', 'max:255'],
            'nom_scientifique' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:fruit,legume,cereale,legumineuse,autre'],
            'saison' => ['required', 'in:printemps,ete,automne,hiver,toute_annee'],
            'ph_sol_min' => ['nullable', 'numeric'],
            'ph_sol_max' => ['nullable', 'numeric'],
            'temp_min' => ['nullable', 'integer'],
            'temp_max' => ['nullable', 'integer'],
            'besoin_eau_cycle' => ['nullable', 'integer'],
            'soil_type' => ['nullable', 'in:argileux,sableux,limoneux,humifere'],
            'conseils' => ['nullable', 'string'],
        ]);

        Culture::create($validated);

        return redirect()->route('admin.cultures.index')->with('success', 'Culture créée');
    }

    public function show(Culture $culture)
    {
        $culture->load(['parcels.user', 'products']);

        return inertia('Admin/Cultures/Show', [
            'culture' => $culture,
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function edit(Culture $culture)
    {
        return inertia('Admin/Cultures/Edit', [
            'culture' => $culture,
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function update(Request $request, Culture $culture)
    {
        $validated = $request->validate([
            'nom_commun' => ['required', 'string', 'max:255'],
            'nom_scientifique' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:fruit,legume,cereale,legumineuse,autre'],
            'saison' => ['required', 'in:printemps,ete,automne,hiver,toute_annee'],
            'ph_sol_min' => ['nullable', 'numeric'],
            'ph_sol_max' => ['nullable', 'numeric'],
            'temp_min' => ['nullable', 'integer'],
            'temp_max' => ['nullable', 'integer'],
            'besoin_eau_cycle' => ['nullable', 'integer'],
            'soil_type' => ['nullable', 'in:argileux,sableux,limoneux,humifere'],
            'conseils' => ['nullable', 'string'],
        ]);

        $culture->update($validated);

        return redirect()->route('admin.cultures.index')->with('success', 'Culture mise à jour');
    }

    public function destroy(Culture $culture)
    {
        $culture->delete();
        return redirect()->route('admin.cultures.index')->with('success', 'Culture supprimée');
    }
}