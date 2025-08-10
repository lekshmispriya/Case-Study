<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BbcApiService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.bbc.key') ?? env('NEWSAPI_KEY');
    }

    public function fetchArticles(): array
    {
         $response = Http::get('https://newsapi.org/v2/top-headlines', [
            'apikey' => $this->apiKey,
            'sources' => 'bbc-news', 
            'language' => 'en',
            'pageSize' => 10,
        ]);
       // dd($response->json());
        if ($response->failed()) {
            return [];
        }
        $json = $response->json();
        return $json['articles'] ?? [];
    }
}
