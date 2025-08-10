<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class NewsFeedController extends Controller
{
    
    public function list()
    {
        return Article::orderByDesc('published_at')->paginate(20);
    }

    // Search articles by keyword in title or description
    
    public function search(Request $request)
    {
        $queryTerm = $request->query('q', '');
        $source = $request->query('source', '');
        $category = $request->query('category', '');
        $author = $request->query('author', '');
        $dateFrom = $request->query('date_from', '');
        $dateTo = $request->query('date_to', '');
    
        $query = Article::query();
    
        if ($queryTerm) {
            $query->where(function ($q) use ($queryTerm) {
                $q->where('title', 'like', "%{$queryTerm}%")
                  ->orWhere('description', 'like', "%{$queryTerm}%");
            });
        }
    
        if ($source) {
            $query->where('source', $source);
        }
    
        if ($category) {
            $query->where('category', $category);
        }
    
        if ($author) {
            $query->where('author', $author);
        }
    
        if ($dateFrom) {
            $query->whereDate('published_at', '>=', $dateFrom);
        }
    
        if ($dateTo) {
            $query->whereDate('published_at', '<=', $dateTo);
        }
    
        $articles = $query->orderByDesc('published_at')->paginate(20);
    
        return response()->json([
            'status' => 'success',
            'data' => [
                'current_page' => $articles->currentPage(),
                'per_page' => $articles->perPage(),
                'total' => $articles->total(),
                'last_page' => $articles->lastPage(),
                'articles' => $articles->items(),
            ],
            'message' => 'Articles retrieved successfully',
        ]);
    }

}
