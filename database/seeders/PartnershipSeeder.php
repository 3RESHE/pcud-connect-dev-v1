<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Partnership;
use Illuminate\Database\Seeder;

class PartnershipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = User::where('role', 'partner')->get();
        $admin = User::where('role', 'admin')->first();

        $activities = [
            'feedingprogram' => 'Feeding Program for Underprivileged Children',
            'brigadaeskwela' => 'Brigada Eskwela - School Maintenance Project',
            'communitycleanup' => 'Community Cleanup Drive',
            'treeplanting' => 'Tree Planting and Reforestation',
            'donationdrive' => 'Relief Goods Donation Drive',
        ];

        foreach ($partners as $partner) {
            for ($i = 0; $i < 2; $i++) {
                $activity_type = array_rand($activities);
                $activity_title = $activities[$activity_type];

                Partnership::create([
                    'partner_id' => $partner->id,
                    'reviewed_by' => $admin->id,
                    'activity_type' => $activity_type,
                    'organization_name' => $partner->partnerProfile->company_name,
                    'organization_background' => $partner->partnerProfile->description,
                    'organization_website' => $partner->partnerProfile->website,
                    'organization_phone' => $partner->partnerProfile->phone,
                    'activity_title' => $activity_title,
                    'activity_description' => fake()->paragraph(3),
                    'activity_date' => now()->addDays(fake()->numberBetween(7, 60)),
                    'activity_time' => now()->addHours(fake()->numberBetween(8, 17))->format('H:i:s'),
                    'venue_address' => fake()->address(),
                    'activity_objectives' => fake()->paragraph(2),
                    'expected_impact' => fake()->paragraph(2),
                    'contact_name' => $partner->partnerProfile->contact_person,
                    'contact_position' => $partner->partnerProfile->contact_title,
                    'contact_email' => $partner->email,
                    'contact_phone' => $partner->partnerProfile->phone,
                    'status' => 'approved',
                    'reviewed_at' => now(),
                ]);
            }
        }

        echo "✓ Partnerships seeded\n";
    }
}
