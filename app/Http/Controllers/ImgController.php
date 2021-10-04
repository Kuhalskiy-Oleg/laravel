<?php

namespace App\Http\Controllers;

use App\Models\Img;
use App\Jobs\AddImgJob;
use App\Jobs\SendMessageJob;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UploadImgFormRequest;

class ImgController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function uploadImg(UploadImgFormRequest $request)
    {
        // проверяем есть ли в запросе файл
        if ($request->hasFile('image')) {

            // записываем в переменную объект файла
            $file_request = $request->file('image');

            // записываем имя файла
            $file_name = $request->file('image')->getClientOriginalName();

            // создаем запись в бд
            $new_images_db = Img::create([
                'category_img' => ((string)rand(1, 10)),
                'disk' => 'media',
            ]);

            // из таблицы imgs получаем id картинки которую пытаемся загрузить
            $id_new_images_db = $new_images_db->id;

            // загружаем картинку на сервер и делаем запись в бд в таблицы meadia
            $file_uploaded = $new_images_db->addMedia($file_request)->toMediaCollection('images');

            // создаем путь к закачанному файлу. по этому пути можно будет сделать копию картинки и взять файл с диска media чтобы   сделать редактирование картинки
            $file_path = (string)$file_uploaded->id . '/' . $file_uploaded->file_name;

            // создаем уникальное имя копии файла
            $file_mini_name = 'mini_' . time() . '.' . pathinfo($file_uploaded->file_name, PATHINFO_EXTENSION);

            // создаем путь и название для копирования файла.
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
                    'file_path' => $file_path,
                    'file_name' => $file_name,
                    'file_path_mini' => $file_path_mini,
                    'file_mini_name' => $file_mini_name,
                ])
            ])
                ->dispatch([
                    'file_path' => $file_path,
                    'file_path_mini' => $file_path_mini,
                    'file_mini_name' => $file_mini_name,
                    'id_new_images_db' => $id_new_images_db,
                ]);

            // test commit 3
            return redirect()->route('index')->with([
                'fileName' => $file_uploaded->file_name
            ]);
        }
    }
}
