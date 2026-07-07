<?php

namespace App\Services\Cart;

use App\Contracts\CartStorageInterface;
use App\Models\Product;
use Illuminate\Session\Store as Session;

class SessionCartStorage implements CartStorageInterface
{
    private const SESSION_KEY = 'cart';

    public function __construct(
        private Session $session,
    ) {}

    public function getItems(): array
    {
        return $this->session->get(self::SESSION_KEY, []);
    }

    public function getCount(): int
    {
        return count($this->session->get(self::SESSION_KEY, []));
    }

    public function add(Product $product, int $quantity): void
    {
        $cart = $this->session->get(self::SESSION_KEY, []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $quantity,
                'image' => $product->image,
                'slug' => $product->slug,
                'stock' => $product->stock,
            ];
        }

        $this->session->put(self::SESSION_KEY, $cart);
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $cart = $this->session->get(self::SESSION_KEY, []);

        if (!isset($cart[$productId])) return;

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $quantity;
        }

        $this->session->put(self::SESSION_KEY, $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->session->get(self::SESSION_KEY, []);
        unset($cart[$productId]);
        $this->session->put(self::SESSION_KEY, $cart);
    }

    public function clear(): void
    {
        $this->session->forget(self::SESSION_KEY);
    }

    public function isInCart(int $productId): bool
    {
        $cart = $this->session->get(self::SESSION_KEY, []);
        return isset($cart[$productId]);
    }
}