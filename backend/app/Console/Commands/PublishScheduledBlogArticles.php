<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BlogArticle;
use App\Enums\BlogArticleStatus;

/**
 * Command publishes blog articles that were scheduled for a future date (from
 * "scheduled" to "published" status once their publish_at timestamp has been reached)
 */
class PublishScheduledBlogArticles extends Command
{
    protected $signature = 'blog:publish-scheduled';
    protected $description = 'Publish scheduled blog articles whose publish_at time has arrived';

    public function handle()
    {
        $count = BlogArticle::query()
            ->where('status', BlogArticleStatus::Scheduled->value)
            ->whereNotNull('publish_at')
            ->where('publish_at', '<=', now())
            ->update(['status' => BlogArticleStatus::Published->value]);

        $this->info("Published {$count} scheduled article(s)");
        return self::SUCCESS;
    }
}
