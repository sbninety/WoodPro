<?php

namespace App\Services\District;

use App\Repositories\District\DistrictRepository;
use Illuminate\Support\Collection;

interface DistrictService
{
    public function getRepository(): DistrictRepository;

    public function getDistrictByCityId(int $id): Collection;
}
