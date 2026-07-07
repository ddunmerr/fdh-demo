<?php

namespace App\Services;

use App\Contracts\CartServiceInterface;
use App\Contracts\CartStorageInterface;
use App\Models\Product;
use Illuminate\Contracts\Auth\Guard;

class CartService implements CartServiceInterface
{
    public function __construct(
        private CartStorageInterface $storage,
        private Guard $auth,
    ) {}

    public function getCartItems(): array
    {
        return $this->storage->getItems();
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->storage->getItems() as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function getCartCount(): int
    {
        return $this->storage->getCount();
    }

    public function addProduct(Product $product, int $quantity): void
    {
        $this->storage->add($product, $quantity);
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $this->storage->updateQuantity($productId, $quantity);
    }

    public function removeItem(int $productId): void
    {
        $this->storage->remove($productId);
    }

    public function clear(): void
    {
        $this->storage->clear();
    }

    public function isInCart(int $productId): bool
    {
        return $this->storage->isInCart($productId);
    }

    public function getCurrentQuantityInCart(int $productId): int
    {
        $items = $this->storage->getItems();
        return $items[$productId]['quantity'] ?? 0;
    }

    public function canAddToCart(Product $product, int $quantity): bool
    {
        $currentQuantity = $this->getCurrentQuantityInCart($product->id);
        return ($currentQuantity + $quantity) <= $product->stock;
    }
}