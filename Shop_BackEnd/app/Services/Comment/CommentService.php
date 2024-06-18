<?php

namespace App\Services\Comment;

use App\Repositories\Comment\CommentRepository;

interface CommentService
{
    public function getRepository(): CommentRepository;

    public function store(array $attributes);
}
