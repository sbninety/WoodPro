<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Resources\Comment\CommentResource;
use App\Services\Comment\CommentService;
use App\Traits\APIResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use APIResponseTrait;

    public function __construct(protected CommentService $commentService)
    {
    }

    public function store(CreateCommentRequest $request): JsonResponse
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $comment = $this->commentService->store($request->validated());
            return $this->successResponse(new CommentResource($comment), 'Successfully', 200);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception->getMessage(), 'Somethings went wrong', 500);
        }
    }
}
