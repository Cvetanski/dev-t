<?php

namespace App\Expense\Repositories;

use App\Expense\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Models\Expense;

class EloquentExpenseRepository implements ExpenseRepositoryInterface
{
    public function all():array
    {
        return Expense::all()->all();
    }

    public function get(int $id): ?Expense
    {
        return Expense::findOrFail($id);
    }

    public  function store(Expense $expense)
    {
        $expense->save();
    }

    public function delete(Expense $expense)
    {
        $expense->delete();
    }


    public function update(Expense $expense)
    {
        $expense->save();
    }

    public function findOrFail(int $id): Expense
    {
        return Expense::findOrFail($id);
    }
}
