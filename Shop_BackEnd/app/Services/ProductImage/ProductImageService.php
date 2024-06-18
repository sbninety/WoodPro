<?php

namespace App\Services\ProductImage;

use App\Repositories\ProductImage\ProductImageRepository;

interface ProductImageService
{
    public function getRepository(): ProductImageRepository;

    public function store(array $attributes);

    public function destroy(int $id);
}
