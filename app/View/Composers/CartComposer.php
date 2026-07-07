<?php

namespace App\View\Composers;

use App\Contracts\CartServiceInterface;
use Illuminate\View\View;

class CartComposer
{
    public function __construct(
        private CartServiceInterface $cartService
    ) {}

    public function compose(View $view): void
    {
        $view->with('cartCount', $this->cartService->getCartCount());
    }
}