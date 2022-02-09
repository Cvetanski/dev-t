<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Categories\CategoriesController;
use App\Http\Controllers\Expense\ExpenseController;
use App\Http\Controllers\User\UserController;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
});

Route::post('/store', [RegisteredUserController::class, 'store']);
Route::post('/login', [RegisterController::class, 'login']);
Route::post('/logout', [RegisterController::class, 'logout']);

Route::prefix('dashboard')->group(function (){
    Route::put('/add-amount', [UserController::class, 'addAmountToAccount']);
});

//Route::put('/add-amount', [UserController::class, 'addAmountToAccount']);


Route::prefix('category')->group(function (){
    Route::get('/',[CategoriesController::class,'index']);
    Route::post('/store', [CategoriesController::class, 'store']);
    Route::delete('/delete/{id}', [CategoriesController::class, 'destroy']);
    Route::post('/update/{id}', [CategoriesController::class, 'update']);
});

Route::prefix('expenses')->group(function (){
    Route::get('/',[ExpenseController::class,'index']);
    Route::post('/store', [ExpenseController::class, 'store']);
    Route::delete('/delete/{id}', [ExpenseController::class, 'destroy']);
    Route::post('/update/{id}', [ExpenseController::class, 'update']);
    Route::get('/day-between', [ExpenseController::class, 'filterBetweenTwoDates']);
    Route::get('/price-filter', [ExpenseController::class, 'filterMinMaxPrice']);
    Route::get('/category-filter', [ExpenseController::class, 'filterByCategory']);
    Route::get('/last-month', [ExpenseController::class, 'filterLastMonth']);
    Route::get('/last-year', [ExpenseController::class, 'lastYearExpenses']);
    Route::get('/sum-last-month', [ExpenseController::class, 'spentMoneyLastMonth']);
    Route::get('/sum-last-year', [ExpenseController::class, 'spentMoneyLastYear']);
    Route::get('/sum-last-week', [ExpenseController::class, 'spentMoneyLastWeek']);
});
