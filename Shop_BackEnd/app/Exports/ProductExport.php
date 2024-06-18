<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductExport implements FromCollection, WithHeadings, WithMapping
{
    private mixed $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return new Collection($this->data);
    }

    public function headings(): array
    {
        return [
            'id',
            'category_id',
            'name',
            'slug',
            'price',
            'discount',
            'quantity',
            'special',
            'published',
            'description',
            'product_image',
            'color',
            'material',
            'length',
            'width',
            'height',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @param Product $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->category_id,
            $row->name,
            $row->slug,
            $row->price,
            $row->discount ? $row->discount : '0',
            $row->quantity ? $row->quantity : '0',
            $row->special ? $row->special : '0',
            $row->published ? $row->published : '0',
            $row->description,
            $row->productImages->first()->image->path,
            $row->colors->first()->name,
            $row->materials->first()->name,
            $row->sizes->first()->length ? $row->sizes->first()->length : '0',
            $row->sizes->first()->width ? $row->sizes->first()->width : '0',
            $row->sizes->first()->height ? $row->sizes->first()->height : '0',
            $row->created_at,
            $row->updated_at,
        ];
    }
}
