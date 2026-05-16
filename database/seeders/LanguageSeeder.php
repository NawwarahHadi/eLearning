<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $langs = [
            ['name' => 'Bahasa Melayu', 'code' => 'BM'],
            ['name' => 'English', 'code' => 'EN'],
            ['name' => 'Dual Language Program', 'code' => 'DLP'],
        ];
        foreach ($langs as $lang) {
            Language::firstOrCreate(['code' => $lang['code']], ['name' => $lang['name']]);
        }
    }
}
