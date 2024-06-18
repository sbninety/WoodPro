<?php

namespace App\Services\Profile;

use App\Models\Profile;
use App\Repositories\Profile\ProfileRepository;

class ProfileServiceEloquent implements ProfileService
{
    public function __construct(protected ProfileRepository $profileRepository)
    {
    }

    public function getRepository(): ProfileRepository
    {
        return $this->profileRepository;
    }

    public function getProfile(int $id): Profile
    {
        return $this->profileRepository
            ->where('user_id', $id)
            ->first();
    }

    public function updateProfile(array $attributes, int $id)
    {
        return $this->profileRepository
            ->where('user_id', $id)
            ->update([
                'name' => $attributes['name'],
                'phone' => $attributes['phone'],
                'city_id' => $attributes['city_id'],
                'district_id' => $attributes['district_id'],
                'address' => $attributes['address']
            ]);
    }
}
