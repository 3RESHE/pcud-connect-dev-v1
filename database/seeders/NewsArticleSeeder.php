<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\NewsArticle;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class NewsArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $staff = User::where('role', 'staff')->get();
        $admin = User::where('role', 'admin')->first();

        $articles_data = [
            [
                'title' => 'PCUD Student Wins National Programming Championship',
                'category' => 'alumni_success',
                'is_featured' => true,
            ],
            [
                'title' => 'New Research Center Launches at PCUD',
                'category' => 'university_updates',
                'is_featured' => true,
            ],
            [
                'title' => 'Partnership Signed with Tech Giants',
                'category' => 'partnership_highlights',
                'is_featured' => false,
            ],
            [
                'title' => 'Annual Convocation and Awards Night Celebrates Excellence',
                'category' => 'campus_events',
                'is_featured' => false,
            ],
            [
                'title' => 'Alumni Success Story: From Student to CEO',
                'category' => 'alumni_success',
                'is_featured' => true,
            ],
            [
                'title' => 'PCUD Introduces New Scholarship Program',
                'category' => 'university_updates',
                'is_featured' => false,
            ],
            [
                'title' => 'Community Outreach: PCUD Volunteers Make a Difference',
                'category' => 'campus_events',
                'is_featured' => false,
            ],
            [
                'title' => 'Tech Industry Leaders Share Insights at PCUD',
                'category' => 'partnership_highlights',
                'is_featured' => true,
            ],
        ];

        foreach ($staff as $staff_member) {
            for ($i = 0; $i < 4; $i++) {
                $article_data = $articles_data[($staff->indexOf($staff_member) * 4 + $i) % count($articles_data)];

                $title = $article_data['title'];

                NewsArticle::create([
                    'created_by' => $staff_member->id,
                    'approved_by' => $admin->id,
                    'title' => $title,
                    'slug' => Str::slug($title) . '-' . now()->timestamp,
                    'content' => fake()->paragraphs(5, true),
                    'category' => $article_data['category'],
                    'is_featured' => $article_data['is_featured'],
                    'status' => 'published',
                    'published_at' => now()->subDays(fake()->numberBetween(1, 30)),
                    'views_count' => fake()->numberBetween(10, 500),
                ]);
            }
        }

        echo "✓ News articles seeded\n";
    }
}
