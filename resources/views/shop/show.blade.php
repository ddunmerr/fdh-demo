@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-6">
            <img src="{{ $product->image ?? 'https://placehold.co/600x600/e9ecef/495057?text=No+Image' }}"
                 class="img-fluid rounded" alt="{{ $product->name }}">
        </div>
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p class="text-muted">Категория: {{ $product->category->name }}</p>
            <p class="price display-6">{{ $product->formatted_price }}</p>

            <div class="mb-3">
                @if($product->inStock())
                    <span class="badge bg-success">В наличии ({{ $product->stock }} шт.)</span>
                @else
                    <span class="badge bg-danger">Нет в наличии</span>
                @endif
            </div>

            <p class="lead">{{ $product->description }}</p>

            @if($product->inStock())
                <form action="{{ route('cart.add', $product) }}" method="POST">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-3">
                            <label class="form-label">Кол-во</label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->stock }}">
                        </div>
                        <div class="col-9">
                            <button type="submit" class="btn btn-primary btn-lg w-100 btn-defaultП">
                                <i class="bi bi-cart-plus "></i> В корзину
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <button class="btn btn-secondary btn-lg w-100" disabled>Нет в наличии</button>
            @endif

            <a href="{{ route('home') }}" class="btn btn-link mt-3">
                <i class="bi bi-arrow-left"></i> Назад к покупкам
            </a>
        </div>
    </div>

    @if($relatedProducts->count())
        <hr class="my-5">
        <h3>Похожие товары</h3>
        <div class="row">
            @foreach($relatedProducts as $related)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card product-card">
                        <img src="{{ $related->image ?? 'https://placehold.co/600x400/e9ecef/495057?text=No+Image' }}"
                             class="card-img-top product-img" alt="{{ $related->name }}">
                        <div class="card-body">
                            <h6>{{ $related->name }}</h6>
                            <p class="price">{{ $related->formatted_price }}</p>
                            <a href="{{ route('product.show', $related) }}" class="btn btn-outline-primary btn-sm w-100">Смотреть</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
