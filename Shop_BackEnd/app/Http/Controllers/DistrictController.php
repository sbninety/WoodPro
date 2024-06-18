<?php

namespace App\Http\Controllers;

use App\Services\District\DistrictService;
use App\Traits\APIResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    use APIResponseTrait;

    public function __construct(protected DistrictService $districtService)
    {
    }

    public function getDistrictByCityId(int $id): JsonResponse
    {
        try {
            $data = $this->districtService->getDistrictByCityId($id);
            return $this->successResponse($data, 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }
}
