<?php

namespace App\Http\Controllers\Expense;

use App\Categories\Category;
use App\Expense\Commands\AddExpenseCommand;
use App\Expense\Commands\DeleteExpenseCommand;
use App\Expense\Commands\EditExpenseCommand;
use App\Expense\Repositories\Contracts\ExpenseRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
{
    /**
     * Get List Categories
     * @OA\Get (
     *     path="/api/expenses/",
     *     tags={"Expenses"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="name",
     *                         type="string",
     *                         example="example name"
     *                     ),
     *                     @OA\Property(
     *                         property="amount",
     *                         type="string",
     *                         example="314"
     *                     ),
     *                    @OA\Property(
     *                         property="category_id",
     *                         type="string",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="description",
     *                         type="string",
     *                         example="description"
     *                     ),
     *                     @OA\Property(
     *                         property="category",
     *                         type="string",
     *                         example="category"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function index()
    {
        return response()->json([
            'All Expenses'=>Expense::all()
        ]);
    }


    /**
     * Create Categories
     * @OA\Post (
     *     path="/api/expenses/store",
     *     tags={"Expenses"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="user_id",
     *                          property="description",
     *                          property="amount",
     *                          property="category",
     *                          property="category_id",
     *                      ),
     *                 ),
     *                 example={
     *                     "description":"user_id",
     *                     "description":"description",
     *                     "amount":"amount",
     *                     "category":"category",
     *                     "category_id":"category_id",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="user_id", type="string", example="user_id"),
     *              @OA\Property(property="description", type="string", example="description"),
     *              @OA\Property(property="amount", type="integer", example="amount"),
     *              @OA\Property(property="category", type="integer", example="category"),
     *              @OA\Property(property="category_id", type="integer", example="category_id"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */

    public function store(Request $request,ExpenseRepositoryInterface $expenseRepository)
    {
        try{
            $request->validate([
                'amount'=>'required|int',
                'description'=>'string',
                'category_id'=>'string',
                'category'=>'string',
            ]);

            $user = auth('sanctum')->user();

            if($request->amount > $user->amount){
                return response()->json([
                    'message' => "You don't enough finances on your account"
                ]);
            }

            dispatch_now(new AddExpenseCommand());

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
                'error' => false,
                'message' => 'You Successfully added your expense'
            ]);
    }

    /**
     * Update Expense
     * @OA\Post (
     *     path="/api/expenses/update/{id}",
     *     tags={"Expenses"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="amount",
     *                          type="integer"
     *                      ),
     *                         @OA\Property(
     *                          property="description",
     *                          type="string"
     *                      ),
     *                           @OA\Property(
     *                          property="category",
     *                          type="string"
     *                      ),
     *                           @OA\Property(
     *                          property="category_id",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "amount":"2345",
     *                     "descriptin":"apple",
     *                     "category":"food",
     *                     "category_id":"1",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="amount", type="string", example="amount"),
     *              @OA\Property(property="descriptin", type="string", example="descriptin"),
     *              @OA\Property(property="category", type="string", example="category"),
     *              @OA\Property(property="category_id", type="string", example="category_id"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *          )
     *      )
     * )
     */


    public function update($id, Request $request)
    {
        try{
            $request->validate([
                'amount'=>'required|int',
                'category_id'=>'string',
                'category'=>'string',
                'description'=>'string',
            ]);

            $user = auth('sanctum')->user();

            if($request->amount > $user->amount){
                return response()->json([
                    'message' => "You don't enough finances on your account"
                ]);
            }

            dispatch_now(new EditExpenseCommand($id));

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'You Successfully added your expense'
        ]);
    }


    /**
     * Delete Expense
     * @OA\Delete (
     *     path="/api/expenses/delete/{id}",
     *     tags={"Expenses"},
     *     @OA\Parameter(
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="delete category success")
     *         )
     *     )
     * )
     */

    public function destroy($id)
    {
        try {
            dispatch_now(new DeleteExpenseCommand($id));

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'You Successfully deleted expense'
        ]);
    }

    /**
     * Expenses filter between two dates
     * @OA\Get (
     *     path="/api/expenses/day-between",
     *     tags={"Expenses"},
     *         @OA\Parameter(
     *         in="path",
     *         name="start_date",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *         @OA\Parameter(
     *         in="path",
     *         name="end_date",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="amount",
     *                         type="string",
     *                         example="345"
     *                     ),
     *                       @OA\Property(
     *                         property="description",
     *                         type="string",
     *                         example="gift"
     *                     ),
     *                        @OA\Property(
     *                         property="category",
     *                         type="string",
     *                         example="car"
     *                     ),
     *                        @OA\Property(
     *                         property="category_id",
     *                         type="string",
     *                         example="1"
     *                     ),
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function filterBetweenTwoDates(Request $request)
    {
        $startDate = Carbon::parse($request->start_date)->toDateTimeString();
        $endDate = Carbon::parse($request->end_date)->toDateTimeString();
        $expenses = Expense::whereBetween('created_at',[$startDate,$endDate])->get();

        return response()->json([
            'All Expenses between' . ' ' .$startDate . "and" . ' '. $endDate => $expenses
        ]);
    }

    /**
     * Expenses by Price min-max
     * @OA\Get (
     *     path="/api/expenses/price-filter",
     *     tags={"Expenses"},
     *         @OA\Parameter(
     *         in="path",
     *         name="min_amount",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *         @OA\Parameter(
     *         in="path",
     *         name="max_amount",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="amount",
     *                         type="string",
     *                         example="345"
     *                     ),
     *                       @OA\Property(
     *                         property="description",
     *                         type="string",
     *                         example="gift"
     *                     ),
     *                        @OA\Property(
     *                         property="category",
     *                         type="string",
     *                         example="car"
     *                     ),
     *                        @OA\Property(
     *                         property="category_id",
     *                         type="string",
     *                         example="1"
     *                     ),
     *                 )
     *             )
     *         )
     *     )
     * )
     */


    public function filterMinMaxPrice(Request $request)
    {
        try{
            if($request->min_amount && $request->max_amount){

                $minAmount = $request->min_amount;
                $maxAmount = $request->max_amount;
                $expense = Expense::whereBetween('amount',[$minAmount,$maxAmount])
                    ->orderBy('amount','asc')
                    ->get();
            }
            }catch(\Exception $e){
                return response()->json([
                    'error' => true,
                    'message' => $e->getMessage()
                ]);
            }

            return response()->json([
                'All Expanses between' . ' '. $minAmount . ' ' . 'and' . ' ' .  $maxAmount => $expense
            ]);
    }


    /**
     * Expenses Filtered by Category
     * @OA\Get (
     *     path="/api/expenses/category-filter",
     *     tags={"Expenses"},
     *      *     @OA\Parameter(
     *         in="path",
     *         name="category",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="amount",
     *                         type="string",
     *                         example="345"
     *                     ),
     *                       @OA\Property(
     *                         property="description",
     *                         type="string",
     *                         example="gift"
     *                     ),
     *                        @OA\Property(
     *                         property="category",
     *                         type="string",
     *                         example="car"
     *                     ),
     *                        @OA\Property(
     *                         property="category_id",
     *                         type="string",
     *                         example="1"
     *                     ),
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function filterByCategory(Request  $request)
    {
        try{
            if($request->category){
                $category = Category::where('name','LIKE','%'.$request->category)->first();
                $expense = Expense::where('category_id', 'LIKE', '%'.$category->id.'%')->get();
            }elseif($request->myCategory){
                $expense = Expense::where('category', 'LIKE', '%'.$request->myCategory.'%')->get();
            }

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'message' => 'Here is your all expenses for this category',
            'expense' =>  $expense
        ]);
    }

    /**
     * All Expenses Last Month
     * @OA\Get (
     *     path="/api/expenses/last-month",
     *     tags={"Expenses"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="amount",
     *                         type="string",
     *                         example="345"
     *                     ),
     *                       @OA\Property(
     *                         property="description",
     *                         type="string",
     *                         example="gift"
     *                     ),
     *                        @OA\Property(
     *                         property="category",
     *                         type="string",
     *                         example="car"
     *                     ),
     *                        @OA\Property(
     *                         property="category_id",
     *                         type="string",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function filterLastMonth()
    {
        try{
            $expense = Expense::whereMonth(
                'created_at', '=', Carbon::now()->subMonth()->month)->get();

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'message' => 'Here is your all expenses for last month',
            'expenses' =>  $expense
        ]);
    }


    /**
     * Sum Spent Last Month
     * @OA\Get (
     *     path="/api/expenses/sum-last-month",
     *     tags={"Expenses"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="amount",
     *                         type="string",
     *                         example="345"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function spentMoneyLastMonth()
    {
        try{
            $expenses = Expense::whereMonth(
                'created_at', '=', Carbon::now()->subMonth()->month)->get();

            $totalCost = 0;
            foreach($expenses as $expense){
                $totalCost += $expense->amount;
            }

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'message' => 'Your total expenses for last month',
            'Total Amount' =>  $totalCost
        ]);
    }

    /**
     * All Expenses Last Year
     * @OA\Get (
     *     path="/api/expenses/last-year",
     *     tags={"Expenses"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="amount",
     *                         type="string",
     *                         example="345"
     *                     ),
     *                       @OA\Property(
     *                         property="description",
     *                         type="string",
     *                         example="gift"
     *                     ),
     *                        @OA\Property(
     *                         property="category",
     *                         type="string",
     *                         example="car"
     *                     ),
     *                        @OA\Property(
     *                         property="category_id",
     *                         type="string",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function lastYearExpenses()
    {
        try{
            $expense = Expense::whereYear('created_at', now()->subYear()->year)
                ->get();

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
        return response()->json([
            'message' => 'Here is your all expenses for last year',
            'expenses' =>  $expense
        ]);
    }


    /**
     * Get Sum Spent Last Year
     * @OA\Get (
     *     path="/api/expenses/sum-last-year",
     *     tags={"Expenses"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="amount",
     *                         type="string",
     *                         example="345"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function spentMoneyLastYear()
    {
        try{
            $expenses = Expense::whereYear('created_at', now()->subYear()->year)
                ->get();

            $totalCost = 0;
            foreach($expenses as $expense){
                $totalCost += $expense->amount;
            }

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'message' => 'Your total expenses for last month',
            'Total Amount' =>  $totalCost
        ]);
    }


    /**
     * Get Sum Spent Last Week
     * @OA\Get (
     *     path="/api/expenses/sum-last-week",
     *     tags={"Expenses"},
     *     @OA\Response(
     *         response=200,
     *         description="success",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 type="array",
     *                 property="rows",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="_id",
     *                         type="number",
     *                         example="1"
     *                     ),
     *                     @OA\Property(
     *                         property="amount",
     *                         type="string",
     *                         example="345"
     *                     ),
     *                     @OA\Property(
     *                         property="updated_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     ),
     *                     @OA\Property(
     *                         property="created_at",
     *                         type="string",
     *                         example="2021-12-11T09:25:53.000000Z"
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function spentMoneyLastWeek()
    {
        try{
            $expenses = Expense::whereBetween('created_at',
                    [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]
                )
                ->get();

            $totalCost = 0;
            foreach($expenses as $expense){
                $totalCost += $expense->amount;
            }

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'message' => 'Your total expenses for last month',
            'Total Amount' =>  $totalCost
        ]);
    }

}
