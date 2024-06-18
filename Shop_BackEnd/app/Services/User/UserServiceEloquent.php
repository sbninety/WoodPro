<?php

namespace App\Services\User;

use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserServiceEloquent implements UserService
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    public function getRepository(): UserRepository
    {
        return $this->userRepository;
    }

    public function login(array $attributes): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        if (Auth::attempt($attributes)) {
            return Auth::user();
        }
        return null;
    }

    public function logout()
    {
        Auth::logout();
        return null;
    }

    public function updateUser(int $id, array $attributes): mixed
    {
        return $this->userRepository->update($attributes, $id);
    }

    public function storeUser(array $attributes): ?User
    {
        return $this->userRepository->create($attributes);
    }

    public function updateToken(array $attributes)
    {
        return $this->userRepository
            ->where('email', $attributes['email'])
            ->update(['token' => $attributes['token']]);
    }

    public function checkToken(string $token): ?User
    {
        return $this->userRepository
            ->where('token', $token)
            ->first();
    }

    public function updatePassword(array $attributes)
    {
        return $this->userRepository
            ->where('token', $attributes['token'])
            ->update([
                'token' => null,
                'password' => bcrypt($attributes['password'])
            ]);
    }

    public function countCustomer()
    {
        return $this->userRepository
            ->where('type', 1)
            ->get()->count();
    }

    public function getStaffLog()
    {
        return $this->userRepository
            ->where('type', 2)
            ->orderByDesc('login_at')
            ->limit(8)
            ->get();
    }
}
