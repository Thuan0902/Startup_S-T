<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ClickAnalytic;
use App\Models\Comment;
use App\Models\Product;
use App\Models\PageContent;
use App\Models\UserSession;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $latestArticles = Article::with(['images', 'categories'])
            ->latest()
            ->take(4)
            ->get();

        // Lấy thống kê hôm nay
        $today = Carbon::now()->startOfDay();
        $tomorrow = $today->clone()->addDay();

        // Tổng số lượt truy cập hôm nay
        $totalVisitors = UserSession::whereBetween('started_at', [$today, $tomorrow])->count();

        // Tổng thời gian người dùng ở lại (phút)
        $totalTimeMinutes = round(
            UserSession::whereBetween('started_at', [$today, $tomorrow])->sum('duration_seconds') / 60,
            2
        );

        // Tổng số click hôm nay
        $totalClicks = ClickAnalytic::whereBetween('clicked_at', [$today, $tomorrow])->count();

        // Thống kê click theo type (bài viết vs sản phẩm)
        $clickStats = ClickAnalytic::whereBetween('clicked_at', [$today, $tomorrow])
            ->selectRaw("item_type, COUNT(*) as count")
            ->groupBy('item_type')
            ->pluck('count', 'item_type');

        // Thống kê từng ngày trong tuần
        $weekStats = UserSession::whereBetween('started_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])
            ->selectRaw("DATE(started_at) as date")
            ->selectRaw("COUNT(*) as visitors")
            ->selectRaw("SUM(duration_seconds) as total_time")
            ->selectRaw("SUM(total_clicks) as clicks")
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Thống kê click theo ngày trong 3 ngày gần nhất
        $threeDayStats = [];
        for ($i = 2; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->startOfDay();
            $endDate = $date->clone()->addDay();

            $clicksByType = ClickAnalytic::whereBetween('clicked_at', [$date, $endDate])
                ->selectRaw("item_type, COUNT(*) as count")
                ->groupBy('item_type')
                ->pluck('count', 'item_type');

            $threeDayStats[] = [
                'date' => $date->format('Y-m-d'),
                'date_display' => $date->format('d/m'),
                'article_clicks' => $clicksByType->get('article', 0),
                'product_clicks' => $clicksByType->get('product', 0),
                'total_clicks' => $clicksByType->sum()
            ];
        }

        // Top 5 sản phẩm được click nhiều nhất hôm nay
        $topProducts = ClickAnalytic::where('item_type', 'product')
            ->whereBetween('clicked_at', [$today, $tomorrow])
            ->select('item_id')
            ->selectRaw('COUNT(*) as clicks')
            ->groupBy('item_id')
            ->orderByDesc('clicks')
            ->limit(5)
            ->get();

        // Lấy thông tin chi tiết sản phẩm
        $productIds = $topProducts->pluck('item_id')->toArray();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $topProductsList = $topProducts->map(function($item) use ($products) {
            return [
                'product' => $products->get($item->item_id),
                'clicks' => $item->clicks
            ];
        })->filter(function($item) {
            return $item['product'] !== null;
        });

        // Lấy 10 phản hồi gần đây nhất
        $recentComments = Comment::latest()
            ->limit(10)
            ->get()
            ->map(function($comment) {
                $data = [
                    'comment' => $comment,
                    'user_name' => $comment->user ? $comment->user->name : $comment->name,
                    'user_email' => $comment->user ? $comment->user->email : $comment->email,
                    'item_type' => $comment->commentable_type,
                    'item_name' => null
                ];

                if ($comment->commentable_type === 'article') {
                    $article = Article::find($comment->commentable_id);
                    $data['item_name'] = $article ? $article->title : 'Bài viết (Đã xóa)';
                } elseif ($comment->commentable_type === 'product') {
                    $product = Product::find($comment->commentable_id);
                    $data['item_name'] = $product ? $product->name : 'Sản phẩm (Đã xóa)';
                }

                return $data;
            });

        return view('Admin.admin', compact(
            'latestArticles',
            'totalVisitors',
            'totalTimeMinutes',
            'totalClicks',
            'clickStats',
            'weekStats',
            'threeDayStats',
            'topProductsList',
            'recentComments'
        ));
    }

    public function content()
    {
        $pageKeys = ['home', 'occasions', 'site'];
        $pages = collect(config('page_content.pages'))->only($pageKeys)->toArray();
        $pageContents = [];

        foreach ($pageKeys as $pageKey) {
            $pageContents[$pageKey] = PageContent::valuesForPage($pageKey);
        }

        return view('Admin.content', compact('pages', 'pageContents'));
    }

    public function approveArticle(Article $article)
    {
        $article->update([
            'approval_status' => 'approved',
            'status' => 1
        ]);

        return redirect()->back()->with('success', 'Bài viết đã được duyệt.');
    }

    public function rejectArticle(Article $article)
    {
        $article->update(['approval_status' => 'rejected']);

        return redirect()->back()->with('success', 'Bài viết đã bị từ chối.');
    }

    public function approveProduct(Product $product)
    {
        $product->update([
            'approval_status' => 'approved',
            'status' => 1
        ]);

        return redirect()->back()->with('success', 'Sản phẩm đã được duyệt.');
    }

    public function rejectProduct(Product $product)
    {
        $product->update(['approval_status' => 'rejected']);

        return redirect()->back()->with('success', 'Sản phẩm đã bị từ chối.');
    }
}
