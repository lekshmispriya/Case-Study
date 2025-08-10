<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GuardianApiService
{
    protected string $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.guardian.key') ?? env('GUARDIAN_KEY');
    }

    public function fetchArticles(): array
    {
        $response = Http::get('https://content.guardianapis.com/search', [
            'api-key' => $this->apiKey,
            'show-fields' => 'trailText,thumbnail',
            'page-size' => 10,
        ]);

        if ($response->failed()) {
            return [];
        }

        return $response->json('response.results', []);
    }
}
