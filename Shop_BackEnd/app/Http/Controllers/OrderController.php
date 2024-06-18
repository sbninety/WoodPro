<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\CreateOrderRequest;
use App\Http\Requests\Order\SearchOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Services\Order\OrderService;
use App\Traits\APIResponseTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use APIResponseTrait;

    public function __construct(protected OrderService $orderService)
    {
    }

    public function store(CreateOrderRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $params = $request->validated();
            $params['order_id'] = $this->createOrderId();
            $params['status'] = 1;

            $this->orderService->storeOrder($params);

            DB::commit();
            return $this->successResponse(null, 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    private function createOrderId()
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');

        $orderCount = $this->orderService->countOrderToday();

        $orderCount++;

        return sprintf("%04d%02d%02d%04d", $year, $month, $day, $orderCount);
    }

    public function getOrderByUser(int $id): JsonResponse
    {
        try {
            if ($id != Auth::id()) {
                return $this->errorResponse(null, 'Unauthenticated', 401);
            }
            $data = $this->orderService->getOrderByUser($id);
            return $this->successResponse(OrderResource::collection($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function update(UpdateOrderRequest $request, int $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $this->orderService->updateOrder($request->validated(), $id);
            DB::commit();
            return $this->successResponse(null, 'Successfullly', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function getRevenue(): JsonResponse
    {
        try {
            $data = $this->orderService->revenueByMonth();
            return $this->successResponse($data, 'Successfullly', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function countOrder(): JsonResponse
    {
        try {
            $data = $this->orderService->countOrder();
            return $this->successResponse($data, 'Successfullly', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function search(SearchOrderRequest $request): JsonResponse
    {
        try {
            $data = $this->orderService->search($request->validated());
            return $this->successResponse(OrderResource::collection($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function export(int $id)
    {
        try {
            $order = $this->orderService->getOrderById($id);
            $data = [
                'order_id' => $order->order_id
            ];
            $invoice = Pdf::loadview('invoice', compact('data'));
            return $invoice->stream();
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong');
        }
    }
}
