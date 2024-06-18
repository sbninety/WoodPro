<?php

namespace App\Services\User;

use App\Repositories\User\UserRepository;

interface UserService
{
    public function getRepository(): UserRepository;

    public function login(array $attributes);

    public function logout();

    public function updateUser(int $id, array $attributes): mixed;

    public function storeUser(array $attributes);

    public function updateToken(array $attributes);

    public function checkToken(string $token);

    public function updatePassword(array $attributes);

    public function countCustomer();

    public function getStaffLog();
}
