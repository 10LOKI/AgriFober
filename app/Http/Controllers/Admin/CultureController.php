<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Culture;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CultureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Culture::withCount('parcels', 'products');
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_commun', 'like', "%$search%")
                  ->orWhere('nom_scientifique', 'like', "%$search%");
            });
        }
        
        $cultures = $query->orderBy('nom_commun')->paginate(15)->withQueryString();
        return Inertia::render('Admin/Cultures/index', compact('cultures'));
    }

    public function create()
    {
        return Inertia::render('Admin/Cultures/Form', ['culture' => null]);
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
            'region' => ['nullable', 'string', 'max:255'],
            'conseils' => ['nullable', 'string'],
        ]);

        Culture::create($validated);

        return redirect()->route('admin.cultures.index')->with('success', 'Culture créée avec succès');
    }

    public function show(Culture $culture)
    {
        $culture->load(['parcels.user', 'products']);
        return Inertia::render('Admin/Cultures/Show', compact('culture'));
    }

    public function edit(Culture $culture)
    {
        return Inertia::render('Admin/Cultures/Form', compact('culture'));
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
            'region' => ['nullable', 'string', 'max:255'],
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
