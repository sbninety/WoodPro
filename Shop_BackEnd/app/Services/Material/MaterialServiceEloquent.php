<?php

namespace App\Services\Material;

use App\Repositories\Material\MaterialRepository;

class MaterialServiceEloquent implements MaterialService
{
    public function __construct(protected MaterialRepository $materialRepository)
    {
    }

    public function getRepository(): MaterialRepository
    {
        return $this->materialRepository;
    }

    public function upsert(array $attributes)
    {
        return $this->materialRepository->upsert($attributes, ['id'], ['product_id', 'name']);
    }
}
