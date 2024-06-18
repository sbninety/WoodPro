<?php

namespace App\Services\Image;

use App\Models\Image;
use App\Repositories\Image\ImageRepository;

class ImageServiceEloquent implements ImageService
{
    public function __construct(protected ImageRepository $imageRepository)
    {
    }

    public function getRepository(): ImageRepository
    {
        return $this->imageRepository;
    }

    public function storeImage(array $attributes): ?Image
    {
        return $this->imageRepository->create($attributes);
    }
}
