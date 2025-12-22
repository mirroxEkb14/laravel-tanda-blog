<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BlogArticle;

/**
 * Command publishes blog articles that were scheduled for a future date (from
 * "scheduled" to "published" status once their publish_at timestamp has been reached)
 */
class PublishScheduledBlogArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled blog articles whose publish_at time has arrived';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = BlogArticle::query()
            ->where('status', 'scheduled')
            ->whereNotNull('publish_at')
            ->where('publish_at', '<=', now())
            ->update(['status' => 'published']);

        $this->info("Published {$count} scheduled article(s)");
        return self::SUCCESS;
    }
}
