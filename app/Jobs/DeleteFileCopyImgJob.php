<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DeleteFileCopyImgJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * путь и имя для удаления мини копии файла
     *
     * @var int
     */
    protected $file_path_mini;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->file_path_mini = $data['file_path_mini'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Storage::disk('media')->delete($this->file_path_mini);
    }
}
