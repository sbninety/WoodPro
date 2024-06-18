<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Color\ColorResource;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Image\ImageResource;
use App\Http\Resources\Material\MaterialResource;
use App\Http\Resources\Size\SizeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'slug' => $this->resource->slug,
            'category_id' => $this->resource->category->id,
            'category_name' => $this->resource->category->name,
            'name' => $this->resource->name,
            'price' => $this->resource->price,
            'discount' => $this->resource->discount,
            'quantity' => $this->resource->quantity,
            'published' => $this->resource->published,
            'description' => $this->resource->description,
            'colors' => ColorResource::collection($this->resource->colors),
            'materials' => MaterialResource::collection($this->resource->materials),
            'sizes' => SizeResource::collection($this->resource->sizes),
            'images' => ImageResource::collection($this->resource->productImages),
            'comments' => CommentResource::collection($this->resource->comments)
        ];
    }
}
