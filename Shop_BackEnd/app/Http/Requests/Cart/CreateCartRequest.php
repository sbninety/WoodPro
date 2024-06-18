<?php

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class CreateCartRequest extends FormRequest
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
                'required',
                'numeric'
            ],
            'product_id' => [
                'required',
                'numeric'
            ],
            'color_id' => [
                'required',
                'numeric'
            ],
            'material_id' => [
                'required',
                'numeric'
            ],
            'size_id' => [
                'required',
                'numeric'
            ],
            'quantity' => [
                'required',
                'numeric'
            ]
        ];
    }
}
