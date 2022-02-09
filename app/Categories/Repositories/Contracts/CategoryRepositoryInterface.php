<?php

namespace App\Categories\Repositories\Contracts;

use App\Categories\Category;
use App\Categories\Providers\CategoryServiceProvider;

interface CategoryRepositoryInterface
{
    public function all(): array;

    public function get(int $id): ?Category;

    public function store(Category $category);

    public function delete(Category $category);

    public function update(Category $category);

    public function findOrFail(int $id): Category;


}
