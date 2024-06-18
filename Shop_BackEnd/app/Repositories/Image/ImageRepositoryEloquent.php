<?php

namespace App\Repositories\Image;

use App\Models\Image;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Image\ImageRepository;
use App\Validators\Image\ImageValidator;

/**
 * Class ImageRepositoryEloquent.
 *
 * @package namespace App\Repositories\Image;
 */
class ImageRepositoryEloquent extends BaseRepository implements ImageRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Image::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
