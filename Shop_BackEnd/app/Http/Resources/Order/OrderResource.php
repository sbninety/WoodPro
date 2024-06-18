<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\OrderDetail\OrderDetailResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'order_id' => $this->resource->order_id,
            'name' => $this->resource->name,
            'phone' => $this->resource->phone,
            'date' => $this->dateFormat(),
            'total' => $this->resource->total,
            'status' => $this->resource->status,
            'ship_method' => $this->resource->ship_method,
            'payment_method' => $this->resource->payment_method,
            'city' => $this->resource->city->name,
            'district' => $this->resource->district->name,
            'address' => $this->resource->address,
            'items' => OrderDetailResource::collection($this->resource->orderDetails)
        ];
    }

    private function dateFormat(): string
    {
        $date = Carbon::parse($this->resource->created_at);

        $ngay = $date->day;
        $thang = $date->month;
        $nam = $date->year;

        return $ngay . '/' . $thang . '/' . $nam;
    }
}
