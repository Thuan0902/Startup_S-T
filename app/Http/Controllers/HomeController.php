<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $latestArticles = Article::with(['images', 'category', 'user'])
            ->where('status', 1)
            ->where('approval_status', 'approved')
            ->latest()
            ->take(3)
            ->get();

        return view('home', compact('latestArticles')); // Trả về view home.blade.php
    }
}
