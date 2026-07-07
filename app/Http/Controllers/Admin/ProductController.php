<?php

namespace App\Http\Controllers\Admin;

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

    public function store(Request $request)
    {
        try {
            // Убираем is_active из валидации, обработаем отдельно
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:products',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Ручная обработка is_active (checkbox)
            $validated['is_active'] = $request->has('is_active') ? true : false;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $validated['image'] = $this->imageService->upload($request->file('image'));
            }

            $product = Product::create($validated);

            return redirect()->route('admin.products.index')
                ->with('success', 'Товар "' . $product->name . '" успешно создан!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Ошибка: ' . $e->getMessage()]);
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Ручная обработка is_active
            $validated['is_active'] = $request->has('is_active') ? true : false;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $this->imageService->delete($product->image);
                $validated['image'] = $this->imageService->upload($request->file('image'));
            }

            $product->update($validated);

            return redirect()->route('admin.products.index')
                ->with('success', 'Товар "' . $product->name . '" успешно обновлен');

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Ошибка: ' . $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $this->imageService->delete($product->image);
            $product->delete();
            return redirect()->route('admin.products.index')
                ->with('success', 'Товар удален');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при удалении: ' . $e->getMessage()]);
        }
    }
}
