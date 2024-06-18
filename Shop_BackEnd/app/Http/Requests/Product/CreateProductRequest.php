<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
            'category_id' => [
                'required'
            ],
            'name' => [
                'required'
            ],
            'slug' => [
                'required',
                'unique:products,slug'
            ],
            'price' => [
                'required',
                'numeric'
            ],
            'discount' => [
                'nullable'
            ],
            'quantity' => [
                'required',
                'numeric'
            ],
            'published' => [
                'required',
                'numeric'
            ],
            'description' => [
                'nullable'
            ],
            'images.*' => [
                'required'
            ],
            'colors.*.name' => [
                'required'
            ],
            'materials.*.name' => [
                'required'
            ],
            'sizes.*.length' => [
                'required',
                'numeric'
            ],
            'sizes.*.width' => [
                'required',
                'numeric'
            ],
            'sizes.*.height' => [
                'required',
                'numeric'
            ],
            'sizes.*.price' => [
                'nullable'
            ]
        ];
    }
}
