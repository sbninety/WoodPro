<?php

namespace App\Services\Size;

use App\Repositories\Size\SizeRepository;

class SizeServiceEloquent implements SizeService
{
    public function __construct(protected SizeRepository $sizeRepository)
    {
    }

    public function getRepository(): SizeRepository
    {
        return $this->sizeRepository;
    }

    public function upsert(array $attributes)
    {
        return $this->sizeRepository->upsert($attributes, ['id'], ['product_id', 'length', 'width', 'height', 'price']);
    }
}
