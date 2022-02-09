<?php

namespace App\Http\Controllers\Categories;

use App\Categories\Category;
use App\Categories\Commands\AddCategoryCommand;
use App\Categories\Commands\DeleteCategoryCommand;
use App\Categories\Commands\EditCategoryCommand;
use App\Categories\Repositories\Contracts\CategoryRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CategoriesController extends Controller
{
    /**
     * Get List Categories
     * @OA\Get (
     *     path="/api/category/",
     *     tags={"Categories"},
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

    public function index()
    {
        return response()->json([
            'All Categories'=>Category::all()
        ]);
    }

    /**
     * Create Categories
     * @OA\Post (
     *     path="/api/category/store",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                      type="object",
     *                      @OA\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "name":"example name",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="name"),
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

    public function store(Request $request,CategoryRepositoryInterface $categoryRepository)
    {
        try{
            $this->validate($request, [
                'name' => 'required|string',
            ]);
            $data = $request->all();
            dispatch_now(new AddCategoryCommand($data));

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'error' => false,
            'categories' => 'You successfully added category'
        ]);
    }

    /**
     * Delete Categories
     * @OA\Delete (
     *     path="/api/category/delete/{id}",
     *     tags={"Categories"},
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
        try{
            dispatch_now(new DeleteCategoryCommand($id));

            return response()->json([
                'error'=>false,
                'You Successfully deleted the category'
            ]);

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update Categories
     * @OA\Post (
     *     path="/api/category/update/{id}",
     *     tags={"Categories"},
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
     *                          property="name",
     *                          type="string"
     *                      ),
     *                 ),
     *                 example={
     *                     "name":"example name",
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="name", type="string", example="name"),
     *              @OA\Property(property="updated_at", type="string", example="2021-12-11T09:25:53.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2021-12-11T09:25:53.000000Z")
     *          )
     *      )
     * )
     */

    public function update(Request $request, $id)
    {
        try{
            dispatch_now(new EditCategoryCommand($id, $request));

        }catch(\Exception $e){
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'error'=>false,
            'You Successfully updated the category'
        ]);

    }
}
