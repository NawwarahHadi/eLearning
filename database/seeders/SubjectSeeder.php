<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $list = ['Additional Mathematics', 'Physics', 'Chemistry', 'Biology', 'Mathematics', 'English'];

        foreach ($list as $sub) {
            Subject::firstOrCreate([
                'name' => $sub,
                'slug' => Str::slug($sub)
            ]);
        }
    }
}
