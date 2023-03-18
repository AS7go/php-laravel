<?php

namespace App\Broadcasting;

use App\Models\Product;
use App\Models\User;

class WishListProductUpdate
{
    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Authenticate the user's access to the channel.
     *
     * @param \App\Models\User $user
     * @return array|bool
     */
    public function join(User $user, Product|int $productId)
    {
        logs()->info(self::class);
        return $user->isWishedProduct($productId);
    }
}
