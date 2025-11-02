<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\JobPosting;
use Illuminate\Database\Seeder;

class JobPostingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = User::where('role', 'partner')->get();
        $admin = User::where('role', 'admin')->first();

        $jobs_data = [
            [
                'title' => 'Junior PHP Developer',
                'description' => 'We are looking for a junior PHP developer with 1-2 years of experience in Laravel framework.',
                'job_type' => 'fulltime',
                'experience_level' => 'entry',
                'work_setup' => 'remote',
                'salary_min' => 25000,
                'salary_max' => 35000,
                'salary_period' => 'monthly',
                'technical_skills' => ['PHP', 'Laravel', 'MySQL', 'Git'],
            ],
            [
                'title' => 'UI/UX Designer Intern',
                'description' => 'Join our design team as an intern and work on real-world projects.',
                'job_type' => 'internship',
                'experience_level' => 'student',
                'work_setup' => 'hybrid',
                'salary_min' => 0,
                'salary_max' => 0,
                'is_unpaid' => true,
                'technical_skills' => ['Figma', 'Adobe XD', 'Wireframing'],
            ],
            [
                'title' => 'Senior Java Developer',
                'description' => 'Seeking experienced Java developer for enterprise application development.',
                'job_type' => 'fulltime',
                'experience_level' => 'senior',
                'work_setup' => 'onsite',
                'salary_min' => 50000,
                'salary_max' => 70000,
                'salary_period' => 'monthly',
                'technical_skills' => ['Java', 'Spring Boot', 'PostgreSQL', 'Docker'],
            ],
            [
                'title' => 'Freelance Content Writer',
                'description' => 'Looking for creative writers to produce engaging content.',
                'job_type' => 'contract',
                'experience_level' => 'entry',
                'work_setup' => 'remote',
                'salary_min' => 500,
                'salary_max' => 1000,
                'salary_period' => 'project',
                'technical_skills' => ['Content Writing', 'SEO', 'Copy Writing'],
            ],
            [
                'title' => 'Data Science Analyst',
                'description' => 'Analyze big data and create insights for business decisions.',
                'job_type' => 'fulltime',
                'experience_level' => 'mid',
                'work_setup' => 'hybrid',
                'salary_min' => 40000,
                'salary_max' => 55000,
                'salary_period' => 'monthly',
                'technical_skills' => ['Python', 'R', 'SQL', 'Tableau'],
            ],
        ];

        $job_count = 0;
        foreach ($partners as $partner) {
            for ($i = 0; $i < 3; $i++) {
                $job_data = $jobs_data[$job_count % count($jobs_data)];
                $job_count++;

                JobPosting::create([
                    'partner_id' => $partner->id,
                    'approved_by' => $admin->id,
                    'title' => $job_data['title'],
                    'description' => $job_data['description'],
                    'job_type' => $job_data['job_type'],
                    'experience_level' => $job_data['experience_level'],
                    'work_setup' => $job_data['work_setup'],
                    'location' => $i % 2 === 0 ? 'Manila, Philippines' : 'Makati, Philippines',
                    'salary_min' => $job_data['salary_min'],
                    'salary_max' => $job_data['salary_max'],
                    'salary_period' => $job_data['salary_period'] ?? null,
                    'is_unpaid' => $job_data['is_unpaid'] ?? false,
                    'application_deadline' => now()->addDays(30),
                    'positions_available' => fake()->numberBetween(1, 3),
                    'technical_skills' => json_encode($job_data['technical_skills']),
                    'status' => 'published',
                    'published_at' => now(),
                ]);
            }
        }

        echo "✓ Job postings seeded\n";
    }
}
