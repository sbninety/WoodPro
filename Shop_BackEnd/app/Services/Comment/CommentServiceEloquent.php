<?php

namespace App\Services\Comment;

use App\Repositories\Comment\CommentRepository;

class CommentServiceEloquent implements CommentService
{
    public function __construct(protected CommentRepository $commentRepository)
    {
    }

    public function getRepository(): CommentRepository
    {
        return $this->commentRepository;
    }

    public function store(array $attributes)
    {
        return $this->commentRepository->create($attributes);
    }
}
