<?php

namespace App\Expense\Commands;

use App\Expense\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Models\Expense;

class DeleteExpenseCommand
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function handle(ExpenseRepositoryInterface $expenseRepository)
    {
        $expense = Expense::findOrFail($this->id);
        $expenseRepository->delete($expense);
    }

}
