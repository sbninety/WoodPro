<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductImportRequest extends FormRequest
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
            'file' => ['required']
            // '*.category_id' => [
            //     'required',
            //     'numeric'
            // ],
            // '*.name' => [
            //     'required'
            // ],
            // '*.slug' => [
            //     'required',
            //     'unique:products,slug'
            // ],
            // '*.price' => [
            //     'required'
            // ],
            // '*.discount' => [
            //     'nullable'
            // ],
            // '*.quantity' => [
            //     'required'
            // ],
            // '*.special' => [
            //     'nullable'
            // ],
            // '*.published' => [
            //     'required'
            // ],
            // '*.description' => [
            //     'nullable'
            // ],
            // '*.product_image' => [
            //     'required'
            // ],
            // '*.color' => [
            //     'required'
            // ],
            // '*.material' => [
            //     'required'
            // ],
            // '*.length' => [
            //     'required',
            //     'numeric'
            // ],
            // '*.width' => [
            //     'required',
            //     'numeric'
            // ],
            // '*.height' => [
            //     'required',
            //     'numeric'
            // ]
        ];
    }
}
