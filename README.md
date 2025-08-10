# News Aggregator Backend

This is a Laravel 11 backend project for a News Aggregator website.  
It fetches news articles from multiple sources (NewsAPI.org, The Guardian API, and BBC RSS feeds), stores them in a database, and provides API endpoints to search and filter articles for the frontend application.

---

## Features

- Fetch articles from:
  - NewsAPI.org (JSON API)
  - The Guardian API (JSON API)
  - BBC News RSS feeds (XML)
- Store articles locally in MySQL database
- Search and filter articles by:
  - Keywords (title, description)
  - Source
  - Category
  - Author
  - Publication date range
- Pagination support for API responses
- Scheduled automatic fetching/updating of articles

---

## Requirements

- PHP 8.2  
- Laravel 11.x  
- MySQL or compatible database  
- Composer  
- API keys for NewsAPI and The Guardian (see below)

---

## Setup Instructions

1. **Clone the repository**

-git clone https://github.com/lekshmispriya/Case-Study.git
cd case_study
#Install dependencies
composer install
#Configure environment

#Copy .env.example to .env:
    cp .env.example .env
#Set your database credentials in .env

#Add API keys in .env:

NEWSAPI_KEY=your_newsapi_key_here
GUARDIAN_API_KEY=your_guardian_api_key_here
NYTIMES_KEY=your_nyt_api_key_here (if used)
#Run migrations
  -php artisan migrate
#Start Server
  php artisan serve
#Run Queue
 -php artisan queue:work
#Run the scheduler 
 -php artisan schedule:work
#Incase if you want to test the job manually then run
 -php artisan fetch:articles

######API Endpoints##########
1.GET /api/news?page=1
-Retrieve the latest news articles with pagination.
2.GET /api/news/search
    -Search and filter news articles.

     -Query parameters:

######Parameter	Description	Example#######
1.q	        ->   Search keyword (title or description)	climate
2.source	->   Filter by source name	NewsAPI
3.category	->   Filter by category	technology
4.author	->   Filter by author name	John
5.date_from	->   Articles published on or after this date	2025-01-01
6.date_to	->   Articles published on or before this date	2025-08-10
7.page	    ->   Pagination page number	1

Example request:

GET /api/news/search?q=climate&source=NewsAPI&category=technology&page=1
_______________________________________
######Services Overview######
_________________________________________
App\Services\NewsApiService – Fetches articles from NewsAPI.org

App\Services\GuardianService – Fetches articles from The Guardian API

App\Services\BbcRssService – Fetches and parses articles from BBC RSS feeds

Notes
Ensure you have valid API keys from NewsAPI.org and The Guardian Developer Platform.

BBC RSS feeds do not require API keys and are parsed from XML.

Articles are deduplicated by their URL before saving.

Pagination defaults to 20 articles per page.

## Postman Collection

You can import the Postman collection to test the API endpoints easily.
 - Download the collection JSON file from the repo: case study  news apis.postman_collection
 - Open Postman, click **Import** > **File** and select the downloaded JSON file.
 - You can now run all API requests from Postman with pre-configured endpoints.