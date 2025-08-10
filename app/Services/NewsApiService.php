<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class NewsApiService
{
    protected string $apiKey;
    protected array $categories = ['business', 'entertainment', 'general', 'health', 'science', 'sports', 'technology'];


    public function __construct()
    {
        $this->apiKey = config('services.newsapi.key') ?? env('NEWSAPI_KEY');
        
    }
    public function fetchArticles(): array
    {
        $allArticles = [];

        foreach ($this->categories as $category) {
            $response = Http::get('https://newsapi.org/v2/top-headlines', [
                'apiKey' => $this->apiKey,
                'language' => 'en',
                'category' => $category,
                'pageSize' => 10,
            ]);

            if ($response->successful()) {
                $articles = $response->json('articles', []);
                foreach ($articles as &$article) {
                    $article['category'] = $category;
                }
                $allArticles = array_merge($allArticles, $articles);
            }
        }
        return $allArticles;
    }

    // public function fetchArticles(): array
    // {
    //     $response = Http::get('https://newsapi.org/v2/top-headlines', [
    //         'apiKey' => $this->apiKey,
    //         'language' => 'en',
    //         'pageSize' => 10,
    //     ]);

    //     if ($response->failed()) {
    //         return [];
    //     }

    //     return $response->json('articles', []);
    // }
}
