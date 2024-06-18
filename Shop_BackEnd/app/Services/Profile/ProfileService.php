<?php

namespace App\Services\Profile;

use App\Models\Profile;
use App\Repositories\Profile\ProfileRepository;

interface ProfileService
{
    public function getRepository(): ProfileRepository;

    public function getProfile(int $id): Profile;

    public function updateProfile(array $attributes, int $id);
}
