<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Backend
            ['name' => 'Laravel',    'category' => 'backend',  'level' => 5, 'order' => 1, 'is_visible' => true],
            ['name' => 'PHP',        'category' => 'backend',  'level' => 4, 'order' => 2, 'is_visible' => true],
            ['name' => 'MySQL',      'category' => 'backend',  'level' => 4, 'order' => 3, 'is_visible' => true],
            ['name' => 'Redis',      'category' => 'backend',  'level' => 3, 'order' => 4, 'is_visible' => true],
            ['name' => 'REST API',   'category' => 'backend',  'level' => 4, 'order' => 5, 'is_visible' => true],

            // Frontend
            ['name' => 'React',      'category' => 'frontend', 'level' => 3, 'order' => 6,  'is_visible' => true],
            ['name' => 'JavaScript', 'category' => 'frontend', 'level' => 3, 'order' => 7,  'is_visible' => true],
            ['name' => 'HTML & CSS', 'category' => 'frontend', 'level' => 4, 'order' => 8,  'is_visible' => true],

            // DevOps
            ['name' => 'Docker',     'category' => 'devops',   'level' => 3, 'order' => 9,  'is_visible' => true],
            ['name' => 'Git',        'category' => 'devops',   'level' => 4, 'order' => 10, 'is_visible' => true],
            ['name' => 'GitHub Actions', 'category' => 'devops', 'level' => 2, 'order' => 11, 'is_visible' => true],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
