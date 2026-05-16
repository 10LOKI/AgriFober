<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Product::query();
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nom_commercial', 'like', "%$search%");
        }
        
        $products = $query->orderBy('nom_commercial')->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $product = new Product();
        return view('admin.products.edit', compact('product'));
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

        return redirect()->route('admin.products.index')->with('success', 'Produit créé avec succès');
    }

    public function show(Product $product)
    {
        $product->load('cultures');
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
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
            // Supprimer l'ancienne image si existe
            if ($product->image) {
                \Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produit mis à jour');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            \Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produit supprimé');
    }
}
