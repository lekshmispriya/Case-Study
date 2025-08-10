<?php

namespace App\Jobs;

use App\Models\Article;
use App\Services\NewsApiService;
use App\Services\GuardianApiService;
use App\Services\NyApiService;
use App\Services\BbcApiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateNewsFeedJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function handle(
        NewsApiService $newsApi,
        GuardianApiService $guardian,
        NyApiService $nytimes,
        BbcApiService $bbc
    ) {
        $feeds = [
            'newsapi' => $newsApi->fetchArticles(),
            'guardian' => $guardian->fetchArticles(),
            'nytimes' => $nytimes->fetchArticles(),
            'bbc' => $bbc->fetchArticles(),
        ];

        foreach ($feeds as $source => $articles) {
            foreach ($articles as $item) {
                $data = match ($source) {
                    'newsapi' => [
                        'title' => $item['title'] ?? '',
                        'url' => $item['url'] ?? '',
                        'description' => $item['description'] ?? null,
                        'url_to_image' => $item['urlToImage'] ?? null,
                        'published_at' => isset($item['publishedAt']) 
                            ? date('Y-m-d H:i:s', strtotime($item['publishedAt'])) 
                            : null,
                        'source' => $source,
                        'author' => $item['author'] ?? null,
                        'category' => $item['category'] ?? null,
                    ],
                    'guardian' => [
                        'title' => $item['webTitle'] ?? '',
                        'url' => $item['webUrl'] ?? '',
                        'description' => $item['fields']['trailText'] ?? null,
                        'url_to_image' => $item['fields']['thumbnail'] ?? null,
                        'published_at' => isset($item['webPublicationDate']) 
                            ? date('Y-m-d H:i:s', strtotime($item['webPublicationDate'])) 
                            : null,
                        'source' => $source,
                        'author' => $item['fields']['byline'] ?? null,
                        'category' => $item['sectionName'] ?? null,
                    ],
                    'nytimes' => [
                        'title' => $item['title'] ?? '',
                        'url' => $item['url'] ?? '',
                        'description' => $item['abstract'] ?? null,
                        'url_to_image' => $item['multimedia'][0]['url'] ?? null,
                        'published_at' => isset($item['published_date']) 
                            ? date('Y-m-d H:i:s', strtotime($item['published_date'])) 
                            : null,
                        'source' => $source,
                        'author' => $item['byline'] ?? null,
                        'category' => $item['section'] ?? null,
                    ],
                    'bbc' => [
                        'title' => $item['title'] ?? '',
                        'url' => $item['url'] ?? '',
                        'description' => $item['description'] ?? null,
                        'url_to_image' => $item['urlToImage'] ?? null,
                        'published_at' => isset($item['publishedAt']) 
                            ? date('Y-m-d H:i:s', strtotime($item['publishedAt'])) 
                            : null,
                        'source' => $item['source']['id'] ?? 'bbc-news',
                        'author' => $item['author'] ?? null,
                        'category' => null,  // BBC API not providing category 
                    ],
                    default => null,
                };

                // Skip if data invalid
                if (!$data || empty($data['title']) || empty($data['url'])) {
                    continue;
                }

                Article::updateOrCreate(
                    ['url' => $data['url']],
                    [
                        'source' => $data['source'],
                        'title' => $data['title'],
                        'description' => $data['description'],
                        'url_to_image' => $data['url_to_image'],
                        'published_at' => $data['published_at'],
                        'author' => $data['author'],
                        'category' => $data['category'],
                    ]
                );
            }
        }
    }
}
