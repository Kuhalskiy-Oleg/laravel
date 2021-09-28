<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CategoryImage;
use App\Http\Resources\CategoryImageResource;
use App\Http\Requests\PaginationFormRequest;

class ApiCategoryImageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories?page={Number page}&per_page={Select count elements}",
     *     summary="Get list categories",
     *     description="Get a list of categories with pagination",
     *     tags={"CategoryImage"},
     *     @OA\Parameter(
     *         name="Number page",
     *         in="path",
     *         description="Number page",
     *         required=true,  
     *         @OA\Schema(
     *              type="integer",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         name="Select count elements",
     *         in="path",
     *         description="Select count elements",
     *         required=true,  
     *         @OA\Schema(
     *              type="integer",
     *              required={"Select count elements"},
     *         ),
     *     ),
     *     @OA\Response(
     *         @OA\JsonContent(),
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/definitions/CategoryImage")
     *         ),
     *     ),
     *     @OA\Response(
     *         @OA\JsonContent(),
     *         response="404",
     *         description="Not Found",
     *     ),
     * )
     *
     * Отображение списка ресурсов с пагинацией.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaginationFormRequest $request)
    {
        $per_page = $request->validated()['per_page'] ??  5;

        $categories = CategoryImage::paginate($per_page);

        return CategoryImageResource::collection($categories);
    }



    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Get category information",
     *     description="Get category information by id",
     *     tags={"CategoryImage"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="CategoryImage id",
     *         required=true,
     *         @OA\Schema(
     *              type="integer",
     *              required={"id"},
     *         )
     *     ),
     *     @OA\Response(
     *         @OA\JsonContent(),
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/definitions/CategoryImage")
     *         ),
     *     ),
     *     @OA\Response(
     *         @OA\JsonContent(),
     *         response="404",
     *         description="Not Found",
     *     ),
     * )
     *
     * Показать выбранную категорию
     *
     * @param  array  $category
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryImage $category)
    {
        CategoryImageResource::withoutWrapping();

        return new CategoryImageResource($category);
    }

}
