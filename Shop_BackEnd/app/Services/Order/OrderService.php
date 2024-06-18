<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Repositories\Order\OrderRepository;
use Illuminate\Support\Collection;

interface OrderService
{
    public function getRepository(): OrderRepository;

    public function storeOrder(array $attributes);

    public function countOrderToday();

    public function getOrderByUser(int $id): Collection;

    public function updateOrder(array $attributes, int $id);

    public function revenueByMonth();

    public function countOrder();

    public function search(array $attributes): Collection;

    public function getOrderById(int $id): Order;
}
