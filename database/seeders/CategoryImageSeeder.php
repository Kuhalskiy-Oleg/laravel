<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CategoryImage;

class CategoryImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CategoryImage::factory()->count(10)->create();
    }
}
