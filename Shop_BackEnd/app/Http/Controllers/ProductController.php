<?php

namespace App\Http\Controllers;

use App\Helper\ImageManagerTrait;
use App\Http\Requests\Product\CreateProductRequest;
use App\Http\Requests\Product\EditProductRequest;
use App\Http\Requests\Product\GetProductRequest;
use App\Http\Requests\Product\ProductImportRequest;
use App\Http\Resources\Product\ProductResource;
use App\Imports\ProductsImport;
use App\Services\Color\ColorService;
use App\Services\Image\ImageService;
use App\Services\Material\MaterialService;
use App\Services\OrderDetail\OrderDetailService;
use App\Services\Product\ProductService;
use App\Services\ProductImage\ProductImageService;
use App\Services\Size\SizeService;
use App\Traits\APIResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ProductController extends Controller
{
    use APIResponseTrait, ImageManagerTrait;

    public function __construct(
        protected ProductService $productService,
        protected ColorService $colorService,
        protected MaterialService $materialService,
        protected SizeService $sizeService,
        protected ImageService $imageService,
        protected ProductImageService $productImageService,
        protected OrderDetailService $orderDetailService
    ) {
    }

    public function getSpecial(): JsonResponse
    {
        try {
            $data = $this->productService->getSpecial();
            return $this->successResponse(ProductResource::collection($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function getSale(): JsonResponse
    {
        try {
            $data = $this->productService->getSale();
            return $this->successResponse(ProductResource::collection($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function getSuggest(): JsonResponse
    {
        try {
            $data = $this->productService->getSuggest();
            return $this->successResponse(ProductResource::collection($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Sonethings went wrong', 500);
        }
    }

    public function getOther(): JsonResponse
    {
        try {
            $data = $this->productService->getOther();
            return $this->successResponse(ProductResource::collection($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function index(GetProductRequest $request): JsonResponse
    {
        try {
            $params = $request->validated();
            $data = $this->productService->getAll($params);

            return $this->successResponse(ProductResource::collection($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function getProductByCategoryId(int $id): JsonResponse
    {
        try {
            $data = $this->productService->getProductByCategoryId($id);
            return $this->successResponse(ProductResource::collection($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function getProductBySlug(string $slug): JsonResponse
    {
        try {
            $data = $this->productService->getProductBySlug($slug);
            return $this->successResponse(new ProductResource($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function countProduct(): JsonResponse
    {
        try {
            $data = $this->productService->countProduct();
            return $this->successResponse($data, 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        try {
            $params['published'] = $request->get('published');
            $this->productService->updateProduct($params, $id);
            return $this->successResponse(null, 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function create(CreateProductRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $params = $request->validated();
            $params['slug'] = Str::slug($params['slug']);

            $this->productService->storeProduct($params);

            DB::commit();
            return $this->successResponse(null, 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function edit(int $id): JsonResponse
    {
        try {
            $data = $this->productService->getProductById($id);
            return $this->successResponse(new ProductResource($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function update(EditProductRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $params = $request->validated();
            $this->execute($request, $id);
            $this->productService->updateProduct($params, $id);
            $this->colorService->upsert($params['colors']);
            $this->materialService->upsert($params['materials']);
            $this->sizeService->upsert($params['sizes']);
            // dd(1111);
            DB::commit();
            return $this->successResponse(null, 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    private function execute(mixed $request, int $id): void
    {
        if ($request->newImages) {
            foreach ($request->newImages as $image) {
                $fileData = $this->uploads($image);
                $imageUpload = $this->imageService->storeImage($fileData);
                $this->productImageService->store([
                    'product_id' => $id,
                    'image_id' => $imageUpload->id
                ]);
            }
        }
        if ($request->deleteImages) {
            foreach ($request->deleteImages as $image) {
                $this->productImageService->destroy($image['id']);
            }
        }
    }

    public function getBestSellingProducts(Request $request): JsonResponse
    {
        try {
            $month = $request->input('month', Carbon::now()->format('m'));
            $year = $request->input('year', Carbon::now()->format('Y'));

            return $this->successResponse($this->orderDetailService->getBestSellingProducts($month, $year), 'successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function getWorstSellingProducts(Request $request): JsonResponse
    {
        try {
            $month = $request->input('month', Carbon::now()->format('m'));
            $year = $request->input('year', Carbon::now()->format('Y'));

            $littleSellingProducts = $this->orderDetailService->getLittleSellingProducts($month, $year);
            $notSellingProducts = $this->productService->getNotSellingProducts($month, $year);

            return $this->successResponse(array_slice(array_merge($littleSellingProducts->toArray(), $notSellingProducts), -5), 'successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function exportProduct(Request $request): BinaryFileResponse
    {
        return $this->productService->exportProduct($request);
    }

    public function importProduct(ProductImportRequest $request)
    {
        try {
            Excel::import(new ProductsImport, $request->file('file'));
            return $this->successResponse($this->productService->getAll([]), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }
}
