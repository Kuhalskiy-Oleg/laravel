<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use App\Models\Img;
use App\Models\CopyMiniImage;
use Exception;

class AddImgJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Количество раз, которое можно попробовать выполнить задачу.
     *
     * @var int
     */
    public $tries = 1;

    /**
     * Количество секунд, во время которых может выполняться задача до таймаута.
     *
     * @var int
     */
    public $timeout = 100;

    /**
     * Количество секунд ожидания перед повторной попыткой выполнения задания.
     *
     * @var int
     */
    public $backoff = 1;

    /**
     * id созданной записи при загрузке файла (таблица imgs) 
     *
     * @var string
     */
    protected $id_new_images_db;

    /**
     * путь и имя для создания мини копии файла
     *
     * @var string
     */
    protected $file_path_mini;

    /**
     * путь и имя для редактирования загруженной картинки
     *
     * @var string
     */
    protected $file_path;

    /**
     * название картинки мини-копии
     *
     * @var string
     */
    protected $file_mini_name;

    /**
     * создать новый экземпляр задания.
     *
     * @return void
     */
    public function __construct($data)
    {   
        $this->file_path = $data['file_path'];
        $this->file_path_mini = $data['file_path_mini']; 
        $this->file_mini_name = $data['file_mini_name'];
        $this->id_new_images_db = $data['id_new_images_db'];
    }

    /**
     * выполнить задание
     *
     * @return void
     */
    public function handle()
    {
        // объявление собственной ошибки для проверки сколька раз будут пытаться выполнится задание
        //throw new Exception('My error', 500); 
        
        // создаем копию файла для его изменения и пересохранения с изменениями
        Storage::disk('media')->copy($this->file_path,  $this->file_path_mini);


        //=====================================
        // ПРИСВАИВАЕМ СТАТУС РЕДАКТИРУЕТСЯ
        //=====================================

        $new_images_db = Img::findOrFail($this->id_new_images_db);
        $new_images_db->status = 'Редактируется в ' . __CLASS__;
        $new_images_db->save();




        //=====================================
        // СОЗДАЕМ ЗАПИСЬ В ТАБЛИЦЕ --КОПИИ ИЗОБРАЖЕНИЙ--
        //=====================================

        CopyMiniImage::create([
            'id_img' => $this->id_new_images_db,
            'title' => $this->file_mini_name,
            'disk' => 'media',
            'path_in_disk' => $this->file_path_mini,
        ]);




        //=====================================
        // РЕДАКТИРУЕМ ЗАГРУЖЕННУЮ КАРТИНКУ
        //=====================================

        // получаем дабавленную картинку 
        $file = Storage::disk('media')->get($this->file_path);

        // получаем абсолютный путь от скопированного файла
        $file_absolut_path = Storage::disk('media')->path($this->file_path); 

        // добавляем загруженную картинку в редактор
        $img = \Image::make($file);

        // блюр
        $img->blur(40);

        // водяной знак
        $img->insert('storage/app/public/watermark/watermark.png', 'bottom-right', -20, -80,  );
   
        // сохраняем файл
        $img->save($file_absolut_path); 



       
             






        //=====================================
        // РЕДАКТИРУЕМ СКОПИРОВАННУЮ КАРТИНКУ
        //=====================================

        // получаем копию картинки картинку 
        $file_copy = Storage::disk('media')->get($this->file_path_mini);
        
        // получаем абсолютный путь от скопированного файла
        $file_absolut_path_mini = Storage::disk('media')->path($this->file_path_mini); 

        // добавляем скопированную картинку в редактор
        $img = \Image::make($file_copy);
        
        // загружаем картинку водяного знака который будет накладываться на основную картнку
        $watermark = \Image::make(public_path('storage/watermark/watermark.png'))->opacity(50);
        
        // изменяем фото
        $img->resize(400, 300);

        // накладываем водяной знак
        $img->insert($watermark, 'center');

        // сохраняем файл
        $img->save($file_absolut_path_mini); 




        //=====================================
        // ПРИСВАИВАЕМ СТАТУС ГОТОВО
        //=====================================

        $new_images_db->status = 'Готово';
        $new_images_db->save();

        info([
            'job' => __CLASS__,
            'status' => 'succes',
            'result' => 'successfully editing the file and successfully adding a copy of the file'
        ]);
    
    }



    /**
     * Обработать провал задания.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        info([
            'job' => __CLASS__ ,
            'status' => 'error',
            'info' => 'failed to edit the file and add a copy of the file'
        ]);
    }


}
