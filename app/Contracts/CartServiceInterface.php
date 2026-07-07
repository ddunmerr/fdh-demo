<?php

namespace App\Contracts;

use App\Models\Product;

interface CartServiceInterface
{
    public function getCartItems(): array;
    public function getTotal(): float;
    public function getCartCount(): int;
    public function addProduct(Product $product, int $quantity): void;
    public function updateQuantity(int $productId, int $quantity): void;
    public function removeItem(int $productId): void;
    public function clear(): void;
    public function isInCart(int $productId): bool;
    public function getCurrentQuantityInCart(int $productId): int;
    public function canAddToCart(Product $product, int $quantity): bool;
}
