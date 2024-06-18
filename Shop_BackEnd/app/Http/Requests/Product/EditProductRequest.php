<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EditProductRequest extends FormRequest
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
        $slug = $this->request->get("slug");
        return [
            'category_id' => [
                'required'
            ],
            'name' => [
                'required'
            ],
            'slug' => [
                'required',
                Rule::unique('products')->ignore($slug, 'slug')
            ],
            'price' => [
                'required',
                'numeric'
            ],
            'quantity' => [
                'required',
                'numeric'
            ],
            'discount' => [
                'nullable'
            ],
            'published' => [
                'required'
            ],
            'newImages.*' => [
                'nullable'
            ],
            'deleteImages.*' => [
                'nullable'
            ],
            'description' => [
                'nullable'
            ],
            'colors.*.id' => [
                'nullable'
            ],
            'colors.*.product_id' => [
                'required'
            ],
            'colors.*.name' => [
                'required'
            ],
            'materials.*.id' => [
                'nullable'
            ],
            'materials.*.product_id' => [
                'required'
            ],
            'materials.*.name' => [
                'required'
            ],
            'sizes.*.id' => [
                'nullable'
            ],
            'sizes.*.product_id' => [
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
