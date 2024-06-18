<?php

namespace App\Services\Product;

use App\Http\Requests\Product\ProductImportRequest;
use App\Models\Product;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface ProductService
{
    public function getRepository(): ProductRepository;

    public function getSpecial(): Collection;

    public function getSale(): Collection;

    public function getSuggest(): Collection;

    public function getOther(): Collection;

    public function getAll(array $attributes): Collection;

    public function getProductByCategoryId(int $id): Collection;

    public function getProductBySlug(string $slug);

    public function countProduct();

    public function updateProduct(array $attributes, int $id): mixed;

    public function storeProduct(array $attributes): ?Product;

    public function getProductById(int $id);

    public function getNotSellingProducts(int $month, int $year);

    public function exportProduct(Request $request);

    public function importProduct(array $attributes);
}
