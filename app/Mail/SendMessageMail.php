<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * ссылка на загруженый файл (не мини-копия)
     *
     * @var string
     */
    protected $file_url;

    /**
     * название загруженного файла (не мини-копия)
     *
     * @var string
     */
    protected $file_name;

    /**
     * тело мини-копии файла
     *
     * @var string
     */
    protected $file_mini_copy;

    /**
     * размер загруженного файла (не мини-копия)
     *
     * @var string
     */
    protected $file_size;

    /**
     * размер мини-копии файла 
     *
     * @var string
     */
    protected $file_mini_size;

    /**
     * название мини-копии файла 
     *
     * @var string
     */
    protected $file_mini_name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {       
        $this->file_mini_size = $data['file_mini_size'];
        $this->file_url = $data['file_url'];
        $this->file_size = $data['file_size'];
        $this->file_name = $data['file_name']; 
        $this->file_mini_copy = $data['file_mini_copy'];  
        $this->file_mini_name = $data['file_mini_name'];           
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.sendMessageJob')
        ->with([
            'file_mini_copy' => $this->file_mini_copy,
            'file_name' => $this->file_name,
            'file_url' => $this->file_url,
            'file_size' => $this->file_size,
            'file_mini_size' => $this->file_mini_size,
            'file_mini_name' => $this->file_mini_name,
        ])
        ->attachData($this->file_mini_copy, $this->file_name, [
            
            'mime' => 'image/jpeg',
        ]);
    }
}
