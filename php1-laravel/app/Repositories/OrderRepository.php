<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderStatus;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderRepository implements Contracts\OrderRepositoryContract
{

    public function create(array $request): Order|bool
    {
        $status = OrderStatus::default()->first();
        $request = array_merge($request, ['status_id' => $status->id]);

        return auth()->user()->orders()->create($request);
    }

    protected function addProductsToOrder(Order $order)
    {
        Cart::instance('cart')->content()->groupBy('id')->each(function ($item) use ($order) {
            $cartItem = $item->first();
            $order->products()->attach($cartItem->model, [
                'quantity' => $cartItem->qty,
                'single_price' => $cartItem->price
            ]);

            $quantity = $cartItem->model->quantity - $cartItem->qty;

            if (!$cartItem->model->update(compact('quantity'))) {
                throw new \Exception("Smth went wrong with product (id: {$cartItem->model->id}) quantity update");
            }
        });
    }
}
