<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CreateCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Http\Resources\Cart\CartResource;
use App\Services\Cart\CartService;
use App\Traits\APIResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use APIResponseTrait;

    public function __construct(protected CartService $cartService)
    {
    }

    public function getCartByUserId(int $id): JsonResponse
    {
        try {
            // if ($id != Auth::user()->id) {
            //     return $this->errorResponse(null, 'Unauthenticated', 401);
            // }
            $data = $this->cartService->getCartByUserId($id);
            return $this->successResponse(CartResource::collection($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function store(CreateCartRequest $request): JsonResponse
    {
        try {
            $data = $this->cartService->storeCart($request->validated());
            return $this->successResponse(new CartResource($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function update(UpdateCartRequest $request, int $id): JsonResponse
    {
        try {
            $this->cartService->updateCart($id, $request->validated());
            return $this->successResponse(null, 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->cartService->deleteCart($id);
            return $this->successResponse(null, 'successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }
}
