# 📊 Các Cách Hiển Thị Dữ Liệu Analytics

Tệp này cung cấp các ví dụ về cách hiển thị dữ liệu analytics trên website.

## 1️⃣ Hiển Thị Bảng Thống Kê Hôm Nay

### Controller
```php
// app/Http/Controllers/AdminController.php

public function analytics()
{
    $today = Carbon::now()->startOfDay();
    $tomorrow = $today->clone()->addDay();

    $stats = [
        'total_visitors' => UserSession::whereBetween('started_at', [$today, $tomorrow])->count(),
        'total_time' => round(UserSession::whereBetween('started_at', [$today, $tomorrow])->sum('duration_seconds') / 60, 2),
        'total_clicks' => ClickAnalytic::whereBetween('clicked_at', [$today, $tomorrow])->count(),
    ];

    return view('analytics.daily', $stats);
}
```

### View
```blade
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Truy Cập Hôm Nay</h5>
                <h2 class="text-primary">{{ $total_visitors }}</h2>
                <small class="text-muted">lượt truy cập</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Thời Gian Trung Bình</h5>
                <h2 class="text-success">{{ $total_time }} phút</h2>
                <small class="text-muted">tính bằng phút</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Tổng Click</h5>
                <h2 class="text-info">{{ $total_clicks }}</h2>
                <small class="text-muted">lần click</small>
            </div>
        </div>
    </div>
</div>
```

---

## 2️⃣ Bảng Top Bài Viết Được Click Nhiều

### Controller
```php
public function topArticles()
{
    $topArticles = ClickAnalytic::where('item_type', 'article')
        ->whereBetween('clicked_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
        ->select('item_id')
        ->selectRaw('COUNT(*) as clicks')
        ->groupBy('item_id')
        ->orderByDesc('clicks')
        ->limit(10)
        ->with('article:id,title')
        ->get();

    return view('analytics.top-articles', compact('topArticles'));
}
```

### View
```blade
<div class="card mt-4">
    <div class="card-header">
        <h5>Top 10 Bài Viết - Hôm Nay</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tiêu Đề</th>
                    <th class="text-right">Số Click</th>
                    <th class="text-right">%</th>
                </tr>
            </thead>
            <tbody>
                @php $total = $topArticles->sum('clicks'); @endphp
                @forelse ($topArticles as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->article->title ?? 'N/A' }}</td>
                        <td class="text-right">
                            <span class="badge bg-primary">{{ $item->clicks }}</span>
                        </td>
                        <td class="text-right">
                            {{ round(($item->clicks / $total) * 100, 2) }}%
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Không có dữ liệu</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
```

---

## 3️⃣ Biểu Đồ Thống Kê (Sử dụng Chart.js)

### Controller
```php
public function chartData()
{
    $stats = UserSession::whereBetween('started_at', [
        Carbon::now()->startOfWeek(),
        Carbon::now()->endOfWeek()
    ])
        ->selectRaw('DATE(started_at) as date')
        ->selectRaw('COUNT(*) as visitors')
        ->selectRaw('SUM(total_clicks) as clicks')
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    return response()->json($stats);
}
```

### View với Chart.js
```blade
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="card mt-4">
    <div class="card-header">
        <h5>Biểu Đồ Tuần</h5>
    </div>
    <div class="card-body">
        <canvas id="weekChart"></canvas>
    </div>
</div>

<script>
    fetch('/api/analytics/chart-data')
        .then(r => r.json())
        .then(data => {
            new Chart(document.getElementById('weekChart'), {
                type: 'bar',
                data: {
                    labels: data.map(d => d.date),
                    datasets: [{
                        label: 'Lượt Truy Cập',
                        data: data.map(d => d.visitors),
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Click',
                        data: data.map(d => d.clicks),
                        backgroundColor: 'rgba(153, 102, 255, 0.5)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });
</script>
```

---

## 4️⃣ Trang Báo Cáo Hàng Ngày

### Route
```php
Route::get('/admin/reports/daily', [AdminController::class, 'dailyReport'])->name('reports.daily');
```

