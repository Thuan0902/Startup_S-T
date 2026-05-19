<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Occasion;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
    public function show(User $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return redirect()->route('login');
        }

        $articles = $user->articles()
            ->with(['category', 'images', 'products'])
            ->where('approval_status', 'approved')
            ->latest()
            ->paginate(10);

        $products = $user->products()
            ->with(['category'])
            ->where('approval_status', 'approved')
            ->latest()
            ->paginate(10);

        return view('profile', compact('user', 'articles', 'products'));
    }

    public function createArticle()
    {
        $user = Auth::user();

        if (!$user->isVip()) {
            return redirect()->route('profile')->with('error', 'Bạn cần nâng cấp tài khoản VIP để tạo bài viết.');
        }

        $categories = Category::orderBy('name')->get();
        $occasions = Occasion::orderBy('name')->get();
        $userProducts = $user->products()->where('approval_status', 'approved')->get();

        return view('user.create-article', compact('categories', 'occasions', 'userProducts'));
    }

    public function storeArticle(Request $request)
    {
        $user = Auth::user();

        if (!$user->isVip()) {
            return redirect()->route('profile')->with('error', 'Bạn cần nâng cấp tài khoản VIP để tạo bài viết.');
        }

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
        ]);

        // Kiểm tra sản phẩm có phải của user không
        if (!empty($validated['product_ids'])) {
            $userProductIds = $user->products()->pluck('id')->toArray();
            $invalidProducts = array_diff($validated['product_ids'], $userProductIds);
            if (!empty($invalidProducts)) {
                return back()->withErrors(['product_ids' => 'Bạn chỉ có thể chọn sản phẩm của mình.']);
            }
        }

        $categoryIds = $validated['category_ids'] ?? [];
        $productIds = $validated['product_ids'] ?? [];

        $article = Article::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status' => 0, // Chờ duyệt
            'approval_status' => 'pending',
            'user_id' => $user->id,
            'category_id' => $categoryIds[0] ?? null,
            'product_id' => $productIds[0] ?? null,
            'occasion_id' => $validated['occasion_id'] ?? null,
        ]);

        $article->categories()->sync($categoryIds);
        $article->products()->sync($productIds);

        if ($request->hasFile('images')) {
            $this->storeArticleImages($article, $request);
        }

        return redirect()->route('profile')->with('success', 'Bài viết đã được gửi để duyệt.');
    }

    public function createProduct()
    {
        $user = Auth::user();

        if (!$user->isVip()) {
            return redirect()->route('profile')->with('error', 'Bạn cần nâng cấp tài khoản VIP để tạo sản phẩm.');
        }

        $categories = Category::orderBy('name')->get();
        $occasions = Occasion::orderBy('name')->get();

        return view('user.create-product', compact('categories', 'occasions'));
    }

    public function storeProduct(Request $request)
    {
        $user = Auth::user();

        if (!$user->isVip()) {
            return redirect()->route('profile')->with('error', 'Bạn cần nâng cấp tài khoản VIP để tạo sản phẩm.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'advantages' => ['nullable', 'string'],
            'disadvantages' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
            'affiliate_link' => ['nullable', 'url'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'occasion_id' => ['nullable', 'integer', 'exists:occasions,id'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'advantages' => $validated['advantages'] ?? null,
            'disadvantages' => $validated['disadvantages'] ?? null,
            'price' => $validated['price'] ?? null,
            'image' => $imagePath,
            'affiliate_link' => $validated['affiliate_link'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'occasion_id' => $validated['occasion_id'] ?? null,
            'status' => 0, // Chờ duyệt
            'approval_status' => 'pending',
            'user_id' => $user->id,
            'views' => 0,
        ]);

        return redirect()->route('profile')->with('success', 'Sản phẩm đã được gửi để duyệt.');
    }

    private function storeArticleImages($article, $request)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('articles', 'public');

                $article->images()->create([
                    'image_path' => $path,
                    'sort_order' => $index,
                ]);
            }
        }
    }
}
