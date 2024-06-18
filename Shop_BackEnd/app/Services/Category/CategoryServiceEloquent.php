<?php

namespace App\Services\Category;

use App\Models\Category;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Support\Collection;

class CategoryServiceEloquent implements CategoryService
{
    public function __construct(protected CategoryRepository $categoryRepository)
    {
    }

    public function getRepository(): CategoryRepository
    {
        return $this->categoryRepository;
    }

    public function getAll(): Collection
    {
        return $this->categoryRepository->all();
    }

    public function getCategoryBySlug(string $slug): ?Category
    {
        return $this->categoryRepository
            ->where('slug', $slug)
            ->select('id', 'name')
            ->first();
    }
}
