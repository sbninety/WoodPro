<?php

namespace App\Services\OrderDetail;

use App\Repositories\OrderDetail\OrderDetailRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderDetailServiceEloquent implements OrderDetailService
{
    public function __construct(protected OrderDetailRepository $orderDetailRepository)
    {
    }

    public function getRepository(): OrderDetailRepository
    {
        return $this->orderDetailRepository;
    }

    public function getBestSellingProducts(int $month, int $year): Collection
    {
        return $this->orderDetailRepository
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('SUM(order_details.quantity) as total_quantity'))
            ->where('orders.status', '4')
            ->whereMonth('orders.created_at', $month)
            ->whereYear('orders.created_at', $year)
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
    }

    public function getLittleSellingProducts(int $month, int $year): Collection
    {
        return $this->orderDetailRepository
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->join('products', 'order_details.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('SUM(order_details.quantity) as total_quantity'))
            ->where('orders.status', '4')
            ->whereMonth('orders.created_at', $month)
            ->whereYear('orders.created_at', $year)
            ->having('total_quantity', '<=', 5)
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            // ->limit(15)
            ->get();
    }
}
