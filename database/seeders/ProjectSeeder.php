<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'title'       => 'Dausyaf Portfolio',
                'slug'        => 'dausyaf-portfolio',
                'description' => 'Personal portfolio website built with Laravel 13 and React. Features REST API, JWT authentication, and Docker deployment.',
                'tech_stack'  => ['Laravel', 'React', 'MySQL', 'Redis', 'Docker'],
                'github_url'  => 'https://github.com/dausyaf/dausyaf-backend',
                'live_url'    => 'https://dausyaf.dev',
                'status'      => 'active',
                'is_featured' => true,
            ],
            [
                'title'       => 'REST API Boilerplate',
                'slug'        => 'rest-api-boilerplate',
                'description' => 'Production-ready Laravel REST API boilerplate with Sanctum auth, role management, and standardized response format.',
                'tech_stack'  => ['Laravel', 'Sanctum', 'MySQL', 'Docker'],
                'github_url'  => 'https://github.com/dausyaf/api-boilerplate',
                'live_url'    => null,
                'status'      => 'active',
                'is_featured' => true,
            ],
            [
                'title'       => 'Task Management App',
                'slug'        => 'task-management-app',
                'description' => 'Simple task management application with team collaboration features.',
                'tech_stack'  => ['Laravel', 'Vue.js', 'MySQL'],
                'github_url'  => 'https://github.com/dausyaf/task-app',
                'live_url'    => null,
                'status'      => 'archived',
                'is_featured' => false,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
