<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Database\Seeder;

class EventRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = Event::where('status', 'published')->get();
        $students = User::where('role', 'student')->get();
        $alumni = User::where('role', 'alumni')->get();

        // Register students for events
        foreach ($events as $event) {
            $registrants = $students->random(fake()->numberBetween(10, 30));

            foreach ($registrants as $registrant) {
                // Avoid duplicate registrations
                if (EventRegistration::where('event_id', $event->id)
                    ->where('user_id', $registrant->id)
                    ->exists()) {
                    continue;
                }

                EventRegistration::create([
                    'event_id' => $event->id,
                    'user_id' => $registrant->id,
                    'user_type' => 'student',
                    'registration_type' => 'online',
                    'attendance_status' => fake()->randomElement(['registered', 'attended', 'no_show']),
                    'checked_in_at' => fake()->boolean(60) ? now()->subDays(fake()->numberBetween(0, 5)) : null,
                ]);
            }
        }

        // Register alumni for events targeting alumni
        foreach ($events->where('target_audience', '!=', 'allstudents') as $event) {
            $registrants = $alumni->random(fake()->numberBetween(5, 15));

            foreach ($registrants as $registrant) {
                // Avoid duplicate registrations
                if (EventRegistration::where('event_id', $event->id)
                    ->where('user_id', $registrant->id)
                    ->exists()) {
                    continue;
                }

                EventRegistration::create([
                    'event_id' => $event->id,
                    'user_id' => $registrant->id,
                    'user_type' => 'alumni',
                    'registration_type' => 'online',
                    'attendance_status' => fake()->randomElement(['registered', 'attended', 'no_show']),
                    'checked_in_at' => fake()->boolean(60) ? now()->subDays(fake()->numberBetween(0, 5)) : null,
                ]);
            }
        }

        echo "✓ Event registrations seeded\n";
    }
}
