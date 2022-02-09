<?php

namespace App\Expense\Commands;

use App\Expense\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Models\Expense;
use Illuminate\Http\Request;

class EditExpenseCommand
{
    private $id;

    public function __construct(int $id)
    {
        $this->id=$id;
    }

    public function handle(ExpenseRepositoryInterface $expenseRepository, Request $request)
    {
        $user = auth('sanctum')->user();

        $expense = $expenseRepository->findOrFail($this->id);
        $currentExpenseAmount = $expense->amount;

        $expense->amount = $request->amount;
        $expense->description = $request->description;
        $expense->category = $request->category;
        $expense->category_id = $request->category_id;
        $expense->user_id = $user->id;

        $expense->update();

        if($request->amount > $currentExpenseAmount){
            $user->update(['amount'=> $user->amount - ($request->amount - $currentExpenseAmount)]);
        }else{
            $user->update(['amount'=> $user->amount + ($currentExpenseAmount - $request->amount) ]);
        }
    }

}
