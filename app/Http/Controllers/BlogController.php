<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;

class BlogController extends Controller
{
    public function index()
    {
        $categoryFilter = request('category');

        $categories = Category::where('status', 1)
            ->orderBy('id', 'asc')
            ->get();

        $articles = Article::with(['category', 'categories', 'images'])
            ->where('status', 1)
            ->where('approval_status', 'approved')
            ->when($categoryFilter, function ($query) use ($categoryFilter) {
                $query->where('category_id', $categoryFilter);
            })
            ->latest()
            ->get();

        return view('blog', compact('categories', 'articles'));
    }
}
