<?php

namespace App\Contracts;

use App\Models\Product;

interface CartStorageInterface
{
    public function getItems(): array;
    public function getCount(): int;
    public function add(Product $product, int $quantity): void;
    public function updateQuantity(int $productId, int $quantity): void;
    public function remove(int $productId): void;
    public function clear(): void;
    public function isInCart(int $productId): bool;
}