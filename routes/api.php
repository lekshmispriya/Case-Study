<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsFeedController;

Route::get('/news', [NewsFeedController::class, 'list']);
Route::get('/news/search', [NewsFeedController::class, 'search']);
