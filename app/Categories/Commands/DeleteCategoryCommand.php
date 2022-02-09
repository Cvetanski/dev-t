<?php

namespace App\Categories\Commands;

use App\Categories\Category;
use App\Categories\Repositories\Contracts\CategoryRepositoryInterface;

class DeleteCategoryCommand
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function handle(CategoryRepositoryInterface $categoryRepository)
    {
        $product = Category::findOrFail($this->id);
        $categoryRepository->delete($product);
    }

}
