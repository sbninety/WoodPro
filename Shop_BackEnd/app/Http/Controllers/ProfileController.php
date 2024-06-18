<?php

namespace App\Http\Controllers;

use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Resources\Profile\ProfileResource;
use App\Services\Profile\ProfileService;
use App\Traits\APIResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use APIResponseTrait;

    public function __construct(protected ProfileService $profileService)
    {
    }

    public function show(int $id): JsonResponse
    {
        try {
            $data = $this->profileService->getProfile($id);
            return $this->successResponse(new ProfileResource($data), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function update(UpdateProfileRequest $request, int $id): JsonResponse
    {
        try {
            $this->profileService->updateProfile($request->validated(), $id);
            return $this->successResponse(null, 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }
}
