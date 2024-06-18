<?php

namespace App\Repositories\Material;

use App\Models\Material;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Material\MaterialRepository;
use App\Validators\Material\MaterialValidator;

/**
 * Class MaterialRepositoryEloquent.
 *
 * @package namespace App\Repositories\Material;
 */
class MaterialRepositoryEloquent extends BaseRepository implements MaterialRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Material::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
