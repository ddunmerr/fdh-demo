<?php

namespace App\Services\Cart;

use App\Contracts\CartStorageInterface;
use App\Models\Product;
use Illuminate\Contracts\Auth\Guard;

class CartStorageManager implements CartStorageInterface
{
    public function __construct(
        private DatabaseCartStorage $databaseStorage,
        private SessionCartStorage $sessionStorage,
        private Guard $auth,
    ) {}

    private function storage(): CartStorageInterface
    {
        return $this->auth->check()
            ? $this->databaseStorage
            : $this->sessionStorage;
    }

    public function getItems(): array
    {
        return $this->storage()->getItems();
    }

    public function getCount(): int
    {
        return $this->storage()->getCount();
    }

    public function add(Product $product, int $quantity): void
    {
        $this->storage()->add($product, $quantity);
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $this->storage()->updateQuantity($productId, $quantity);
    }

    public function remove(int $productId): void
    {
        $this->storage()->remove($productId);
    }

    public function clear(): void
    {
        $this->storage()->clear();
    }

    public function isInCart(int $productId): bool
    {
        return $this->storage()->isInCart($productId);
    }
}