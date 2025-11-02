<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Order matters! Departments first, then users, then everything else
        $this->call([
            DepartmentSeeder::class,
            UserSeeder::class,
            JobPostingSeeder::class,
            EventSeeder::class,
            NewsArticleSeeder::class,
            PartnershipSeeder::class,
        ]);

        // Optional: Uncomment for more seed data
        $this->call([
            ExperienceSeeder::class,
            ProjectSeeder::class,
            JobApplicationSeeder::class,
            EventRegistrationSeeder::class,
        ]);
    }
}
