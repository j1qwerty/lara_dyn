<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::latest()->paginate(12);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
                    'name' => 'required|string',
                    'price' => 'required|numeric',
                    'description' => 'nullable|string',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                ]);

                $path = null;
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = now()->format('YmdHis') . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
                    // $path = $image->storeAs('uploads', $filename, 'public');
                    $path = $image->move(('uploads'), $filename);
                }

                Products::create([
                    'name' => $request->name,
                    'price' => $request->price,
                    'description' => $request->description,
                    'image' => $path,
                ]);

                return redirect()->route('products.index')->with('success', 'Product added');
    }

    /**
     * Display the specified resource.
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Products $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Products $product)
    {
        $request->validate([
                    'name' => 'required|string',
                    'price' => 'required|numeric',
                    'description' => 'nullable|string',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                ]);

                $path = $product->image;
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $filename = now()->format('YmdHis') . '_' . Str::random(5) . '.' . $image->getClientOriginalExtension();
                    $path = $image->move(('uploads'), $filename);
                }

                $product->update([
                    'name' => $request->name,
                    'price' => $request->price,
                    'description' => $request->description,
                    'image' => $path,
                ]);

                return redirect()->route('products.index')->with('success', 'Product updated');
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Products $product)
    {
        // Delete image file if it exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        // Delete the product record from DB
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted along with image');
    }

}
