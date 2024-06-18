<?php

namespace App\Http\Resources\OrderDetail;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
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
            'product_id' => $this->resource->product_id,
            'product_name' => $this->resource->product->name,
            'product_image' => $this->resource->product->productImages[0]->image->path,
            'price' => $this->resource->price,
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
}
