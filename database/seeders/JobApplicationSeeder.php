<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\JobPosting;
use App\Models\JobApplication;
use Illuminate\Database\Seeder;

class JobApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobs = JobPosting::where('status', 'published')->get();
        $students = User::where('role', 'student')->get();
        $alumni = User::where('role', 'alumni')->get();

        // Students apply for jobs
        foreach ($jobs as $job) {
            $applicants = $students->random(fake()->numberBetween(3, 8));

            foreach ($applicants as $applicant) {
                // Avoid duplicate applications
                if (JobApplication::where('job_posting_id', $job->id)
                    ->where('applicant_id', $applicant->id)
                    ->exists()) {
                    continue;
                }

                JobApplication::create([
                    'job_posting_id' => $job->id,
                    'applicant_id' => $applicant->id,
                    'applicant_type' => 'student',
                    'cover_letter' => fake()->paragraph(3),
                    'resume_path' => 'resumes/student-' . $applicant->id . '.pdf',
                    'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
                    'reviewed_at' => fake()->boolean(70) ? now()->subDays(fake()->numberBetween(1, 10)) : null,
                ]);
            }
        }

        // Alumni apply for jobs
        foreach ($jobs as $job) {
            $applicants = $alumni->random(fake()->numberBetween(2, 5));

            foreach ($applicants as $applicant) {
                // Avoid duplicate applications
                if (JobApplication::where('job_posting_id', $job->id)
                    ->where('applicant_id', $applicant->id)
                    ->exists()) {
                    continue;
                }

                JobApplication::create([
                    'job_posting_id' => $job->id,
                    'applicant_id' => $applicant->id,
                    'applicant_type' => 'alumni',
                    'cover_letter' => fake()->paragraph(3),
                    'resume_path' => 'resumes/alumni-' . $applicant->id . '.pdf',
                    'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
                    'reviewed_at' => fake()->boolean(70) ? now()->subDays(fake()->numberBetween(1, 10)) : null,
                ]);
            }
        }

        echo "✓ Job applications seeded\n";
    }
}
