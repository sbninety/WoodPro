<?php

namespace App\Services\Color;

use App\Repositories\Color\ColorRepository;

class ColorServiceEloquent implements ColorService
{
    public function __construct(protected ColorRepository $colorRepository)
    {
    }

    public function getRepository(): ColorRepository
    {
        return $this->colorRepository;
    }

    public function upsert(array $attributes)
    {
        return $this->colorRepository->upsert($attributes, ['id'], ['product_id', 'name']);
    }
}
