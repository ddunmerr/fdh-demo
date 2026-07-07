<?php

namespace App\Services;

use App\Contracts\CheckoutServiceInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\ConnectionInterface;

class CheckoutService implements CheckoutServiceInterface
{
    public function __construct(
        private Guard $auth,
        private ConnectionInterface $db,
    ) {}

    public function process(array $validated, array $cart): void
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $this->db->transaction(function () use ($validated, $cart, $total) {
            $order = Order::create([
                'user_id' => $this->auth->id(),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'comment' => $validated['comment'] ?? null,
                'total' => $total,
                'status' => 'pending',
            ]);

            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                if (!$product) continue;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $product->decrement('stock', $item['quantity']);
            }
        });
    }
}