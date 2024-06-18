<?php

namespace App\Services\Product;

use App\Exports\ProductExport;
use App\Http\Requests\Product\ProductImportRequest;
use App\Models\Product;
use App\Repositories\Color\ColorRepository;
use App\Repositories\Image\ImageRepository;
use App\Repositories\Material\MaterialRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductImage\ProductImageRepository;
use App\Repositories\Size\SizeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Str;

class ProductServiceEloquent implements ProductService
{
    public function __construct(
        protected ProductRepository $productRepository,
        protected ImageRepository $imageRepository,
        protected ProductImageRepository $productImageRepository,
        protected ColorRepository $colorRepository,
        protected MaterialRepository $materialRepository,
        protected SizeRepository $sizeRepository
    ) {
    }

    public function getRepository(): ProductRepository
    {
        return $this->productRepository;
    }

    public function getSpecial(): Collection
    {
        return $this->productRepository
            ->where([['published', 1], ['special', 1]])
            ->inRandomOrder()
            ->take(8)
            ->get();
    }

    public function getSale(): Collection
    {
        return $this->productRepository
            ->where('published', 1)
            ->whereNotNull('discount')
            ->inRandomOrder()
            ->take(8)
            ->get();
    }

    public function getSuggest(): Collection
    {
        return $this->productRepository
            ->where('published', 1)
            ->inRandomOrder()
            ->take(8)
            ->get();
    }

    public function getOther(): Collection
    {
        return $this->productRepository
            ->where('published', 1)
            ->inRandomOrder()
            ->take(4)
            ->get();
    }

    public function getAll(array $attributes): Collection
    {
        if (empty($attributes)) {
            return $this->productRepository
                ->orderByDesc('created_at')
                ->get();
        }

        $keyword = $attributes['keyword'];
        $publish = $attributes['publish'];

        if (!$keyword && $publish) {
            return $this->productRepository
                ->where('published', $publish)
                ->orderByDesc('created_at')
                ->get();
        } else if ($keyword && !$publish) {
            return $this->productRepository
                ->where('name', 'LIKE', '%' . $keyword . '%')
                ->orderByDesc('created_at')
                ->get();
        } else {
            return $this->productRepository
                ->where([
                    ['published', $publish],
                    ['name', 'LIKE', '%' . $keyword . '%']
                ])
                ->orderByDesc('created_at')
                ->get();
        }
    }

    public function getProductByCategoryId(int $id): Collection
    {
        return $this->productRepository
            ->where('category_id', $id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getProductBySlug(string $slug)
    {
        return $this->productRepository
            ->where('slug', $slug)
            ->first();
    }

    public function countProduct()
    {
        return $this->productRepository->all()->count();
    }

    public function updateProduct(array $attributes, int $id): mixed
    {
        return $this->productRepository->update($attributes, $id);
    }

    public function storeProduct(array $attributes): ?Product
    {
        return $this->productRepository->create($attributes);
    }

    public function getProductById(int $id)
    {
        return $this->productRepository->find($id);
    }
    public function getNotSellingProducts(int $month, int $year)
    {
        $productsOrderInMonth =  $this->productRepository
            ->join('order_details', 'products.id', '=', 'order_details.product_id')
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->where('orders.status', '4')
            ->whereMonth('orders.created_at', $month)
            ->whereYear('orders.created_at', $year)
            ->select('products.id', 'products.name', DB::raw('SUM(order_details.quantity) as total_quantity'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->get();

        $products = $this->productRepository
            ->where('published', '1')
            ->select('id', 'name')
            ->get();

        $result = [];

        foreach ($products as $product) {
            $found = false;
            foreach ($productsOrderInMonth as $pro) {
                if ($product['id'] == $pro['id']) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $product['total_quantity'] = 0;
                $result[] = $product;
            }
        }
        return $result;
    }

    public function exportProduct(Request $request): BinaryFileResponse
    {
        $data = $this->productRepository->all();
        return Excel::download(new ProductExport($data), 'list-product.xlsx', null, [
            'Access-Control-Expose-Headers' => [
                'Content-Disposition'
            ]
        ]);
    }

    public function importProduct(array $attributes)
    {
        foreach ($attributes as $insertItem) {
            $product = $this->productRepository->create($insertItem);

            $image = $this->imageRepository->create([
                'path' => $insertItem['product_image']
            ]);

            $this->productImageRepository->create([
                'product_id' => $product->id,
                'image_id' => $image->id,
            ]);

            $this->colorRepository->create([
                'product_id' => $product->id,
                'name' => $insertItem['color'],
            ]);

            $this->materialRepository->create([
                'product_id' => $product->id,
                'name' => $insertItem['material']
            ]);

            $this->sizeRepository->create([
                'product_id' => $product->id,
                'length' => $insertItem['length'],
                'width' => $insertItem['width'],
                'height' => $insertItem['height'],
            ]);
        }
    }
}
