<?php

namespace App\Http\Resources\Size;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SizeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'product_id' => $this->resource->product->id,
            'length' => $this->resource->length,
            'width' => $this->resource->width,
            'height' => $this->resource->height,
            'price' => $this->resource->price
        ];
    }
}
