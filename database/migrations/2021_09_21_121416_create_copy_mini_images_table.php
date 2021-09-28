<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCopyMiniImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('copy_mini_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_img')->constrained('imgs')->onDelete('cascade');
            $table->string('title');
            $table->string('disk', 100);
            $table->string('path_in_disk')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('copy_mini_images');
    }
}
