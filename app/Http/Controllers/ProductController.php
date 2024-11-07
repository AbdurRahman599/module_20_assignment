<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query();

        $sortField = $request->get('sort', 'product_id');
        $sortDirection = $request->get('direction', 'asc');

        if (in_array($sortField, ['product_id', 'name', 'price']) && in_array($sortDirection, ['asc', 'desc'])) {
            $products->orderBy($sortField, $sortDirection);
        }

        if ($request->search) {
            $products->where('product_id', 'LIKE', "%{$request->search}%")
                ->orWhere('description', 'LIKE', "%{$request->search}%");
        }

        $products = $products->paginate(10);

        return view('products.index', compact('products', 'sortField', 'sortDirection'));
    }


    public function create()
    {
        return view('products.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|unique:products',
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Image validation
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $validated['product_id'] . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('images'), $fileName);

            $imagePath = 'images/' . $fileName;
        }

        Product::create($validated + [
                'description' => $request->description,
                'stock' => $request->stock,
                'image' => $imagePath,
            ]);

        return redirect()->route('products.index')->with('success', 'Product added successfully!');
    }


    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'product_id' => 'required|unique:products,product_id,' . $product->id,
            'name' => 'required',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $product->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $fileName = $validated['product_id'] . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('images'), $fileName);

            $imagePath = 'images/' . $fileName;
        }

        $product->update($validated + [
                'description' => $request->description,
                'stock' => $request->stock,
                'image' => $imagePath,
            ]);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }



    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}
