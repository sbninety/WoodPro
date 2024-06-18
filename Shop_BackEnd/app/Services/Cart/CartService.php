<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Repositories\Cart\CartRepository;
use Illuminate\Support\Collection;

interface CartService
{
    public function getRepository(): CartRepository;

    public function getCartByUserId(int $id): Collection;

    public function storeCart(array $attributes): Cart;

    public function updateCart(int $id, array $attributes): mixed;

    public function deleteCart(int $id): bool;
}
