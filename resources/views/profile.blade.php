@extends('welcome')

@section('content')
<section class="container py-5">
    <h1 class="mb-4">{{ $pageContent['title'] ?? 'Danh sách mong muốn' }}</h1>
    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 3rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    @if($user->isVip())
                        <span class="badge bg-warning text-dark mb-3">VIP đến {{ $user->vip_expires_at->format('d/m/Y') }}</span>
                    @else
                        <span class="badge bg-secondary mb-3">Thường</span>
                    @endif

                    @if($user->bio)
                        <p class="text-muted small">{{ $user->bio }}</p>
                    @endif

                    @if(Auth::check() && Auth::id() === $user->id)
                        <div class="mt-3">
                            @if($user->isVip())
                                <a href="{{ route('user.create-article') }}" class="btn btn-primary btn-sm me-2">Tạo bài viết</a>
                                <a href="{{ route('user.create-product') }}" class="btn btn-success btn-sm">Tạo sản phẩm</a>
                            @else
                                <a href="#" class="btn btn-warning btn-sm" onclick="alert('Nâng cấp VIP để tạo nội dung')">Nâng cấp VIP</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Bài viết đã duyệt ({{ $articles->total() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($articles as $article)
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            @php
                                $thumb = $article->images->first() ? asset('storage/' . $article->images->first()->image_path) : asset('assets/img/img_h_2.jpg');
                            @endphp
                            <img src="{{ $thumb }}" alt="{{ $article->title }}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('article.show', $article) }}" class="text-decoration-none">{{ $article->title }}</a>
                                </h6>
                                <p class="text-muted small mb-1">{{ $article->description ? Str::limit($article->description, 100) : 'Không có mô tả' }}</p>
                                <small class="text-muted">{{ $article->created_at->format('d/m/Y') }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">{{ $pageContent['empty_message'] ?? 'Chưa có bài viết nào được duyệt.' }}</p>
                    @endforelse

                    {{ $articles->links() }}
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Sản phẩm đã duyệt ({{ $products->total() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($products as $product)
                        <div class="d-flex mb-3 pb-3 border-bottom">
                            @php
                                $thumb = $product->image ? asset('storage/' . $product->image) : asset('assets/img/img_h_2.jpg');
                            @endphp
                            <img src="{{ $thumb }}" alt="{{ $product->name }}" class="rounded me-3" style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $product->name }}</h6>
                                <p class="text-muted small mb-1">{{ $product->description ? Str::limit($product->description, 100) : 'Không có mô tả' }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $product->created_at->format('d/m/Y') }}</small>
                                    @if($product->price)
                                        <span class="fw-semibold text-primary">{{ number_format($product->price * 1000, 0, ',', '.') }} đ</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">{{ $pageContent['empty_message'] ?? 'Chưa có sản phẩm nào được duyệt.' }}</p>
                    @endforelse

                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
