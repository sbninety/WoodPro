<?php

namespace App\Repositories\District;

use App\Models\District;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\District\DistrictRepository;
use App\Validators\District\DistrictValidator;

/**
 * Class DistrictRepositoryEloquent.
 *
 * @package namespace App\Repositories\District;
 */
class DistrictRepositoryEloquent extends BaseRepository implements DistrictRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return District::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
