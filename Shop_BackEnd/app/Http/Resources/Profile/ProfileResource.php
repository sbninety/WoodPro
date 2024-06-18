<?php

namespace App\Http\Resources\Profile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
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
            'name' => $this->resource->name,
            'phone' => $this->resource->phone,
            'city_id' => $this->resource->city_id,
            'city' => $this->resource->city->name,
            'district_id' => $this->resource->district_id,
            'district' => $this->resource->district->name,
            'address' => $this->resource->address
        ];
    }
}
