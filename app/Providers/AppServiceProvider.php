<?php

namespace App\Providers;

use App\Categories\Repositories\Contracts\CategoryRepositoryInterface;
use App\Categories\Repositories\EloquentCategoryRepository;
use App\Expense\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Expense\Repositories\EloquentExpenseRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            CategoryRepositoryInterface::class,
            EloquentCategoryRepository::class
        );
        $this->app->bind(
            ExpenseRepositoryInterface::class,
            EloquentExpenseRepository::class
        );

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
}
