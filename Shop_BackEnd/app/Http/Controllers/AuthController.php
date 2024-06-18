<?php

namespace App\Http\Controllers;

use App\Helper\SendMailTrait;
use App\Http\Requests\User\ForgotPasswordRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\ResetPasswordRequest;
use App\Services\User\UserService;
use App\Traits\APIResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use APIResponseTrait, SendMailTrait;

    public function __construct(protected UserService $userService)
    {
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = $this->userService->login($request->validated());
            if (!$user) {
                return $this->errorResponse(null, 'Data not found', 404);
            }
            $request->session()->regenerate();

            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $params['login_at'] = date("Y-m-d H:i:s");
            $this->userService->updateUser($user->id, $params);

            $params['id'] = $user->id;
            $params['type'] = $user->type;
            $params['token'] = $user->createToken('authToken')->plainTextToken;

            return $this->successResponse($params, 'Login Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong');
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $params = $request->validated();
            $params['password'] = bcrypt($params['password']);

            $data = $this->userService->storeUser($params);

            return $this->successResponse($data, 'Register Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function forgot(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $params = $request->validated();

            $params['token'] = Str::random(64);
            $this->userService->updateToken($params);

            $this->callSendUserForgotPasswordMail([
                'email' => $params['email'],
                'token' => $params['token']
            ]);

            return $this->successResponse(null, 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function checkToken(Request $request): JsonResponse
    {
        try {
            $user = $this->userService->checkToken($request->get('token'));
            if (!$user) {
                return $this->errorResponse(null, 'Token invalid', 404);
            }
            return $this->successResponse(null, 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $params = $request->validated();
            $this->userService->updatePassword($params);

            return $this->successResponse(null, 'Reset Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Sonethings went wrong', 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $this->userService->logout();
            $request->session()->invalidate();
            return $this->successResponse(null, 'Logout Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }
}
