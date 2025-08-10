<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class NyApiService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.nytimes.com/svc/topstories/v2';

    public function __construct()
    {
        // Store your NYTimes API key in config/services.php or .env
        $this->apiKey = config('services.nytimes.key');
    }

    /**
     * Fetch latest top stories articles from NYTimes API
     *
     * @param string $section Section of news, e.g. 'home', 'world', 'technology'
     * @return array
     */
    public function fetchArticles(string $section = 'home'): array
    {
        $url = $this->baseUrl . '/' . $section . '.json';

        $response = Http::get($url, [
            'api-key' => $this->apiKey,
        ]);

        if ($response->failed()) {
            // Log error, return empty array
            \Log::error('NYTimes API request failed', ['response' => $response->body()]);
            return [];
        }

        $data = $response->json();

        if (!isset($data['results']) || !is_array($data['results'])) {
            return [];
        }

        // Transform NYTimes articles to a consistent format
        $articles = [];

        foreach ($data['results'] as $item) {
            $articles[] = [
                'title'        => $item['title'] ?? '',
                'description'  => $item['abstract'] ?? '',
                'url'          => $item['url'] ?? '',
                'source'       => 'New York Times',
                'category'     => $section,
                'author'       => $this->extractAuthor($item),
                'published_at' => $item['published_date'] ?? null,
                'content'      => $item['abstract'] ?? '',
            ];
        }

        return $articles;
    }

    /**
     * Extract author name(s) from NYTimes article data
     *
     * @param array $article
     * @return string|null
     */
    protected function extractAuthor(array $article): ?string
    {
        if (!empty($article['byline'])) {
            // NYTimes often gives "By John Doe"
            return Str::replaceFirst('By ', '', $article['byline']);
        }

        return null;
    }
}
