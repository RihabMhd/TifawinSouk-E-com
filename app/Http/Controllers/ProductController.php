<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource (PUBLIC - no auth required).
     */
    public function index()
    {
        $products = Product::with(['user', 'category'])
            ->latest()
            ->paginate(12);
            
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource (ADMIN only).
     */
    public function create()
    {
        $categories = Category::orderBy('title')->get();
        
        if ($categories->isEmpty()) {
            return redirect()->route('categories.create')
                ->with('error', 'Please create a category first before adding products.');
        }
        
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage (ADMIN only).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['user_id'] = auth()->id();

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully!');
    }

    /**
     * Display the specified resource (PUBLIC - no auth required).
     */
    public function show(string $id)
    {
        $product = Product::with(['user', 'category'])->findOrFail($id);
        
        // Get related products from the same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
            
        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Show the form for editing the specified resource (ADMIN only).
     */
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('title')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage (ADMIN only).
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'price' => 'required|numeric|min:0|max:999999.99',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'category_id' => 'required|exists:categories,id'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage (ADMIN only).
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        
        // Delete associated image
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }
}