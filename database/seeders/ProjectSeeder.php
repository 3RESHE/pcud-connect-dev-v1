<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $alumni = User::where('role', 'alumni')->get();

        // Add projects to students
        foreach ($students->take(30) as $student) {
            for ($i = 0; $i < fake()->numberBetween(1, 3); $i++) {
                Project::create([
                    'user_id' => $student->id,
                    'user_type' => 'student',
                    'title' => fake()->sentence(3),
                    'url' => fake()->randomElement([
                        'https://github.com/user/project',
                        'https://portfolio.example.com/project',
                        null,
                    ]),
                    'start_date' => now()->subMonths(fake()->numberBetween(3, 12)),
                    'end_date' => now()->subMonths(fake()->numberBetween(0, 6)),
                    'description' => fake()->paragraph(2),
                ]);
            }
        }

        // Add projects to alumni
        foreach ($alumni as $alum) {
            for ($i = 0; $i < fake()->numberBetween(2, 4); $i++) {
                Project::create([
                    'user_id' => $alum->id,
                    'user_type' => 'alumni',
                    'title' => fake()->sentence(3),
                    'url' => fake()->url(),
                    'start_date' => now()->subYears(fake()->numberBetween(1, 5)),
                    'end_date' => now()->subYears(fake()->numberBetween(0, 4)),
                    'description' => fake()->paragraph(2),
                ]);
            }
        }

        echo "✓ Projects seeded\n";
    }
}
