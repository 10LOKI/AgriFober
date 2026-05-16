<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $regions = Region::withCount('users')->orderBy('nom')->paginate(15);
        return view('admin.regions.index', compact('regions'));
    }

    public function create()
    {
        $region = new Region();
        return view('admin.regions.edit', compact('region'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:regions,nom'],
            'code' => ['required', 'string', 'max:10', 'unique:regions,code'],
            'pays' => ['required', 'string', 'max:255'],
        ]);

        Region::create($validated);

        return redirect()->route('admin.regions.index')->with('success', 'Région créée avec succès');
    }

    public function edit(Region $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Region $region)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:regions,nom,'.$region->id],
            'code' => ['required', 'string', 'max:10', 'unique:regions,code,'.$region->id],
            'pays' => ['required', 'string', 'max:255'],
        ]);

        $region->update($validated);

        return redirect()->route('admin.regions.index')->with('success', 'Région mise à jour');
    }

    public function destroy(Region $region)
    {
        // Vérifier si la région a des utilisateurs
        if ($region->users()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une région liée à des agriculteurs');
        }
        $region->delete();
        return redirect()->route('admin.regions.index')->with('success', 'Région supprimée');
    }
}
