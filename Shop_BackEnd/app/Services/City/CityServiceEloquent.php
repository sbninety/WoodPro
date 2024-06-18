<?php

namespace App\Services\City;

use App\Repositories\City\CityRepository;
use Illuminate\Support\Collection;

class CityServiceEloquent implements CityService
{
    public function __construct(protected CityRepository $cityRepository)
    {
    }

    public function getRepository(): CityRepository
    {
        return $this->cityRepository;
    }

    public function getAll(): Collection
    {
        return $this->cityRepository->all();
    }
}
