<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'type',
        'size'
    ];

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function commentImages(): HasMany
    {
        return $this->hasMany(CommentImage::class);
    }
}
