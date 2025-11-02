<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::where('role', 'student')->get();
        $alumni = User::where('role', 'alumni')->get();

        $experience_types = ['parttime', 'internship', 'volunteer', 'organization', 'competition'];

        // Add experiences to students
        foreach ($students->take(20) as $student) {
            for ($i = 0; $i < fake()->numberBetween(1, 3); $i++) {
                Experience::create([
                    'user_id' => $student->id,
                    'user_type' => 'student',
                    'role_position' => fake()->jobTitle(),
                    'organization' => fake()->company(),
                    'start_date' => now()->subMonths(fake()->numberBetween(3, 12)),
                    'end_date' => now()->subMonths(fake()->numberBetween(0, 3)),
                    'is_current' => fake()->boolean(20),
                    'experience_type' => $experience_types[array_rand($experience_types)],
                    'description' => fake()->paragraph(2),
                ]);
            }
        }

        // Add experiences to alumni
        foreach ($alumni as $alum) {
            for ($i = 0; $i < fake()->numberBetween(2, 5); $i++) {
                Experience::create([
                    'user_id' => $alum->id,
                    'user_type' => 'alumni',
                    'role_position' => fake()->jobTitle(),
                    'organization' => fake()->company(),
                    'start_date' => now()->subYears(fake()->numberBetween(1, 8)),
                    'end_date' => now()->subMonths(fake()->numberBetween(0, 12)),
                    'is_current' => fake()->boolean(30),
                    'experience_type' => $experience_types[array_rand($experience_types)],
                    'description' => fake()->paragraph(2),
                ]);
            }
        }

        echo "✓ Experiences seeded\n";
    }
}
