<?php

namespace App\Services\OrderDetail;

use App\Repositories\OrderDetail\OrderDetailRepository;
use Illuminate\Support\Collection;

interface OrderDetailService
{
    public function getRepository(): OrderDetailRepository;

    public function getBestSellingProducts(int $month, int $year): Collection;

    public function getLittleSellingProducts(int $month, int $year): Collection;
}
