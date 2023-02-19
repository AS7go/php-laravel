<?php

namespace App\Repositories\Contracts;

use App\Models\Order;

interface OrderRepositoryContract
{
    public function create(array $request, float $total): Order|bool;
}