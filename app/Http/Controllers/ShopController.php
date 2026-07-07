<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController
{
    private const SORT_MAP = [
        'price_asc'  => ['column' => 'price', 'direction' => 'asc'],
        'price_desc' => ['column' => 'price', 'direction' => 'desc'],
        'name_asc'   => ['column' => 'name', 'direction' => 'asc'],
        'name_desc'  => ['column' => 'name', 'direction' => 'desc'],
        'oldest'     => ['column' => 'created_at', 'direction' => 'asc'],
        'newest'     => ['column' => 'created_at', 'direction' => 'desc'],
    ];

    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        // Фильтр по категории
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Поиск
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // СОРТИРОВКА
        $sort = $request->input('sort', 'newest');
        $sortRule = self::SORT_MAP[$sort] ?? self::SORT_MAP['newest'];
        $query->orderBy($sortRule['column'], $sortRule['direction']);

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('shop.index', compact('products', 'categories', 'sort'));
    }

    public function show(Product $product)
    {
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }
}
