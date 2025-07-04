<?php

namespace Database\Seeders;

use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 50 normal published posts
        Post::factory()->count(50)->create();

        // 5 scheduled posts (with future published_at dates)
        Post::factory()->count(5)->create([
            'scheduled_at' => Carbon::now()->addMinutes(2),
            'published_at' => null,
        ]);

        // 5 scheduled posts (with future published_at dates)
        Post::factory()->count(5)->create([
            'scheduled_at' => Carbon::now()->addMinutes(5),
            'published_at' => null,
          
        ]);
    }
}
