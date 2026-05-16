<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define your categories
        $list = ['Form 1', 'Form 2', 'Form 3', 'Form 4', 'Form 5'];

        foreach ($list as $cat) {
            Category::firstOrCreate([
                'name' => $cat,
                'code' => Str::slug($cat)
            ]);
        }
    }
}
