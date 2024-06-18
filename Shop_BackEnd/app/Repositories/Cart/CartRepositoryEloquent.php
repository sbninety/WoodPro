<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Cart\CartRepository;
use App\Validators\Cart\CartValidator;

/**
 * Class CartRepositoryEloquent.
 *
 * @package namespace App\Repositories\Cart;
 */
class CartRepositoryEloquent extends BaseRepository implements CartRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Cart::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
