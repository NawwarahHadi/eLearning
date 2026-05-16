<?php

namespace Database\Seeders;

use App\Models\EducationLevel;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    public function run(): void
    {
        $levels = [
            ['name' => 'PhD',     'weight' => 40],
            ['name' => 'Master',  'weight' => 30],
            ['name' => 'Degree',  'weight' => 20],
            ['name' => 'Diploma', 'weight' => 10],
        ];

        foreach ($levels as $level) {
            EducationLevel::firstOrCreate(
                ['name' => $level['name']],
                ['weight' => $level['weight']]
            );
        }
    }
}
