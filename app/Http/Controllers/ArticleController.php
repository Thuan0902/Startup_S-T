<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleImage;
use App\Models\Category;
use App\Models\Product;
use App\Models\Occasion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $search = trim((string) request('q', ''));
        $status = (string) request('status', '');
        $categoryFilter = (string) request('category_id', '');
        $productFilter = (string) request('product_id', '');
        $occasionFilter = (string) request('occasion_id', '');

        $articles = Article::with(['categories', 'products', 'images'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['0', '1'], true), function ($query) use ($status) {
                $query->where('status', (int) $status);
            })
            ->when(ctype_digit($categoryFilter), function ($query) use ($categoryFilter) {
                $query->whereHas('categories', function ($subQuery) use ($categoryFilter) {
                    $subQuery->where('categories.id', (int) $categoryFilter);
                });
            })
            ->when(ctype_digit($productFilter), function ($query) use ($productFilter) {
                $query->whereHas('products', function ($subQuery) use ($productFilter) {
                    $subQuery->where('products.id', (int) $productFilter);
                });
            })
            ->when(ctype_digit($occasionFilter), function ($query) use ($occasionFilter) {
                $query->where('occasion_id', (int) $occasionFilter);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $occasions = Occasion::orderBy('name')->get();

        return view('Admin.article', compact('articles', 'categories', 'products', 'occasions', 'search', 'status', 'categoryFilter', 'productFilter', 'occasionFilter'));
    }

    public function show(Article $article)
    {
        if (! $article->status || $article->approval_status !== 'approved') {
            abort(404);
        }

        $article->load(['category', 'images', 'occasion']);

        $relatedArticles = Article::with(['category', 'images'])
            ->where('status', 1)
            ->where('id', '<>', $article->id)
            ->when($article->category_id, function ($query) use ($article) {
                $query->where('category_id', $article->category_id);
            })
            ->latest()
            ->take(4)
            ->get();

        $topProducts = $article->products()
            ->with('category')
            ->get();

        return view('article', compact('article', 'relatedArticles', 'topProducts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'occasion_id' => ['nullable', 'integer', 'exists:occasions,id'],
            'images' => ['nullable', 'array', 'max:3'],
            'images.*' => ['image', 'max:2048'],
            'status' => ['nullable', 'boolean'],
        ]);

        $categoryIds = $validated['category_ids'] ?? [];
        $productIds = $validated['product_ids'] ?? [];

        $article = Article::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $request->boolean('status'),
            // Keep compatibility with existing single-value columns.
            'category_id' => $categoryIds[0] ?? null,
            'product_id' => $productIds[0] ?? null,
            'occasion_id' => $validated['occasion_id'] ?? null,
        ]);

        $article->categories()->sync($categoryIds);
        $article->products()->sync($productIds);
        $this->storeArticleImages($article, $request);

        return redirect()->route('article')->with('success', 'Article created successfully.');
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
            'occasion_id' => ['nullable', 'integer', 'exists:occasions,id'],
            'images' => ['nullable', 'array', 'max:3'],
            'images.*' => ['image', 'max:2048'],
            'status' => ['nullable', 'boolean'],
        ]);

        $categoryIds = $validated['category_ids'] ?? [];
        $productIds = $validated['product_ids'] ?? [];

        $article->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => $request->boolean('status'),
            'category_id' => $categoryIds[0] ?? null,
            'product_id' => $productIds[0] ?? null,
            'occasion_id' => $validated['occasion_id'] ?? null,
        ]);

        $article->categories()->sync($categoryIds);
        $article->products()->sync($productIds);

        if ($request->hasFile('images')) {
            foreach ($article->images as $existingImage) {
                Storage::disk('public')->delete($existingImage->image_path);
            }
            $article->images()->delete();
            $this->storeArticleImages($article, $request);
        }

        return redirect()->route('article')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        foreach ($article->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $article->delete();

        return redirect()->route('article')->with('success', 'Article deleted successfully.');
    }

    private function storeArticleImages(Article $article, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $images = $request->file('images');
        foreach ($images as $index => $uploadedImage) {
            $path = $uploadedImage->store('articles', 'public');
            ArticleImage::create([
                'article_id' => $article->id,
                'image_path' => $path,
                'sort_order' => $index,
            ]);
        }
    }
}
