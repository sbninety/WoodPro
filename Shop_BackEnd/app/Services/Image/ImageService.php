<?php

namespace App\Services\Image;

use App\Models\Image;
use App\Repositories\Image\ImageRepository;

interface ImageService
{
    public function getRepository(): ImageRepository;

    public function storeImage(array $attributes): ?Image;
}
