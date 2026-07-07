@extends('layouts.app')

@section('title', 'Заказ оформлен')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <div class="display-1 text-success">
            <i class="bi bi-check-circle-fill"></i>
        </div>
        <h1 class="display-4">Заказ оформлен!</h1>
        <p class="lead">Номер заказа: <strong>#{{ $order->id }}</strong></p>
        <p>Мы отправим подтверждение на ваш email: <strong>{{ $order->customer_email }}</strong></p>
        
        <div class="card mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <h5>Детали заказа</h5>
                <p><strong>Сумма:</strong> {{ $order->total_formatted }}</p>
                <p><strong>Статус:</strong> <span class="badge bg-warning">{{ $order->status }}</span></p>
                <p><strong>Адрес доставки:</strong><br>{{ $order->shipping_address }}</p>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="bi bi-house"></i> На главную
            </a>
        </div>
    </div>
</div>
@endsection
