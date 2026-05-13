<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $products = Product::withCount('parcels')
            ->orderBy('nom_commercial')
            ->paginate(15);

        return inertia('Admin/Products/Index', [
            'products' => $products,
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function create()
    {
        return inertia('Admin/Products/Create', [
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_commercial' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'composant_actif' => ['nullable', 'string', 'max:255'],
            'dosage_recommande' => ['nullable', 'string', 'max:255'],
            'delai_avant_recolte' => ['nullable', 'integer'],
            'type' => ['required', 'in:engrais,pesticide,fongicide,herbicide,biologique'],
            'avantages' => ['nullable', 'string'],
            'usage_method' => ['nullable', 'string'],
            'safety_instructions' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produit créé');
    }

    public function show(Product $product)
    {
        $product->load('cultures');

        return inertia('Admin/Products/Show', [
            'product' => $product,
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function edit(Product $product)
    {
        return inertia('Admin/Products/Edit', [
            'product' => $product,
            'auth' => ['user' => auth()->user()]
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'nom_commercial' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'composant_actif' => ['nullable', 'string', 'max:255'],
            'dosage_recommande' => ['nullable', 'string', 'max:255'],
            'delai_avant_recolte' => ['nullable', 'integer'],
            'type' => ['required', 'in:engrais,pesticide,fongicide,herbicide,biologique'],
            'avantages' => ['nullable', 'string'],
            'usage_method' => ['nullable', 'string'],
            'safety_instructions' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé');
    }
}