<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'middle_name' => null,
            'email' => 'admin@pcu.edu.ph',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'department_id' => null,
            'is_active' => true,
            'password_changed_at' => now(),
        ]);
        $admin->adminProfile()->create([
            'phone' => '555-0101',
            'admin_department' => 'IT',
            'position' => 'System Administrator',
        ]);

        // Staff Users
        $staff_users = [
            [
                'first_name' => 'Maria',
                'last_name' => 'Santos',
                'email' => 'maria.santos@pcu.edu.ph',
                'staff_department' => 'Events',
                'position' => 'Events Coordinator',
            ],
            [
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'email' => 'juan.delacruz@pcu.edu.ph',
                'staff_department' => 'Communications',
                'position' => 'Communications Officer',
            ],
            [
                'first_name' => 'Rosa',
                'last_name' => 'Garcia',
                'email' => 'rosa.garcia@pcu.edu.ph',
                'staff_department' => 'Alumni Relations',
                'position' => 'Alumni Relations Officer',
            ],
        ];

        foreach ($staff_users as $staff_data) {
            $staff = User::create([
                'first_name' => $staff_data['first_name'],
                'last_name' => $staff_data['last_name'],
                'email' => $staff_data['email'],
                'password' => Hash::make('password'),
                'role' => 'staff',
                'department_id' => null,
                'is_active' => true,
                'password_changed_at' => now(),
            ]);
            $staff->staffProfile()->create([
                'phone' => '555-' . str_pad(rand(100, 999), 4, '0', STR_PAD_LEFT),
                'staff_department' => $staff_data['staff_department'],
                'position' => $staff_data['position'],
            ]);
        }

        // Partner Users
        $partners = [
            [
                'company_name' => 'Tech Solutions Inc.',
                'email' => 'contact@techsolutions.com',
                'industry' => 'Information Technology',
                'company_size' => '101-500',
            ],
            [
                'company_name' => 'Global Consulting Group',
                'email' => 'hr@globalconsulting.com',
                'industry' => 'Consulting',
                'company_size' => '51-100',
            ],
            [
                'company_name' => 'Finance Pro Services',
                'email' => 'recruitment@financepro.com',
                'industry' => 'Finance',
                'company_size' => '201-500',
            ],
            [
                'company_name' => 'Marketing Experts Ltd.',
                'email' => 'careers@marketingexperts.com',
                'industry' => 'Marketing',
                'company_size' => '11-50',
            ],
        ];

        foreach ($partners as $partner_data) {
            $partner = User::create([
                'first_name' => 'Company',
                'last_name' => explode(' ', $partner_data['company_name'])[0],
                'email' => $partner_data['email'],
                'password' => Hash::make('password'),
                'role' => 'partner',
                'department_id' => null,
                'is_active' => true,
                'password_changed_at' => now(),
            ]);
            $partner->partnerProfile()->create([
                'company_name' => $partner_data['company_name'],
                'industry' => $partner_data['industry'],
                'company_size' => $partner_data['company_size'],
                'description' => "Leading company in {$partner_data['industry']}",
                'contact_person' => 'John Manager',
                'contact_title' => 'HR Manager',
                'phone' => '555-' . str_pad(rand(100, 999), 4, '0', STR_PAD_LEFT),
                'website' => 'https://' . str_replace(' ', '', strtolower($partner_data['company_name'])) . '.com',
            ]);
        }

        // Student Users (5 per department)
        $departments = Department::all();
        $student_count = 0;

        foreach ($departments as $dept) {
            for ($i = 1; $i <= 5; $i++) {
                $student_count++;
                $student = User::create([
                    'first_name' => fake()->firstName(),
                    'last_name' => fake()->lastName(),
                    'middle_name' => fake()->firstName(),
                    'email' => "student{$student_count}@pcu.edu.ph",
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'department_id' => $dept->id,
                    'is_active' => true,
                    'password_changed_at' => now(),
                ]);

                $student->studentProfile()->create([
                    'student_id' => sprintf('2024-%05d', $student_count),
                    'headline' => fake()->sentence(4),
                    'personal_email' => fake()->email(),
                    'phone' => '555-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                    'address' => fake()->address(),
                    'date_of_birth' => fake()->dateTimeBetween('-25 years', '-18 years')->format('Y-m-d'),
                    'gender' => fake()->randomElement(['male', 'female']),
                    'technical_skills' => json_encode(['PHP', 'Laravel', 'JavaScript', 'MySQL']),
                    'soft_skills' => 'Leadership, Communication, Teamwork',
                ]);
            }
        }

        // Alumni Users (10 total)
        for ($i = 1; $i <= 10; $i++) {
            $alumni = User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => "alumni{$i}@pcu.edu.ph",
                'password' => Hash::make('password'),
                'role' => 'alumni',
                'department_id' => null,
                'is_active' => true,
                'password_changed_at' => now(),
            ]);

            $alumni->alumniProfile()->create([
                'headline' => fake()->jobTitle(),
                'phone' => '555-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'current_location' => fake()->city() . ', Philippines',
                'professional_summary' => fake()->paragraph(3),
                'degree_program' => 'Bachelor of Science in Computer Science',
                'graduation_year' => fake()->numberBetween(2015, 2023),
                'gwa' => fake()->randomFloat(2, 2.5, 4.0),
                'honors' => fake()->randomElement(['Cum Laude', 'Magna Cum Laude', 'Summa Cum Laude', null]),
                'organizations' => 'IEEE, ACM, Red Cross',
                'technical_skills' => json_encode(['Python', 'Java', 'React', 'AWS']),
            ]);
        }

        echo "✓ Users seeded (3 staff, 4 partners, " . count($departments) * 5 . " students, 10 alumni)\n";
    }
}
