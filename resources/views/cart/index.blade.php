@extends('layouts.app')

@section('title', 'Корзина')

@section('content')
<div class="container py-4">
    <h1><i class="bi bi-cart"></i> Корзина</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(empty($cart))
        <div class="text-center py-5">
            <i class="bi bi-cart-x fs-1"></i>
            <h3>Корзина пуста</h3>
            <a href="{{ route('home') }}" class="btn btn-primary btn-default">Перейти к покупкам</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Товар</th>
                        <th>Цена</th>
                        <th>Кол-во</th>
                        <th>Сумма</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $id => $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $item['image'] ?? 'https://placehold.co/60x60/e9ecef/495057?text=No+Image' }}"
                                         class="rounded me-3" width="60" height="60" style="object-fit: cover;">
                                    <a href="{{ route('product.show', $item['slug']) }}" class="text-decoration-none">
                                        {{ $item['name'] }}
                                    </a>
                                </div>
                            </td>
                            <td>{{ number_format($item['price'], 2, '.', ' ') }} ₽</td>
                            <td>
                                <form action="{{ route('cart.update', $item['id']) }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                           min="1" max="{{ $item['stock'] }}" class="form-control" style="width: 80px;">
                                    <button type="submit" class="btn btn-outline-primary btn-sm">Обновить</button>
                                </form>
                            </td>
                            <td class="fw-bold">{{ number_format($item['price'] * $item['quantity'], 2, '.', ' ') }} ₽</td>
                            <td>
                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="table-active">
                        <td colspan="3" class="text-end"><strong>Итого:</strong></td>
                        <td><strong>{{ number_format($total, 2, '.', ' ') }} ₽</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-between gap-2">
            <form action="{{ route('cart.clear') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Очистить корзину?')">
                    <i class="bi bi-trash"></i> Очистить
                </button>
            </form>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Продолжить покупки
            </a>
            <a href="{{ route('checkout') }}" class="btn btn-success">
                <i class="bi bi-credit-card"></i> Оформить заказ
            </a>
        </div>
    @endif
</div>
@endsection
