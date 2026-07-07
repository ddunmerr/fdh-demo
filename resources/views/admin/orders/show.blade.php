@extends('layouts.app')

@section('title', 'Заказ #' . $order->id)

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Заказ #{{ $order->id }}</h1>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Назад
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Информация о заказе</h5>
                </div>
                <div class="card-body">
                    <p><strong>Статус:</strong> 
                        <span class="badge bg-{{ [
                            'pending' => 'warning',
                            'paid' => 'info',
                            'shipped' => 'primary',
                            'delivered' => 'success',
                            'cancelled' => 'danger',
                        ][$order->status] ?? 'secondary' }}">
                            {{ $order->status }}
                        </span>
                    </p>
                    <p><strong>Сумма:</strong> {{ $order->total_formatted }}</p>
                    <p><strong>Дата:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                    <p><strong>Покупатель:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Email:</strong> {{ $order->customer_email }}</p>
                    <p><strong>Телефон:</strong> {{ $order->customer_phone }}</p>
                    <p><strong>Адрес доставки:</strong><br>{{ $order->shipping_address }}</p>
                    @if($order->comment)
                        <p><strong>Комментарий:</strong><br>{{ $order->comment }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Изменить статус</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <select name="status" class="form-select">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Ожидает</option>
                                <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>Оплачен</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Отправлен</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Доставлен</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Отменен</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Обновить статус
                        </button>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Товары в заказе</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th>Кол-во</th>
                                    <th>Цена</th>
                                    <th>Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->price, 2, '.', ' ') }} ₽</td>
                                        <td>{{ number_format($item->price * $item->quantity, 2, '.', ' ') }} ₽</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
