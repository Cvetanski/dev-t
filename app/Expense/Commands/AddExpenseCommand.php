<?php

namespace App\Expense\Commands;

use App\Expense\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Models\Expense;
use Illuminate\Http\Request;


class AddExpenseCommand
{
    public function handle(ExpenseRepositoryInterface $expenseRepository, Request $request)
    {
        $user = auth('sanctum')->user();

        $expense = new Expense();
        $expense->amount = $request->amount;
        $expense->category =  $request->category;
        $expense->category_id = $request->category_id;
        $expense->description = $request->description;
        $expense->user_id = $user->id;

        $expenseRepository->store($expense);
        $user->update(['amount'=>$user->amount - $request->amount]);

    }
}
