<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $regions = Region::withCount('users')->orderBy('nom')->paginate(15);
        return inertia('Admin/Regions/Index', [
            'regions' => $regions,
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function create()
    {
        return inertia('Admin/Regions/Create', [
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255', 'unique:regions,nom'],
            'code' => ['required', 'string', 'max:10', 'unique:regions,code'],
            'pays' => ['required', 'string', 'max:255'],
        ]);

        Region::create($validated);
        return redirect()->route('admin.regions.index')->with('success', 'Région créée');
    }

    public function edit(Region $region)
    {
        return inertia('Admin/Regions/Edit', [
            'region' => $region,
            'auth' => ['user' => auth()->user()]
        ]);
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
        $region->delete();
        return redirect()->route('admin.regions.index')->with('success', 'Région supprimée');
    }
}