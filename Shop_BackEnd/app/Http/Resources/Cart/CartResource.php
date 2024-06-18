<?php

namespace App\Http\Resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public $resource;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'user_id' => $this->resource->user_id,
            'product_id' => $this->resource->product_id,
            'product_name' => $this->resource->product->name,
            'product_quantity' => $this->resource->product->quantity,
            'product_image' => $this->resource->product->productImages[0]->image->path,
            'price' => $this->caculatePrice(),
            'color_id' => $this->resource->color_id,
            'color_name' => $this->resource->color->name,
            'material_id' => $this->resource->material_id,
            'material_name' => $this->resource->material->name,
            'size_id' => $this->resource->size_id,
            'length' => $this->resource->size->length,
            'width' => $this->resource->size->width,
            'height' => $this->resource->size->height,
            'quantity' => $this->resource->quantity
        ];
    }

    private function caculatePrice(): float
    {
        $product = $this->resource->product;
        $size = $this->resource->size;
        if ($product->discount && $size->price) {
            return $size->price - $size->price * ($product->discount / 100);
        } else if ($product->discount && !$size->price) {
            return $product->price - $product->price * ($product->discount / 100);
        } else if (!$product->discount && $size->price) {
            return $size->price;
        } else {
            return $product->price;
        }
    }
}
