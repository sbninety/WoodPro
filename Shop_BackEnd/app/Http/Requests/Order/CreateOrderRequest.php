<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => [
                'nullable'
            ],
            'name' => [
                'required'
            ],
            'phone' => [
                'required'
            ],
            'city_id' => [
                'required'
            ],
            'district_id' => [
                'required'
            ],
            'address' => [
                'required'
            ],
            'ship_method' => [
                'required'
            ],
            'payment_method' => [
                'required'
            ],
            'total' => [
                'required'
            ],
            'items.*.product_id' => [
                'required'
            ],
            'items.*.color_id' => [
                'required'
            ],
            'items.*.material_id' => [
                'required'
            ],
            'items.*.size_id' => [
                'required'
            ],
            'items.*.price' => [
                'required'
            ],
            'items.*.quantity' => [
                'required'
            ]
        ];
    }
}
