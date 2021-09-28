<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImgsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imgs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_img')->constrained('category_images')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('disk', 50);
            $table->string('path_in_disk')->nullable();
            $table->string('directory')->nullable();
            $table->string('status', 50)->default('Не загружено');          
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
        Schema::dropIfExists('imgs');
    }
}
