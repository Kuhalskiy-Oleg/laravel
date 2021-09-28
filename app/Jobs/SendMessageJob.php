<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMessageMail;
use Illuminate\Support\Facades\Storage;

class SendMessageJob implements ShouldQueue
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
     * путь до мини-копии файла
     *
     * @var string
     */
    protected $file_path_mini;

    /**
     * путь до загруженного файла (не мини-копия)
     *
     * @var string
     */
    protected $file_path;

    /**
     * название загруженного файла (не мини-копия)
     *
     * @var string
     */
    protected $file_name;

    /**
     * название мини-копии файла 
     *
     * @var string
     */
    protected $file_mini_name;


    /**
     * Создать новый экземпляр задания.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->file_mini_name = $data['file_mini_name'];
        $this->file_name = $data['file_name'];
        $this->file_path = $data['file_path'];
        $this->file_path_mini = $data['file_path_mini']; 
    }


    /**
     * Выполнить задание.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to('kuhalskiy@echo-company.ru')->send(new SendMessageMail([

            // полуаем мини-копию загруженного файла
            'file_mini_copy' => Storage::disk('media')->get($this->file_path_mini),

            // получаем url адрес для ссылки на загруженный файл (не мини-копия)
            'file_url' => Storage::disk('media')->url($this->file_path),

            // получаем размер мини-копии файла
            'file_mini_size' => Storage::disk('media')->size($this->file_path_mini),

            // получаем размер загруженного файла (не мини-копия)
            'file_size' => Storage::disk('media')->size($this->file_path),

            'file_mini_name' => $this->file_mini_name,
            'file_name' => $this->file_name,
        ]));

        //если письмо отправлено
        if (! Mail::failures()) {

            // info - записывает информацию в лог который доступен в папке storage -> logs -> larawel.log
            info([
                'job' => __CLASS__,
                'status_send_message' => 'The email was sent successfully'
            ]);
        }
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
            'job' => __CLASS__,
            'status_send_message' => 'Mail not send !!!',  
        ]);
    }
}
