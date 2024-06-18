<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\StaffLogResource;
use App\Services\User\UserService;
use App\Traits\APIResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use APIResponseTrait;

    public function __construct(protected UserService $userService)
    {
    }

    public function countCustomer(): JsonResponse
    {
        try {
            $data = $this->userService->countCustomer();
            return $this->successResponse($data, 'Succeccfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function getStaffLog(): JsonResponse
    {
        try {
            $data = $this->userService->getStaffLog();
            return $this->successResponse(StaffLogResource::collection($data), 'Succeccfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }
}
