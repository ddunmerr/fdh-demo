<?php

namespace App\Contracts;

interface CheckoutServiceInterface
{
    public function process(array $validated, array $cart): void;
}