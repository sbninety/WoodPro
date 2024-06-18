<?php

namespace App\Observers;

use App\Helper\ImageManagerTrait;
use App\Models\Comment;
use App\Services\Image\ImageService;

class CommentObserver
{
    use ImageManagerTrait;

    public function __construct(protected ImageService $imageService)
    {
    }

    /**
     * Handle the Comment "created" event.
     */
    public function created(Comment $comment): void
    {
        $images = request()->images;

        foreach ($images as $image) {
            $fileData = $this->uploads($image);
            $imageUpload = $this->imageService->storeImage($fileData);
            $comment->commentImages()->create([
                'product_id' => $comment->id,
                'image_id' => $imageUpload->id
            ]);
        }
    }

    /**
     * Handle the Comment "updated" event.
     */
    public function updated(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "deleted" event.
     */
    public function deleted(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "restored" event.
     */
    public function restored(Comment $comment): void
    {
        //
    }

    /**
     * Handle the Comment "force deleted" event.
     */
    public function forceDeleted(Comment $comment): void
    {
        //
    }
}
