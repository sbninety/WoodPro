<?php

namespace App\Repositories\ProductImage;

use App\Models\ProductImage;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ProductImage\ProductImageRepository;
use App\Validators\ProductImage\ProductImageValidator;

/**
 * Class ProductImageRepositoryEloquent.
 *
 * @package namespace App\Repositories\ProductImage;
 */
class ProductImageRepositoryEloquent extends BaseRepository implements ProductImageRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ProductImage::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
