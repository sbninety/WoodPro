<?php

namespace App\Http\Resources\Comment;

use App\Http\Resources\Image\ImageResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'rate' => $this->resource->rate,
            'product_id' => $this->resource->product_id,
            'content' => $this->resource->content,
            'images' => ImageResource::collection($this->resource->commentImages),
            'created_at' => $this->dateFormat(),
        ];
    }

    private function dateFormat(): string
    {
        $date = Carbon::parse($this->resource->created_at);

        $ngay = $date->day;
        $thang = $date->month;
        $nam = $date->year;
        $gio = $date->hour;
        $phut = $date->minute;

        return $ngay . '-' . $thang . '-' . $nam . ' ' . $gio . ':' . $phut;
    }
}
