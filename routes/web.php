<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OccasionController;
use App\Http\Controllers\PageContentController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UsersController;
use App\Models\Article;
use App\Models\Category;
use App\Models\Occasion;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/center', [HomeController::class, 'index']);

Route::get('/search', function () {
    $search = trim((string) request('q', ''));

    $products = collect();
    $articles = collect();
    $occasions = collect();

    if ($search !== '') {
        $products = Product::with(['category', 'occasion'])
            ->where('status', 1)
            ->where('approval_status', 'approved')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('advantages', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('occasion', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->take(12)
            ->get();

        $articles = Article::with(['category', 'categories', 'images', 'occasion'])
            ->where('status', 1)
            ->where('approval_status', 'approved')
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('categories', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('occasion', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->take(8)
            ->get();

        $occasions = Occasion::where('status', 1)
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->take(12)
            ->get();
    }

    return view('search', compact('search', 'products', 'articles', 'occasions'));
})->name('search');


Route::get('/about', function () {
    return redirect()->route('occasions');
})->name('about');
Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// placeholder routes for other menu items
Route::get('/services', [UserProfileController::class, 'show'])->name('services');
Route::get('/profile/{user?}', [UserProfileController::class, 'show'])->name('profile')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/user/create-article', [UserProfileController::class, 'createArticle'])->name('user.create-article');
    Route::post('/user/store-article', [UserProfileController::class, 'storeArticle'])->name('user.store-article');
    Route::get('/user/create-product', [UserProfileController::class, 'createProduct'])->name('user.create-product');
    Route::post('/user/store-product', [UserProfileController::class, 'storeProduct'])->name('user.store-product');
});
Route::view('/portfolio', 'portfolio')->name('portfolio');
Route::get('/team', function () {
    $categoryFilter = request('category');
    $occasionFilter = request('occasion');

    $products = Product::with(['category', 'occasion'])
        ->where('status', 1)
        ->where('approval_status', 'approved')
        ->when($categoryFilter, function ($query, $categoryFilter) {
            $query->where('category_id', $categoryFilter);
        })
        ->when($occasionFilter, function ($query, $occasionFilter) {
            $query->where('occasion_id', $occasionFilter);
        })
        ->latest()
        ->take(24)
        ->get();

    return view('team', [
        'categories' => Category::orderBy('name')->get(),
        'occasions' => Occasion::orderBy('name')->get(),
        'products' => $products,
    ]);
})->name('team');
Route::get('/occasions', function () {
    $categoryFilter = request('category');
    $occasionFilter = request('occasion');

    $categories = Category::where('status', 1)
        ->orderBy('id', 'asc')
        ->get();

    $occasions = Occasion::where('status', 1)
        ->orderBy('name')
        ->get();

    $articles = Article::with(['category', 'categories', 'occasion', 'images'])
        ->where('status', 1)
        ->where('approval_status', 'approved')
        ->when($categoryFilter, function ($query, $categoryFilter) {
            $query->where('category_id', $categoryFilter);
        })
        ->when($occasionFilter, function ($query, $occasionFilter) {
            $query->where('occasion_id', $occasionFilter);
        })
        ->latest()
        ->get();

    $products = Product::with(['category', 'occasion'])
        ->where('status', 1)
        ->where('approval_status', 'approved')
        ->when($categoryFilter, function ($query, $categoryFilter) {
            $query->where('category_id', $categoryFilter);
        })
        ->when($occasionFilter, function ($query, $occasionFilter) {
            $query->where('occasion_id', $occasionFilter);
        })
        ->latest()
        ->take(20)
        ->get();

    return view('occasions', compact('categories', 'occasions', 'articles', 'products'));
})->name('occasions');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/article/{article}', [ArticleController::class, 'show'])->name('article.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::get('/admin/content', [AdminController::class, 'content'])->name('admin.content');
    Route::post('/admin/page-content', [PageContentController::class, 'update'])->name('admin.page-content.update');

    Route::get('/category', [CategoryController::class, 'index'])->name('category');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::put('/category/{category}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{category}', [CategoryController::class, 'destroy'])->name('category.destroy');

    Route::get('/occasion', [OccasionController::class, 'index'])->name('occasion');
    Route::post('/occasion', [OccasionController::class, 'store'])->name('occasion.store');
    Route::put('/occasion/{occasion}', [OccasionController::class, 'update'])->name('occasion.update');
    Route::delete('/occasion/{occasion}', [OccasionController::class, 'destroy'])->name('occasion.destroy');

    Route::get('/article', [ArticleController::class, 'index'])->name('article');
    Route::post('/article', [ArticleController::class, 'store'])->name('article.store');
    Route::put('/article/{article}', [ArticleController::class, 'update'])->name('article.update');
    Route::delete('/article/{article}', [ArticleController::class, 'destroy'])->name('article.destroy');

    Route::get('/products', [ProductsController::class, 'index'])->name('products');
    Route::post('/products', [ProductsController::class, 'store'])->name('products.store');
    Route::put('/products/{product}', [ProductsController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductsController::class, 'destroy'])->name('products.destroy');

    Route::get('/users', [UsersController::class, 'index'])->name('users');
    Route::post('/users/{user}/toggle-vip', [UsersController::class, 'toggleVip'])->name('users.toggle-vip');

    // Approval routes
    Route::post('/article/{article}/approve', [AdminController::class, 'approveArticle'])->name('article.approve');
    Route::post('/article/{article}/reject', [AdminController::class, 'rejectArticle'])->name('article.reject');
    Route::post('/products/{product}/approve', [AdminController::class, 'approveProduct'])->name('products.approve');
    Route::post('/products/{product}/reject', [AdminController::class, 'rejectProduct'])->name('products.reject');
    Route::put('/users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UsersController::class, 'destroy'])->name('users.destroy');
});

// Google OAuth Routes
Route::get('/auth/google', [LoginController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback'])->name('auth.google.callback');
