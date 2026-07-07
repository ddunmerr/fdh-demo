<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Магазин одежды')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body { background-color: #f8f9fa; min-height: 100vh; display: flex; flex-direction: column; }
        main { flex: 1; }
        .hero {background-color: #e85c8b !important;}
        .navbar-brand { font-weight: 700; font-size: 1.5rem; }
        .product-card { transition: transform 0.2s; height: 100%; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .product-img { height: 250px; object-fit: cover; background-color: #e9ecef; }
        .cart-badge { font-size: 0.7rem; margin-left: -8px; margin-top: -8px; }
        .price { font-size: 1.3rem; font-weight: 700; color: #e85c8b; }
        .footer { margin-top: 50px; padding: 20px 0; background-color: #212529; color: white; background-image: url('/storage/logo-2.jpg'); background-repeat: repeat-x; background-size:contain; }
        .pagination { max-width: 500px; margin: 0 auto; flex-wrap: wrap; }
        .category-link { text-decoration: none; color: inherit; }
        .category-link:hover { color: #7ca8c1; }
        .category-link.active .card { border-color: #e85c8b !important; border-width: 2px; }
        .btn-default {background-color: #e85c8b; border-color: transparent;}
        .btn-default:hover{background-color: #f9588c;}
        
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ route('home') }}">
            <i class="bi bi-bag"></i> SOMESHIT.RU
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
            </ul>

            <form action="{{ route('home') }}" method="GET" class="d-flex me-2">
                <input type="text" name="search" class="form-control form-control-sm me-1"
                       placeholder="Поиск..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-search"></i>
                </button>
            </form>

            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('cart.index') }}" class="btn btn-outline-light position-relative">
                    <i class="bi bi-cart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                        {{ $cartCount }}
                    </span>
                </a>

                @auth
                    <span class="text-light me-2">
                        <i class="bi bi-person"></i> {{ Auth::user()->name }}
                    </span>
                    @if(Auth::user()->is_admin ?? false)
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-warning">
                            <i class="bi bi-gear"></i> Товары
                        </a>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-info">
                            <i class="bi bi-box"></i> Заказы
                        </a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-box-arrow-right"></i> Выйти
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light">Вход</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Регистрация</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<main>
    @yield('content')
</main>

<footer class="footer text-center">
    <div class="container">
        <p class="mb-0"></p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
