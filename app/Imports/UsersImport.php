<?php

namespace App\Imports;

use App\Models\User;
use App\Models\StudentProfile;
use App\Mail\UserCredentialsMail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class UsersImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    private $uploadType;
    private $departmentId;
    private $importedCount = 0;

    public function __construct($uploadType, $departmentId = null)
    {
        $this->uploadType = $uploadType;
        $this->departmentId = $departmentId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Skip empty rows
                if (empty($row['email']) || empty($row['first_name']) || empty($row['last_name'])) {
                    continue;
                }

                // For students, student_id is required
                if ($this->uploadType === 'students' && empty($row['student_id'])) {
                    \Log::warning("Row skipped: student_id is required for students. Email: {$row['email']}");
                    continue;
                }

                // Skip if email already exists
                if (User::where('email', trim($row['email']))->exists()) {
                    \Log::info("User with email {$row['email']} already exists. Skipping.");
                    continue;
                }

                // Skip if student_id already exists (for students)
                if ($this->uploadType === 'students' && StudentProfile::where('student_id', trim($row['student_id']))->exists()) {
                    \Log::warning("Student ID {$row['student_id']} already exists. Skipping.");
                    continue;
                }

                // Generate temporary password
                $tempPassword = Str::random(12);

                // Determine role
                $role = $this->uploadType === 'students' ? 'student' : 'alumni';

                // Create user
                $user = User::create([
                    'first_name' => trim($row['first_name']),
                    'last_name' => trim($row['last_name']),
                    'middle_name' => isset($row['middle_name']) ? trim($row['middle_name']) : null,
                    'name_suffix' => isset($row['name_suffix']) ? trim($row['name_suffix']) : null,
                    'email' => trim($row['email']),
                    'password' => Hash::make($tempPassword),
                    'role' => $role,
                    'department_id' => $this->departmentId,
                    'is_active' => true,
                    'password_changed_at' => null,
                ]);

                // Create profile
                $this->createUserProfile($user, $row);

                // Send credentials email
                try {
                    Mail::to($user->email)->send(new UserCredentialsMail($user, $tempPassword));
                } catch (\Exception $e) {
                    \Log::error('Failed to send credentials email to ' . $user->email . ': ' . $e->getMessage());
                }

                $this->importedCount++;
            } catch (\Exception $e) {
                \Log::error('Failed to import user from row: ' . json_encode($row->toArray()) . ' Error: ' . $e->getMessage());
                continue;
            }
        }
    }

    /**
     * Get imported count
     */
    public function getImportedCount()
    {
        return $this->importedCount;
    }

    /**
     * Create user profile based on role
     */
    private function createUserProfile(User $user, $row): void
    {
        switch ($user->role) {
            case 'student':
                $user->studentProfile()->create([
                    'student_id' => trim($row['student_id']),
                ]);
                break;
            case 'alumni':
                $user->alumniProfile()->create([]);
                break;
        }
    }

    /**
     * Use chunk reading for large files
     */
    public function chunkSize(): int
    {
        return 100;
    }
}
