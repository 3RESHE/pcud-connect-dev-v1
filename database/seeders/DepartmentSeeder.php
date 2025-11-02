<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['title' => 'College of Engineering', 'code' => 'COE'],
            ['title' => 'College of Arts and Sciences', 'code' => 'CAS'],
            ['title' => 'College of Business and Accountancy', 'code' => 'CBA'],
            ['title' => 'College of Education', 'code' => 'COED'],
            ['title' => 'College of Health Sciences', 'code' => 'CHS'],
            ['title' => 'College of Computer Science and Information Technology', 'code' => 'CSIT'],
            ['title' => 'College of Architecture', 'code' => 'CA'],
            ['title' => 'College of Law', 'code' => 'CL'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        echo "✓ Departments seeded\n";
    }
}
