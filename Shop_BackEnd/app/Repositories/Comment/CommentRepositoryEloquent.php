<?php

namespace App\Repositories\Comment;

use App\Models\Comment;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Comment\CommentRepository;
use App\Validators\Comment\CommentValidator;

/**
 * Class CommentRepositoryEloquent.
 *
 * @package namespace App\Repositories\Comment;
 */
class CommentRepositoryEloquent extends BaseRepository implements CommentRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Comment::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
