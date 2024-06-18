<?php

namespace App\Services\ProductImage;

use App\Repositories\ProductImage\ProductImageRepository;

class ProductImageServiceEloquent implements ProductImageService
{
    public function __construct(protected ProductImageRepository $productImageRepository)
    {
    }

    public function getRepository(): ProductImageRepository
    {
        return $this->productImageRepository;
    }

    public function store(array $attributes)
    {
        return $this->productImageRepository->create($attributes);
    }

    public function destroy(int $id)
    {
        return $this->productImageRepository->delete($id);
    }
}
