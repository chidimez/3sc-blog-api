<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;


class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish-scheduled-posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish posts scheduled for now or earlier';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        // Fetch posts scheduled for publishing now or earlier
        $posts = Post::whereNotNull('scheduled_at')
            ->where('scheduled_at', '<=', $now)
            ->whereNull('published_at')
            ->get();

        // Fetch upcoming posts that are not yet due
        $pending = Post::whereNotNull('scheduled_at')
            ->where('scheduled_at', '>', $now)
            ->whereNull('published_at')
            ->orderBy('scheduled_at')
            ->get();

        if ($posts->isEmpty()) {
            $this->info('No scheduled posts to publish at this time.');
        } else {
            foreach ($posts as $post) {
                $post->published_at = $now;
                $post->save();
                $this->info("Published post ID {$post->id}: \"{$post->title}\" at {$now->toDateTimeString()}");
            }

            $this->info("Published {$posts->count()} scheduled post(s).");
        }

        if ($pending->isNotEmpty()) {
            $this->line('');
            $this->info('Upcoming scheduled posts:');
            foreach ($pending as $post) {
                $this->line("- ID {$post->id}: \"{$post->title}\" → scheduled for {$post->scheduled_at->toDateTimeString()}");
            }
        }

        return 0;
    }
}
