<?php

namespace App\Repositories\Color;

use App\Models\Color;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Color\ColorRepository;
use App\Validators\Color\ColorValidator;

/**
 * Class ColorRepositoryEloquent.
 *
 * @package namespace App\Repositories\Color;
 */
class ColorRepositoryEloquent extends BaseRepository implements ColorRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Color::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
