<?php

namespace App\Repositories\City;

use App\Models\City;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\City\CityRepository;
use App\Validators\City\CityValidator;

/**
 * Class CityRepositoryEloquent.
 *
 * @package namespace App\Repositories\City;
 */
class CityRepositoryEloquent extends BaseRepository implements CityRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return City::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
