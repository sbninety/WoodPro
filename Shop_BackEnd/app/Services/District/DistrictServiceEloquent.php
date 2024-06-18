<?php

namespace App\Services\District;

use App\Repositories\District\DistrictRepository;
use Illuminate\Support\Collection;

class DistrictServiceEloquent implements DistrictService
{
    public function __construct(protected DistrictRepository $districtRepository)
    {
    }

    public function getRepository(): DistrictRepository
    {
        return $this->districtRepository;
    }

    public function getDistrictByCityId(int $id): Collection
    {
        return $this->districtRepository
            ->where('city_id', $id)
            ->select('id', 'name')
            ->get();
    }
}
