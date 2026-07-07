@extends('layouts.app')

@section('title', isset($category) ? $category->name : 'Главная')

@section('content')
<div class="container py-4">
    <!-- Hero -->
    <div class="bg-primary text-white rounded-3 p-5 mb-4 hero">
        <h1 class="display-4">{{ isset($category) ? $category->name : '★ SWAGG 2K26 ★' }}</h1>
        <p class="lead">{{ isset($category) ? $category->description : 'fkdahype fweago swag' }}</p>
        @if(request('category') || request('search') || request('sort'))
            <a href="{{ route('home') }}" class="btn btn-light btn-lg">Сбросить фильтры</a>
        @endif
    </div>

    <!-- Категории -->
    <div class="row mb-4">
        <div class="col-md-2 col-6 mb-2">
            <a href="{{ route('home') }}" class="category-link {{ !request('category') ? 'active' : '' }}">
                <div class="card text-center h-100">
                    <div class="card-body">
                        <i class="bi bi-grid fs-1"></i>
                        <h6 class="card-title">Все</h6>
                    </div>
                </div>
            </a>
        </div>
        @foreach($categories as $cat)
            <div class="col-md-2 col-6 mb-2">
                <a href="{{ route('home', ['category' => $cat->id]) }}" 
                   class="category-link {{ request('category') == $cat->id ? 'active' : '' }}">
                    <div class="card text-center h-100">
                        <div class="card-body">
                            <i class="bi bi-tag fs-1"></i>
                            <h6 class="card-title">{{ $cat->name }}</h6>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <!-- Сортировка -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <span class="text-muted">Найдено: {{ $products->total() }} товаров</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <label for="sort" class="text-muted mb-0">Сортировка:</label>
            <select id="sort" name="sort" class="form-select form-select-sm" style="width: auto;" onchange="window.location.href=this.value">
                <option value="{{ route('home', array_merge(request()->query(), ['sort' => 'newest'])) }}" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>
                    Новинки
                </option>
                <option value="{{ route('home', array_merge(request()->query(), ['sort' => 'oldest'])) }}" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                    Сначала старые
                </option>
                <option value="{{ route('home', array_merge(request()->query(), ['sort' => 'price_asc'])) }}" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                    Цена: по возрастанию
                </option>
                <option value="{{ route('home', array_merge(request()->query(), ['sort' => 'price_desc'])) }}" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                    Цена: по убыванию
                </option>
                <option value="{{ route('home', array_merge(request()->query(), ['sort' => 'name_asc'])) }}" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>
                    Название: А-Я
                </option>
                <option value="{{ route('home', array_merge(request()->query(), ['sort' => 'name_desc'])) }}" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>
                    Название: Я-А
                </option>
            </select>
        </div>
    </div>

    <!-- Товары -->
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card product-card">
                    <img src="{{ $product->image ?? 'https://placehold.co/600x400/e9ecef/495057?text=No+Image' }}"
                         class="card-img-top product-img" alt="{{ $product->name }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text text-muted small">{{ $product->category->name }}</p>
                        <p class="price mt-auto">{{ $product->formatted_price }}</p>
                        <a href="{{ route('product.show', $product) }}" class="btn btn-primary w-100 btn-default">
                            Подробнее
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-box-seam display-1 text-muted"></i>
                <h3>Товаров не найдено</h3>
                <p class="text-muted">Попробуйте изменить фильтры или поиск</p>
                <a href="{{ route('home') }}" class="btn btn-primary">Сбросить фильтры</a>
            </div>
        @endforelse
    </div>

    {{ $products->appends(request()->query())->links() }}
</div>
@endsection
