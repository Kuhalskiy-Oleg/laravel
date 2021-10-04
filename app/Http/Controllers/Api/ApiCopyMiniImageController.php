<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CopyMiniImage;
use App\Http\Resources\CopyMiniImageResource;
use App\Http\Requests\PaginationFormRequest;

class ApiCopyMiniImageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/copy-images?page={Number page}&per_page={Select count elements}",
     *     summary="Get list copy-images",
     *     description="Get a list of copy-images with pagination",
     *     tags={"CopyMiniImage"},
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
     *             @OA\Items(ref="#/definitions/CopyMiniImage")
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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(PaginationFormRequest $request)
    {
        $per_page = $request->validated()['per_page'] ??  5;

        $copyMiniImages = CopyMiniImage::paginate($per_page);

        return CopyMiniImageResource::collection($copyMiniImages);
    }



    /**
     * @OA\Get(
     *     path="/api/copy-images/{id}",
     *     summary="Get copy-images information",
     *     description="Get copy-images information by id",
     *     tags={"CopyMiniImage"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="CopyMiniImage id",
     *         required=true,
     *         @OA\Schema(
     *              type="integer",
     *              required={"id"},
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         @OA\JsonContent(),
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/definitions/CopyMiniImage")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         @OA\JsonContent(),
     *         description="Not Found",
     *     ),
     * )
     *
     * Показать выбранную мини-копию изображения
     *
     * @param  int  $id
     * @return CopyMiniImageResource
     */
    public function show($id)
    {
        $copyMiniImage = CopyMiniImage::findOrFail($id);
        CopyMiniImageResource::withoutWrapping();

        return new CopyMiniImageResource($copyMiniImage);
    }


}
