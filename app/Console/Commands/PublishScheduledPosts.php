<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';
    protected $description = 'Publish scheduled posts that are due';

    public function handle()
    {
        $posts = Post::scheduled()->get();
        
        foreach ($posts as $post) {
            if ($post->published_at <= now()) {
                $this->info("Publishing post: {$post->title}");
            }
        }
        
        $this->info('Scheduled posts check completed.');
    }
}