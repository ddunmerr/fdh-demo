<?php

namespace App\Http\Controllers;

use App\Contracts\CartServiceInterface;
use App\Contracts\CheckoutServiceInterface;
use App\Http\Requests\CheckoutRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController
{
    public function __construct(
        private CartServiceInterface $cartService,
        private CheckoutServiceInterface $checkoutService,
    ) {}

    public function index()
    {
        return view('cart.index', [
            'cart' => $this->cartService->getCartItems(),
            'total' => $this->cartService->getTotal(),
        ]);
    }

    public function add(Request $request, Product $product)
    {
        $quantity = $request->input('quantity', 1);

        if (!$this->cartService->canAddToCart($product, $quantity)) {
            return back()->with('error', 'Недостаточно товара на складе');
        }

        $this->cartService->addProduct($product, $quantity);

        return redirect()->route('cart.index')->with('success', 'Товар добавлен в корзину');
    }

    public function update(Request $request, $productId)
    {
        $quantity = $request->input('quantity', 1);

        if (!$this->cartService->isInCart($productId)) {
            return back()->with('error', 'Товар не найден в корзине');
        }

        $this->cartService->updateQuantity($productId, $quantity);

        return redirect()->route('cart.index')->with('success', 'Корзина обновлена');
    }

    public function remove($productId)
    {
        $this->cartService->removeItem($productId);

        return redirect()->route('cart.index')
            ->with('success', 'Товар удален из корзины');
    }

    public function clear()
    {
        $this->cartService->clear();

        return redirect()->route('cart.index')
            ->with('success', 'Корзина очищена');
    }

    public function checkout()
    {
        $cart = $this->cartService->getCartItems();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        return view('cart.checkout', [
            'cart' => $cart,
            'total' => $this->cartService->getTotal(),
        ]);
    }

    public function processCheckout(CheckoutRequest $request)
    {
        $cart = $this->cartService->getCartItems();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Корзина пуста');
        }

        $this->checkoutService->process($request->validated(), $cart);
        $this->cartService->clear();

        return redirect()->route('home')
            ->with('success', 'Заказ оформлен! Спасибо за покупку.');
    }
}