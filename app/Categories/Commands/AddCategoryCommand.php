<?php

namespace App\Categories\Commands;

use App\Categories\Category;
use App\Categories\Repositories\Contracts\CategoryRepositoryInterface;

class AddCategoryCommand
{
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(CategoryRepositoryInterface $categoryRepository)
    {
        $category = new Category();

        $category->setName($this->data['name']);

        $categoryRepository->store($category);
    }

}
