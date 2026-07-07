<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Http\Request;

class ProductController
{
    public function __construct(
        private ImageService $imageService,
    ) {}

    public function index()
    {
        $products = Product::with('category')->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $validated['image'] = $this->imageService->upload($request->file('image'));
        }

        $product = Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар "' . $product->name . '" успешно создан!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $this->imageService->delete($product->image);
            $validated['image'] = $this->imageService->upload($request->file('image'));
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар "' . $product->name . '" успешно обновлен');
    }

    public function destroy(Product $product)
    {
        $this->imageService->delete($product->image);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Товар удален');
    }
}