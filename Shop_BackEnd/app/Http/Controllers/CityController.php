<?php

namespace App\Http\Controllers;

use App\Http\Resources\City\CityResource;
use App\Services\City\CityService;
use App\Traits\APIResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use APIResponseTrait;

    public function __construct(protected CityService $cityService)
    {
    }

    public function index(): JsonResponse
    {
        try {
            $data = $this->cityService->getAll();
            return $this->successResponse(CityResource::collection($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }
}
