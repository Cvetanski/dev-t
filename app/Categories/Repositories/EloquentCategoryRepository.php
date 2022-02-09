<?php

namespace App\Categories\Repositories;

use App\Categories\Category;
use App\Categories\Repositories\Contracts\CategoryRepositoryInterface;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    public function all():array
    {
        return Category::all()->all();
    }

    public function get(int $id): ?Category
    {
        return Category::findOrFail($id);
    }

    public  function store(Category $category)
    {
        $category->save();
    }

    public function delete(Category $category)
    {
        $category->delete();
    }


    public function update(Category $category)
    {
        $category->save();
    }

    public function findOrFail(int $id): Category
    {
        return Category::findOrFail($id);
    }

}
