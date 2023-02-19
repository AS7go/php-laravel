<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderStatus;

class OrderRepository implements Contracts\OrderRepositoryContract
{

    public function create(array $request): Order|bool
    {
        $user = auth()->user();
        $status = OrderStatus::default()->first();
//        dd($user, $status, $request);
        dd($user, $status, $request);
    }
}
