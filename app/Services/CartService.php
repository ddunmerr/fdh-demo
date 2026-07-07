<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getCartItems(): array
    {
        if (Auth::check()) {
            return $this->getDbCartItems();
        }
        return $this->getSessionCartItems();
    }

    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getCartItems() as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public function getCartCount(): int
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())->count();
        }
        return count(session()->get('cart', []));
    }

    public function addProduct(Product $product, int $quantity): void
    {
        if (Auth::check()) {
            $this->addDb($product, $quantity);
        } else {
            $this->addSession($product, $quantity);
        }
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        if (Auth::check()) {
            $this->updateDb($productId, $quantity);
        } else {
            $this->updateSession($productId, $quantity);
        }
    }

    public function removeItem(int $productId): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->where('product_id', $productId)->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }
    }

    public function clear(): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }
    }

    public function isInCart(int $productId): bool
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())->where('product_id', $productId)->exists();
        }
        return isset(session()->get('cart', [])[$productId]);
    }

    private function getDbCartItems(): array
    {
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
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

    private function getSessionCartItems(): array
    {
        return session()->get('cart', []);
    }

    private function addDb(Product $product, int $quantity): void
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
        }
    }

    private function addSession(Product $product, int $quantity): void
    {
        $cart = session()->get('cart', []);

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

        session()->put('cart', $cart);
    }

    private function updateDb(int $productId, int $quantity): void
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if (!$cartItem) return;

        if ($quantity <= 0) {
            $cartItem->delete();
        } else {
            $cartItem->update(['quantity' => $quantity]);
        }
    }

    private function updateSession(int $productId, int $quantity): void
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$productId])) return;

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId]['quantity'] = $quantity;
        }

        session()->put('cart', $cart);
    }
}