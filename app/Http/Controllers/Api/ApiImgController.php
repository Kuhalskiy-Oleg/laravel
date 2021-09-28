<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ImgResource;
use App\Models\Img;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Http\Requests\UploadImgFormRequest;
use App\Http\Requests\PaginationFormRequest;
use App\Jobs\AddImgJob;
use App\Jobs\SendMessageJob;
use App\Jobs\DeleteFileCopyImgJob;

class ApiImgController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/images?page={Number page}&per_page={Select count elements}",
     *     summary="Get list images",
     *     description="Get a list of images with pagination",
     *     tags={"Img"},
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
     *         @OA\JsonContent(),
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/definitions/Img")
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

        $images = Img::paginate($per_page);

        return ImgResource::collection($images);
    }





    /**
     * @OA\Post(
     *     path="/api/images",
     *     summary="Upload file images",
     *     description="Upload file images",
     *     tags={"Img"},
     *     @OA\RequestBody(
     *         required=true, 
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *                  @OA\Schema(
     *                       type="object",
     *                       required={"image"},
     *                       @OA\Property( 
     *                           property="image",
     *                           type="file",
     *                       ), 
     *                  ),
     *         ),
     *     ),                     
     *     @OA\Response(
     *         @OA\JsonContent(),
     *         response=200,
     *         description="successful operation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/definitions/Img")
     *         ),
     *     ),
     *     @OA\Response(
     *         @OA\JsonContent(),
     *         response="404",
     *         description="Not Found",
     *     ),
     *     @OA\Response(
     *         @OA\JsonContent(),
     *         response="422",
     *         description="Errors validation",
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/definitions/Img")
     *         ),
     *     ),
     * 
     * )
     *
     * Загрузка файла на сервер и отправление задачь в очередь
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [

            'image' => 'required|image|max:10240|mimes:jpg,jpeg,png|dimensions:min_width=700,min_height=500'
        ]);

        if ($validator->fails()) {
            
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // проверяем есть ли в запросе файл
        if ($request->hasFile('image')) {

            // записываем в переменную - объект файла
            $file_request = $request->file('image');
            
            // записываем имя файла 
            $file_name = $request->file('image')->getClientOriginalName();

            // записываем размер исходного файла
            $file_size = (string) $file_request->getSize();
            
            // создаем запись в бд 
            $new_images_db = Img::create([
                'category_img' => (string)rand(1,10),
                'disk' => 'media',
            ]);

            // из таблицы imgs получаем id картинки которую пытаемся загрузить
            $id_new_images_db = $new_images_db->id;

            // загружаем картинку на сервер и делаем запись в бд в таблицы meadia
            $file_uploaded = $new_images_db->addMedia($file_request)->toMediaCollection('images');

            // создаем путь к закачанному файлу. по этому пути можно будет сделать копию картинки и взять файл с диска media чтобы записать его в переменную что бы потом можно было редактировать этот файл в редакторе изображений 
            $file_path = (string)$file_uploaded->id . '/' . $file_uploaded->file_name;

            // создаем уникальное имя копии файла
            $file_mini_name = 'mini_' . time() . '.' . pathinfo($file_uploaded->file_name, PATHINFO_EXTENSION);

            // создаем путь и название для копирования файла. путь и название - для передачи во второй параметр метода copy: Storage::disk('media')->copy(old file, new file);
            $file_path_mini = (string)$file_uploaded->id . '/' . $file_mini_name;

        } else {

            return abort(404);
        }
        
        // проверяем есть ли файл который мы загрузили на диске media т.е в папке указанной в конфиге Filesystems
        if (Storage::disk('media')->exists($file_path)) {

            // обновляем запись в бд 
            $new_images_db->status = 'Загружено';
            $new_images_db->title = $file_uploaded->file_name;
            $new_images_db->path_in_disk = $file_path;
            $new_images_db->directory = (string)$file_uploaded->id;
            $new_images_db->save();

            AddImgJob::withChain([
                new SendMessageJob([
                    'file_path'=> $file_path,
                    'file_name' => $file_name,
                    'file_path_mini' => $file_path_mini,
                    'file_mini_name' => $file_mini_name,                  
                ])  
            ])
            ->dispatch([
                'file_path'=> $file_path,
                'file_path_mini' => $file_path_mini,
                'file_mini_name' => $file_mini_name,
                'id_new_images_db' => $id_new_images_db,
            ]);  

            return response()->json([
                'result' => 'succes upload file',         
                'request_getClientOriginalName' => $request->file('image')->getClientOriginalName(),
                'file_size' => $file_size,               
            ], 201);           
        }    
    }




    /**
     * @OA\Get(
     *     path="/api/images/{id}",
     *     summary="Get image information",
     *     description="Get image information by id",
     *     tags={"Img"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Img id",
     *         required=true,
     *         @OA\Schema(
     *              type="integer",
     *              required={"id"},
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(),
     *         @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/definitions/Img")
     *         ),
     *     ),
     *     @OA\Response(
     *         response="404",
     *         @OA\JsonContent(),
     *         description="Not Found",
     *     ),
     * )
     *
     * Показать выбранную картинку
     *
     * @param  array  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Img $image)
    {
        ImgResource::withoutWrapping();

        return new ImgResource($image);
    }


    /**
     * @OA\Delete(
     *      path="/api/images/{id}",
     *      tags={"Img"},
     *      summary="Delete existing image",
     *      description="Deletes an entry in the 'img', 'media', 'copy_mini_images' tables and deletes files from the server",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Img id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          @OA\JsonContent(),
     *          response=200,
     *          description="Successful operation",
     *          @OA\Schema(
     *             type="array",
     *             @OA\Items(ref="#/definitions/Img")
     *         ),
     *       ),
     *      @OA\Response(
     *          @OA\JsonContent(),
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     *
     * Удаляем все копии картинок, основную картинку, папку где они хранились, записи в таблицах: imgs, copy_mini_images, media
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // ищем нужную запись в таблице, если ее не будет то выведится ошибка 404
        $image = Img::findOrFail($id);

        $image_directory = $image->directory;

        $image_disk = $image->disk;

        $image_path_in_disk = $image->path_in_disk;


        // забираем все копии изображения
        $copy_images = $image->copyMiniImages;

        $info_list = [];

        // в цикле удаляем каждую копию изображений
        foreach ($copy_images as $copy_image) {
            
            if (Storage::disk($copy_image->disk)->exists($copy_image->path_in_disk)) {

                Storage::disk($copy_image->disk)->delete($copy_image->path_in_disk);

                $info_list[] = [
                    'delete copy-mini file ' => Storage::disk($copy_image->disk)->path($copy_image->path_in_disk),
                    'deleting a record from a table copy_mini_images ' => $copy_image->delete()
                ];
            }
        }

        // удаляем основную картинку, запись в таблице imgs и удаляется автоматом директория хранеия картинок
        if (Storage::disk($image->disk)->exists($image->path_in_disk)) {

            Storage::disk($image->disk)->delete($image->path_in_disk);

            $info_list[] = [
                'delete file ' => Storage::disk($image->disk)->path($image->path_in_disk),
                'deleting a record from a table imgs ' => $image->delete(),
            ];
        }

        // если путь к месту где хранилась картинка существует то удаляем директорию диска storage где хранились картинки  
        if (File::isDirectory(Storage::disk($image_disk)->path($image_directory))) {
           
            File::deleteDirectory(Storage::disk($image_disk)->path($image_directory));

            $info_list[] = [
                'directory' => 'deleted'
            ];

        // если не существует то записываем в переменную info_list информацию о том что директории не существует
        } else {
            $info_list[] = [
                'directory' =>  'not directory'
            ];
        }
        
        return response()->json([
            'status' => 200,
            'result' => 'succes delete',
            'info' => $info_list           
        ], 200);
    }
}
