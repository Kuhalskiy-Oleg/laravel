<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Http\Resources\SubscriberResource;
use App\Http\Requests\PaginationFormRequest;

class ApiSubscriberController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/subscribers?page={Number page}&per_page={Select count elements}",
     *     summary="Get list subscribers",
     *     description="Get a list of subscribers with pagination",
     *     tags={"Subscriber"},
     *     @OA\Parameter(
     *         name="Number page",
     *         in="path",
     *         description="Number page",
     *         required=true,  
     *         @OA\Schema(
     *              type="integer",
     *              required={"Number page"},
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
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(),
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/definitions/Subscriber")
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
        // per_page - для выбора пользователем сколько будет отображаться на одной странице элементов из массива
        $per_page = $request->validated()['per_page'] ??  5;

        $subscribers = Subscriber::paginate($per_page);

        return SubscriberResource::collection($subscribers);
    }



    /**
     * @OA\Get(
     *     path="/api/subscribers/{id}",
     *     summary="Get image information",
     *     description="Get subscriber information by id",
     *     tags={"Subscriber"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Subscriber id",
     *         required=true,
     *         @OA\Schema(
     *              type="integer",
     *              required={"id"},
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(ref="#/definitions/Subscriber"),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not Found",
     *     ),
     * )
     *
     * Display the specified resource.
     *
     * @param  array  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function show(Subscriber $subscriber)
    {
        SubscriberResource::withoutWrapping();

        return new SubscriberResource($subscriber);
    }

}
