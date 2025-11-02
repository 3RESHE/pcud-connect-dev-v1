<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = User::where('role', 'staff')->get();
        $admin = User::where('role', 'admin')->first();

        $events_data = [
            [
                'title' => 'Career Fair 2024',
                'description' => 'Annual career fair bringing together students and top companies in the region.',
                'event_format' => 'inperson',
                'venue_name' => 'PCUD Main Auditorium',
                'venue_capacity' => 500,
                'target_audience' => 'allstudents',
            ],
            [
                'title' => 'Web Development Workshop',
                'description' => 'Learn the latest technologies in web development from industry experts.',
                'event_format' => 'hybrid',
                'venue_name' => 'Computer Science Lab',
                'platform' => 'Zoom',
                'target_audience' => 'allstudents',
            ],
            [
                'title' => 'Alumni Networking Breakfast',
                'description' => 'Connect with successful alumni and explore career opportunities.',
                'event_format' => 'inperson',
                'venue_name' => 'PCUD Conference Hall',
                'venue_capacity' => 200,
                'target_audience' => 'alumni',
            ],
            [
                'title' => 'Tech Talk: AI and Machine Learning',
                'description' => 'Webinar featuring leading AI researchers and practitioners.',
                'event_format' => 'virtual',
                'platform' => 'Microsoft Teams',
                'virtual_capacity' => 1000,
                'target_audience' => 'openforall',
            ],
            [
                'title' => 'Hackathon 2024',
                'description' => 'Code for a cause! 24-hour hackathon with amazing prizes.',
                'event_format' => 'inperson',
                'venue_name' => 'Engineering Building',
                'venue_capacity' => 300,
                'target_audience' => 'allstudents',
            ],
            [
                'title' => 'Professional Development Seminar',
                'description' => 'Skills workshop for career advancement and professional growth.',
                'event_format' => 'hybrid',
                'venue_name' => 'Seminar Room A',
                'platform' => 'Google Meet',
                'target_audience' => 'alumni',
            ],
        ];

        foreach ($staff as $staff_member) {
            for ($i = 0; $i < 3; $i++) {
                $event_data = $events_data[($staff->indexOf($staff_member) * 3 + $i) % count($events_data)];

                Event::create([
                    'created_by' => $staff_member->id,
                    'approved_by' => $admin->id,
                    'title' => $event_data['title'],
                    'description' => $event_data['description'],
                    'event_format' => $event_data['event_format'],
                    'event_date' => now()->addDays(fake()->numberBetween(7, 60)),
                    'is_multiday' => false,
                    'start_time' => now()->addHours(fake()->numberBetween(8, 18))->format('H:i:s'),
                    'end_time' => now()->addHours(fake()->numberBetween(10, 20))->format('H:i:s'),
                    'venue_name' => $event_data['venue_name'] ?? null,
                    'venue_capacity' => $event_data['venue_capacity'] ?? null,
                    'platform' => $event_data['platform'] ?? null,
                    'virtual_capacity' => $event_data['virtual_capacity'] ?? null,
                    'registration_required' => true,
                    'registration_deadline' => now()->addDays(fake()->numberBetween(3, 30)),
                    'target_audience' => $event_data['target_audience'],
                    'contact_person' => $staff_member->full_name,
                    'contact_email' => $staff_member->email,
                    'contact_phone' => '555-0100',
                    'status' => 'published',
                    'published_at' => now(),
                ]);
            }
        }

        echo "✓ Events seeded\n";
    }
}
