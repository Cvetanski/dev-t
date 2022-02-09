<?php

namespace App\Expense\Repositories\Contracts;

use App\Models\Expense;

interface ExpenseRepositoryInterface
{
    public function all(): array;

    public function get(int $id): ?Expense;

    public function store(Expense $expense);

    public function delete(Expense $expense);

    public function update(Expense $expense);

    public function findOrFail(int $id): Expense;
}
