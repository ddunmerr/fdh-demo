<?php

namespace App\Services\Cart;

use App\Contracts\CartStorageInterface;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Contracts\Auth\Guard;

class DatabaseCartStorage implements CartStorageInterface
{
    public function __construct(
        private Guard $auth,
    ) {}

    public function getItems(): array
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', $this->auth->id())
            ->get();

        $cart = [];

        foreach ($cartItems as $item) {
            $product = $item->product;
            if (!$product) continue;

            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'quantity' => $item->quantity,
                'image' => $product->image,
                'slug' => $product->slug,
                'stock' => $product->stock,
            ];
        }

        return $cart;
    }

    public function getCount(): int
    {
        return CartItem::where('user_id', $this->auth->id())->count();
    }

    public function add(Product $product, int $quantity): void
    {
        $cartItem = CartItem::where('user_id', $this->auth->id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'user_id' => $this->auth->id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        $cartItem = CartItem::where('user_id', $this->auth->id())
            ->where('product_id', $productId)
            ->first();

        if (!$cartItem) return;

        if ($quantity <= 0) {
            $cartItem->delete();
        } else {
            $cartItem->update(['quantity' => $quantity]);
        }
    }

    public function remove(int $productId): void
    {
        CartItem::where('user_id', $this->auth->id())
            ->where('product_id', $productId)
            ->delete();
    }

    public function clear(): void
    {
        CartItem::where('user_id', $this->auth->id())->delete();
    }

    public function isInCart(int $productId): bool
    {
        return CartItem::where('user_id', $this->auth->id())
            ->where('product_id', $productId)
            ->exists();
    }
}