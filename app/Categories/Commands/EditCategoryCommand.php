<?php

namespace App\Categories\Commands;

use App\Categories\Repositories\Contracts\CategoryRepositoryInterface;

class EditCategoryCommand
{
    private $data;
    private $id;

    public function __construct(int $id, object $data)
    {
        $this->id=$id;
        $this->data=$data;
    }

    public function handle(CategoryRepositoryInterface $categoryRepository)
    {
        $category = $categoryRepository->findOrFail($this->id);
        $category->fill($this->data->all());
        $categoryRepository->store($category);
    }


}
