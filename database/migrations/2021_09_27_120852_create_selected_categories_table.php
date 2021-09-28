<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelectedCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selected_categories', function (Blueprint $table) {
            $table->id();
            // прописываем внешний ключ для того чтобы при удалении категории или подписчика данные удалялись в этой промежуточной таблицы
            $table->foreignId('category_images_id')->constrained('category_images')->onDelete('cascade');
            $table->foreignId('subscribers_id')->constrained('subscribers')->onDelete('cascade');
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
        Schema::dropIfExists('selected_categories');
    }
}
