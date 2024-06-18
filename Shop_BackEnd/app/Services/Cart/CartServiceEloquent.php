<?php

namespace App\Services\Cart;

use App\Models\Cart;
use App\Repositories\Cart\CartRepository;
use Illuminate\Support\Collection;

class CartServiceEloquent implements CartService
{
    public function __construct(protected CartRepository $cartRepository)
    {
    }

    public function getRepository(): CartRepository
    {
        return $this->cartRepository;
    }

    public function getCartByUserId(int $id): Collection
    {
        return $this->cartRepository
            ->where('user_id', $id)
            ->get();
    }

    public function storeCart(array $attributes): Cart
    {
        return $this->cartRepository->create($attributes);
    }

    public function updateCart(int $id, array $attributes): mixed
    {
        return $this->cartRepository->update($attributes, $id);
    }

    public function deleteCart(int $id): bool
    {
        return $this->cartRepository->delete($id);
    }
}
