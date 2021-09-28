<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SelectedCategory;

class SelectedCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SelectedCategory::factory()->count(2000)->create();
    }
}
