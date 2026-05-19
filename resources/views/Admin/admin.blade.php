@extends('Admin.layoutAdmin')

@section('Admin_content')
<div class="body-wrapper-inner">
    <div class="container-fluid">
        <!--  Row 1 - Daily Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="card-subtitle text-muted mb-2">Tổng Lượt Truy Cập</p>
                                <h4 class="card-title mb-0" id="totalVisitors">{{ $totalVisitors ?? 0 }}</h4>
                                <small class="text-muted">Hôm nay</small>
                            </div>
                            <div class="text-bg-primary rounded-circle p-3">
                                <i class="ti ti-users fs-6 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="card-subtitle text-muted mb-2">Tổng Thời Gian (Phút)</p>
                                <h4 class="card-title mb-0" id="totalTime">{{ $totalTimeMinutes ?? 0 }}</h4>
                                <small class="text-muted">Trung bình: {{ $totalVisitors > 0 ? round(($totalTimeMinutes * 60) / $totalVisitors, 2) : 0 }}s</small>
                            </div>
                            <div class="text-bg-info rounded-circle p-3">
                                <i class="ti ti-clock fs-6 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="card-subtitle text-muted mb-2">Tổng Số Click</p>
                                <h4 class="card-title mb-0" id="totalClicks">{{ $totalClicks ?? 0 }}</h4>
                                <small class="text-muted">Hôm nay</small>
                            </div>
                            <div class="text-bg-success rounded-circle p-3">
                                <i class="ti ti-click fs-6 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--  Row 2 -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card w-100">
                    <div class="card-body">
                        <div class="d-md-flex align-items-center">
                            <div>
                                <h4 class="card-title">Phân Tích Click</h4>
                                <p class="card-subtitle">
                                    Lượt click bài viết và sản phẩm trong 3 ngày gần nhất
                                </p>
                            </div>
                            <div class="ms-auto">
                                <ul class="list-unstyled mb-0">
                                    <li class="list-inline-item text-primary">
                                        <span class="round-8 text-bg-primary rounded-circle me-1 d-inline-block"></span>
                                        Sản phẩm
                                    </li>
                                    <li class="list-inline-item text-info">
                                        <span class="round-8 text-bg-info rounded-circle me-1 d-inline-block"></span>
                                        Bài viết
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div id="sales-overview" class="mt-4 mx-n6"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <!-- Thống kê chi tiết 3 ngày -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="card-title mb-0">Lịch Sử 3 Ngày</h4>
                            <button type="button" class="btn btn-primary btn-sm" onclick="exportTodayData()">
                                <i class="ti ti-download me-1"></i>Xuất Dữ Liệu Hôm Nay
                            </button>
                        </div>
                        <p class="card-subtitle text-muted">Chi tiết click theo ngày</p>
                        <div class="mt-3">
                            @foreach($threeDayStats as $stat)
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                    <div>
                                        <h6 class="mb-0">{{ $stat['date_display'] }}</h6>
                                        <small class="text-muted">Tổng: {{ $stat['total_clicks'] }} click</small>
                                    </div>
                                    <div class="text-end">
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-primary">{{ $stat['product_clicks'] }} SP</span>
                                            <span class="badge bg-info">{{ $stat['article_clicks'] }} BV</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
                <div class="card overflow-hidden">
                    <div class="card-body pb-0">
                        <div class="d-flex align-items-start">
                            <div>
                                <h4 class="card-title">Bài Viết trong tuần </h4>
                                <p class="card-subtitle">Top bài viết được xem nhiều nhất</p>
                            </div>
                            <div class="ms-auto">
                                <div class="dropdown">
                                    <a href="javascript:void(0)" class="text-muted" id="year1-dropdown" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="ti ti-dots fs-7"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="year1-dropdown">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)">Từ cao đến thấp</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0)">Từ Thấp đến cao</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        @forelse ($latestArticles as $article)
                            <div class="mt-4 pb-3 d-flex align-items-center">
                                @if ($article->images->isNotEmpty())
                                    <img src="{{ asset('storage/' . $article->images->first()->image_path) }}" alt="article image" width="48" height="48" class="rounded-circle object-fit-cover">
                                @else
                                    <span class="btn btn-primary rounded-circle round-48 hstack justify-content-center">
                                        <i class="ti ti-photo fs-6"></i>
                                    </span>
                                @endif
                                <div class="ms-3">
                                    <h5 class="mb-0 fw-bolder fs-4">{{ $article->title }}</h5>
                                    <span class="text-muted fs-3">
                                        {{ $article->categories->isNotEmpty() ? $article->categories->pluck('name')->implode(', ') : 'Chua co danh muc' }}
                                    </span>
                                </div>
                                <div class="ms-auto">
                                    @if ($article->status)
                                        <span class="badge bg-success-subtle text-success">Dang hien thi</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-muted">Dang an</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="mt-4 pb-3 d-flex align-items-center">
                                <span class="btn btn-primary rounded-circle round-48 hstack justify-content-center">
                                    <i class="ti ti-photo fs-6"></i>
                                </span>
                                <div class="ms-3">
                                    <h5 class="mb-0 fw-bolder fs-4">Chua co bai viet</h5>
                                    <span class="text-muted fs-3">Hay tao bai viet moi</span>
                                </div>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
                    <div class="card-body">
                        <div class="d-md-flex align-items-center">
                            <div>
                                <h4 class="card-title">Sản phẩm thịnh hành</h4>
                                <p class="card-subtitle">
                                    Top sản phẩm được người dùng click nhiều nhất hôm nay
                                </p>
                            </div>
                            <div class="ms-auto mt-3 mt-md-0">
                                <span class="badge bg-primary">Hôm nay</span>
                            </div>
                        </div>
                        <div class="table-responsive mt-4">
                            <table class="table mb-0 text-nowrap varient-table align-middle fs-3">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-0 text-muted">
                                            #
                                        </th>
                                        <th scope="col" class="px-0 text-muted">Tên Sản Phẩm</th>
                                        <th scope="col" class="px-0 text-muted">
                                            Giá
                                        </th>
                                        <th scope="col" class="px-0 text-muted text-center">
                                            Số Click
                                        </th>
                                        <th scope="col" class="px-0 text-muted text-center">
                                            Trạng Thái
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topProductsList as $key => $item)
                                        @if ($item['product'])
                                            <tr>
                                                <td class="px-0">
                                                    <span class="badge bg-primary">{{ $key + 1 }}</span>
                                                </td>
                                                <td class="px-0">
                                                    <div class="d-flex align-items-center gap-3">
                                                        @if ($item['product']->image)
                                                            <img src="{{ asset('storage/' . $item['product']->image) }}" class="rounded" width="40" height="40" alt="{{ $item['product']->name }}" style="object-fit: cover;" />
                                                        @else
                                                            <span class="btn btn-sm btn-light rounded">
                                                                <i class="ti ti-package"></i>
                                                            </span>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0 fw-bolder">{{ $item['product']->name }}</h6>
                                                            <small class="text-muted">ID: {{ $item['product']->id }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-0">
                                                    <span class="fw-medium">${{ number_format($item['product']->price, 2) }}</span>
                                                </td>
                                                <td class="px-0 text-center">
                                                    <span class="badge bg-info">{{ $item['clicks'] }} lần</span>
                                                </td>
                                                <td class="px-0 text-center">
                                                    @if ($item['product']->status)
                                                        <span class="badge bg-success-subtle text-success">Hoạt động</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger">Tắt</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <p class="text-muted mb-0">Chưa có dữ liệu click sản phẩm hôm nay</p>
                                                <small class="text-muted">Hãy chờ người dùng click vào sản phẩm</small>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <!-- Card -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-0">Phản Hồi Của Người Dùng</h4>
                        <p class="card-subtitle text-muted">Những phản hồi gần đây nhất</p>
                    </div>
                    <div class="comment-widgets scrollable mb-2 common-widget" style="height: 465px" data-simplebar="">
                        @forelse ($recentComments as $item)
                            <div class="d-flex flex-row comment-row border-bottom p-3 gap-3">
                                <div>
                                    <span class="avatar bg-primary-subtle rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <strong class="text-primary">{{ strtoupper(substr($item['user_name'], 0, 1)) }}</strong>
                                    </span>
                                </div>
                                <div class="comment-text w-100">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="fw-medium mb-0">{{ $item['user_name'] }}</h6>
                                            <small class="text-muted">{{ $item['user_email'] }}</small>
                                        </div>
                                        <span class="badge bg-secondary-subtle text-secondary fs-2">
                                            {{ $item['item_type'] === 'article' ? 'Bài viết' : 'Sản phẩm' }}
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <p class="mb-2 fs-2 text-muted">{{ Str::limit($item['comment']->content, 120) }}</p>
                                        <small class="text-primary fw-bold">
                                            📌 {{ $item['item_name'] }}
                                        </small>
                                    </div>
                                    <div class="comment-footer mt-2">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="badge 
                                                @if ($item['comment']->status === 'approved')
                                                    bg-success-subtle text-success
                                                @elseif ($item['comment']->status === 'rejected')
                                                    bg-danger-subtle text-danger
                                                @else
                                                    bg-warning-subtle text-warning
                                                @endif
                                            ">
                                                {{ $item['comment']->status === 'pending' ? 'Chờ xét duyệt' : ($item['comment']->status === 'approved' ? 'Đã duyệt' : 'Từ chối') }}
                                            </span>
                                            <small class="text-muted">
                                                {{ $item['comment']->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center">
                                <p class="text-muted mb-0">Chưa có phản hồi nào</p>
                                <small class="text-muted">Các phản hồi sẽ hiển thị ở đây</small>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
           
        </div>

    </div>
</div>

<script>
// Truyền dữ liệu cho biểu đồ
window.threeDayStats = @json($threeDayStats);
</script>

<script>
// Analytics Tracking Script
document.addEventListener('DOMContentLoaded', function() {
    const analyticsTracker = {
        sessionId: null,
        startTime: Date.now(),
        clickCount: 0,
        
        init: function() {
            this.startSession();
            this.attachClickListeners();
            this.setupUnloadListener();
        },
        
        startSession: function() {
            fetch('/api/analytics/start-session', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                this.sessionId = data.session_id;
                console.log('Session started:', this.sessionId);
            })
            .catch(error => console.error('Error starting session:', error));
        },
        
        recordClick: function(itemType, itemId, pageUrl = null) {
            this.clickCount++;
            
            fetch('/api/analytics/record-click', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    item_type: itemType,
                    item_id: itemId,
                    page_url: pageUrl || window.location.href
                })
            })
            .catch(error => console.error('Error recording click:', error));
        },
        
        attachClickListeners: function() {
            // Theo dõi click trên các link bài viết
            document.querySelectorAll('[data-article-id]').forEach(el => {
                el.addEventListener('click', (e) => {
                    const articleId = el.getAttribute('data-article-id');
                    this.recordClick('article', articleId);
                });
            });
            
            // Theo dõi click trên các link sản phẩm
            document.querySelectorAll('[data-product-id]').forEach(el => {
                el.addEventListener('click', (e) => {
                    const productId = el.getAttribute('data-product-id');
                    this.recordClick('product', productId);
                });
            });
        },
        
        setupUnloadListener: function() {
            window.addEventListener('beforeunload', () => {
                const durationSeconds = Math.round((Date.now() - this.startTime) / 1000);
                
                if (this.sessionId) {
                    navigator.sendBeacon('/api/analytics/end-session', JSON.stringify({
                        session_id: this.sessionId,
                        duration_seconds: durationSeconds,
                        total_clicks: this.clickCount
                    }));
                }
            });
        }
    };
    
    analyticsTracker.init();
    
    // Tải thống kê hàng ngày
    function loadDailyStats() {
        fetch('/api/analytics/daily-stats')
            .then(response => response.json())
            .then(data => {
                document.getElementById('totalVisitors').textContent = data.total_visitors;
                document.getElementById('totalTime').textContent = data.total_time_minutes;
                document.getElementById('totalClicks').textContent = data.total_clicks;
            })
            .catch(error => console.error('Error loading stats:', error));
    }

    // Tải thống kê 3 ngày
    function loadThreeDayStats() {
        fetch('/api/analytics/three-day-stats')
            .then(response => response.json())
            .then(data => {
                // Cập nhật dữ liệu biểu đồ
                if (window.chart_column_basic) {
                    window.chart_column_basic.updateSeries([
                        {
                            name: "Bài viết",
                            data: data.map(item => item.article_clicks),
                        },
                        {
                            name: "Sản phẩm",
                            data: data.map(item => item.product_clicks),
                        },
                    ]);
                    window.chart_column_basic.updateOptions({
                        xaxis: {
                            categories: data.map(item => item.date_display)
                        }
                    });
                }

                // Cập nhật bảng lịch sử
                const historyContainer = document.querySelector('.card-body .mt-3');
                if (historyContainer) {
                    historyContainer.innerHTML = data.map(stat => `
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <div>
                                <h6 class="mb-0">${stat.date_display}</h6>
                                <small class="text-muted">Tổng: ${stat.total_clicks} click</small>
                            </div>
                            <div class="text-end">
                                <div class="d-flex gap-2">
                                    <span class="badge bg-primary">${stat.product_clicks} SP</span>
                                    <span class="badge bg-info">${stat.article_clicks} BV</span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                }
            })
            .catch(error => console.error('Error loading three day stats:', error));
    }
    
    // Tải thống kê lần đầu
    loadDailyStats();
    loadThreeDayStats();
    
    // Cập nhật thống kê mỗi 30 giây
    setInterval(loadDailyStats, 30000);
    setInterval(loadThreeDayStats, 30000);

    // Function export dữ liệu hôm nay
    function exportTodayData() {
        const today = new Date().toISOString().split('T')[0]; // YYYY-MM-DD format
        
        fetch(`/api/analytics/export-daily?date=${today}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Export failed');
                }
                return response.blob();
            })
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = `analytics_${today}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                document.body.removeChild(a);
            })
            .catch(error => {
                console.error('Error exporting data:', error);
                alert('Có lỗi khi xuất dữ liệu. Vui lòng thử lại.');
            });
    }
});
</script>

@endsection

<?php

?>
