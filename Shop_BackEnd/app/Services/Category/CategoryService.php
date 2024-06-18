<?php

namespace App\Services\Category;

use App\Models\Category;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Support\Collection;

interface CategoryService
{
    public function getRepository(): CategoryRepository;

    public function getAll(): Collection;

    public function getCategoryBySlug(string $slug): ?Category;
}