### Controller
```php
public function dailyReport($date = null)
{
    $date = $date ? Carbon::parse($date) : Carbon::now();
    $startDay = $date->clone()->startOfDay();
    $endDay = $date->clone()->endOfDay();

    $report = [
        'date' => $date,
        'total_visitors' => UserSession::whereBetween('started_at', [$startDay, $endDay])->count(),
        'total_time' => UserSession::whereBetween('started_at', [$startDay, $endDay])->sum('duration_seconds'),
        'total_clicks' => ClickAnalytic::whereBetween('clicked_at', [$startDay, $endDay])->count(),
        'clicks_by_type' => ClickAnalytic::whereBetween('clicked_at', [$startDay, $endDay])
            ->select('item_type')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('item_type')
            ->get(),
        'top_articles' => ClickAnalytic::where('item_type', 'article')
            ->whereBetween('clicked_at', [$startDay, $endDay])
            ->select('item_id')
            ->selectRaw('COUNT(*) as clicks')
            ->groupBy('item_id')
            ->orderByDesc('clicks')
            ->limit(5)
            ->with('article:id,title')
            ->get(),
    ];

    return view('analytics.daily-report', $report);
}
```

### View
```blade
<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>Báo Cáo Ngày {{ $date->format('d/m/Y') }}</h5>
        <form method="GET" action="{{ route('reports.daily') }}" class="d-flex gap-2">
            <input type="date" name="date" value="{{ $date->format('Y-m-d') }}" class="form-control">
            <button class="btn btn-primary" type="submit">Xem</button>
        </form>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <h6 class="text-muted">Truy Cập</h6>
                <h3>{{ $total_visitors }}</h3>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted">Thời Gian (Phút)</h6>
                <h3>{{ round($total_time / 60, 2) }}</h3>
            </div>
            <div class="col-md-3">
                <h6 class="text-muted">Tổng Click</h6>
                <h3>{{ $total_clicks }}</h3>
            </div>
        </div>
    </div>
</div>
```

---

## 5️⃣ Widget Nhỏ cho Dashboard

```blade
<!-- Widget 1: Truy cập trong 24h -->
<div class="widget">
    <div class="widget-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6>Truy Cập 24h</h6>
                <h2 id="visits24">0</h2>
            </div>
            <i class="icon-users fs-2"></i>
        </div>
    </div>
</div>

<!-- Widget 2: Click theo giờ -->
<div class="widget">
    <div class="widget-body">
        <h6>Hoạt Động</h6>
        <div id="hourlyChart"></div>
    </div>
</div>

<!-- Widget 3: Browser -->
<div class="widget">
    <div class="widget-body">
        <h6>Trình Duyệt Phổ Biến</h6>
        <ul id="browsersList"></ul>
    </div>
</div>
```

---

## 6️⃣ Export Báo Cáo (PDF/Excel)

### Controller
```php
public function exportReport(Request $request)
{
    $format = $request->get('format', 'pdf'); // pdf or csv

    $stats = UserSession::whereBetween('started_at', [
        $request->get('from', Carbon::now()->startOfMonth()),
        $request->get('to', Carbon::now()->endOfMonth())
    ])
        ->selectRaw('DATE(started_at) as date')
        ->selectRaw('COUNT(*) as visitors')
        ->selectRaw('SUM(duration_seconds) as time')
        ->selectRaw('SUM(total_clicks) as clicks')
        ->groupBy('date')
        ->get();

    if ($format === 'csv') {
        return response()->streamDownload(function() use ($stats) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Visitors', 'Time (min)', 'Clicks']);
            
            foreach ($stats as $row) {
                fputcsv($file, [
                    $row->date,
                    $row->visitors,
                    round($row->time / 60, 2),
                    $row->clicks
                ]);
            }
            
            fclose($file);
        }, 'analytics-' . date('Y-m-d') . '.csv');
    }
    
    // PDF export sử dụng library như TCPDF hoặc mPDF
}
```

---

## 7️⃣ Real-time Updates

```blade
<script>
    setInterval(() => {
        fetch('/api/analytics/daily-stats')
            .then(r => r.json())
            .then(data => {
                document.getElementById('totalVisitors').textContent = data.total_visitors;
                document.getElementById('totalTime').textContent = data.total_time_minutes;
                document.getElementById('totalClicks').textContent = data.total_clicks;
            });
    }, 5000); // Cập nhật mỗi 5 giây
</script>
```

---

## 📌 Lưu Ý

- Sử dụng `Carbon` để xử lý thời gian
- Luôn cache dữ liệu nếu không cần real-time
- Thêm pagination cho bảng có nhiều hàng
- Thêm filter theo ngày/tháng/năm
- Xem xét privacy/GDPR khi lưu IP và user agent
