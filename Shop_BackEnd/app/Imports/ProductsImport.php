<?php

namespace App\Imports;

use App\Models\Color;
use App\Models\Image;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Size;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $product = Product::create([
                'category_id' => $row['category_id'],
                'name' => $row['name'],
                'slug' => Str::slug($row['slug']),
                'price' => $row['price'],
                'discount' => $row['discount'] ?? null,
                'quantity' => $row['quantity'],
                'special' => $row['special'],
                'published' => $row['published'],
                'description' => $row['description'] ?? null,
            ]);

            $image = Image::create([
                'path' => $row['product_image'],
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image_id' => $image->id,
            ]);

            Color::create([
                'product_id' => $product->id,
                'name' => $row['color'],
            ]);

            Material::create([
                'product_id' => $product->id,
                'name' => $row['material'],
            ]);

            Size::Create([
                'product_id' => $product->id,
                'length' => $row['length'],
                'width' => $row['width'],
                'height' => $row['height'],
            ]);
        }
    }
}
