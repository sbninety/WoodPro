<?php

namespace App\Observers;

use App\Helper\ImageManagerTrait;
use App\Models\Product;
use App\Services\Image\ImageService;

class ProductObserver
{
    use ImageManagerTrait;

    public function __construct(protected ImageService $imageService)
    {
    }
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $colors = request()->colors;
        $materials = request()->materials;
        $sizes = request()->sizes;
        $images = request()->images;

        if ($images) {
            foreach ($images as $image) {
                $fileData = $this->uploads($image);
                $imageUpload = $this->imageService->storeImage($fileData);
                $product->productImages()->create([
                    'product_id' => $product->id,
                    'image_id' => $imageUpload->id
                ]);
            }
        }

        if ($colors) {
            foreach ($colors as $color) {
                $color['product_id'] = $product->id;
                $product->colors()->create($color);
            }
        }

        if ($materials) {
            foreach ($materials as $material) {
                $material['product_id'] = $product->id;
                $product->materials()->create($material);
            }
        }

        if ($sizes) {
            foreach ($sizes as $size) {
                $size['product_id'] = $product->id;
                $product->sizes()->create($size);
            }
        }
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
