<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\UpdateNewsFeedJob;

class FetchArticles extends Command
{
    protected $signature = 'fetch:articles';
    protected $description = 'Fetch news articles from all sources and save to DB';

    public function handle()
    {
       
        dispatch_sync(new UpdateNewsFeedJob());

        $this->info('News articles fetched and saved successfully.');
    }
}
