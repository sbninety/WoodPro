<?php

namespace App\Services\City;

use App\Repositories\City\CityRepository;
use Illuminate\Support\Collection;

interface CityService
{
    public function getRepository(): CityRepository;

    public function getAll(): Collection;
}
