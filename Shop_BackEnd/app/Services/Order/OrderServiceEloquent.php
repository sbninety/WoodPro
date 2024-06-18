<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Repositories\Order\OrderRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderServiceEloquent implements OrderService
{
    public function __construct(protected OrderRepository $orderRepository)
    {
    }

    public function getRepository(): OrderRepository
    {
        return $this->orderRepository;
    }

    public function storeOrder(array $attributes)
    {
        return $this->orderRepository->create($attributes);
    }

    public function countOrderToday()
    {
        return $this->orderRepository
            ->whereDate('created_at', now()->toDateString())
            ->count();
    }

    public function getOrderByUser(int $id): Collection
    {
        return $this->orderRepository
            ->where('user_id', $id)
            ->orderByDesc('order_id')
            ->get();
    }

    public function updateOrder(array $attributes, int $id)
    {
        return $this->orderRepository->update($attributes, $id);
    }

    public function revenueByMonth()
    {
        return $this->orderRepository
            ->select(DB::raw('MONTH(created_at) as month'), DB::raw('ROUND(SUM(total)) as total'))
            ->whereYear('created_at', '=', 2024)
            ->where('status', 4)
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->get();
    }

    public function countOrder()
    {
        return $this->orderRepository->all()->count();
    }

    public function search(array $attributes): Collection
    {
        if (!$attributes['status']) {
            return $this->orderRepository
                ->whereDate('created_at', $attributes['date'])
                ->orderByDesc('order_id')
                ->get();
        } else {
            return $this->orderRepository
                ->whereDate('created_at', $attributes['date'])
                ->where('status', $attributes['status'])
                ->orderByDesc('order_id')
                ->get();
        }
    }

    public function getOrderById(int $id): Order
    {
        return $this->orderRepository->find($id);
    }
}
