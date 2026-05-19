@extends('welcome')

@section('content')
<style>
    .search-page {
        padding: 36px 0 56px;
        background: #fff;
    }

    .search-heading {
        margin-bottom: 24px;
    }

    .search-heading h1 {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 8px;
        color: #16181b;
    }

    .search-heading p {
        color: #6c757d;
        margin: 0;
    }

    .search-section {
        margin-top: 34px;
    }

    .search-section-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 16px;
    }

    .search-section-title h2 {
        font-size: 20px;
        font-weight: 800;
        margin: 0;
        color: #212529;
    }

    .search-card {
        height: 100%;
        border: 1px solid #edeff2;
        border-radius: 18px;
        overflow: hidden;
        background: #fff;
        text-decoration: none;
        color: inherit;
        display: block;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .search-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 30px rgba(12, 21, 36, 0.08);
    }

    .search-card img {
        width: 100%;
        height: 170px;
        object-fit: cover;
        display: block;
        border-bottom: 1px solid #f1f3f5;
    }

    .search-card-body {
        padding: 14px;
    }

    .search-card-body h3 {
        font-size: 16px;
        line-height: 1.35;
        font-weight: 800;
        margin: 0 0 8px;
        color: #111;
    }

    .search-card-body p {
        color: #5b5b5b;
        font-size: 14px;
        line-height: 1.45;
        margin-bottom: 12px;
    }

    .search-meta {
        color: #dc3545;
        font-size: 13px;
        font-weight: 700;
    }

    .occasion-chip {
        display: inline-flex;
        align-items: center;
        padding: 0.6rem 0.9rem;
        margin: 0 0.6rem 0.7rem 0;
        border-radius: 999px;
        border: 1px solid rgba(220, 53, 69, 0.16);
        background: #fff;
        color: #343a40;
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 700;
        transition: transform .2s ease, background .2s ease, color .2s ease;
    }

    .occasion-chip:hover {
        transform: translateY(-1px);
        background: #dc3545;
        color: #fff;
    }
</style>

<section class="search-page">
    <div class="container">
        <div class="search-heading">
            <h1>{{ $pageContent['title'] ?? 'Kết quả tìm kiếm' }}</h1>
            @if($search !== '')
                <p>Từ khóa: <strong>{{ $search }}</strong></p>
            @else
                <p>Nhập từ khóa vào thanh tìm kiếm để tìm sản phẩm, bài viết hoặc dịp.</p>
            @endif
        </div>

        @if($search !== '' && $products->isEmpty() && $articles->isEmpty() && $occasions->isEmpty())
            <div class="alert alert-light border">{{ $pageContent['empty_message'] ?? 'Không tìm thấy kết quả phù hợp.' }}</div>
        @endif

        @if($products->isNotEmpty())
            <div class="search-section">
                <div class="search-section-title">
                    <h2>Sản phẩm</h2>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
                    @foreach($products as $product)
                        <div class="col">
                            <a class="search-card" href="{{ $product->affiliate_link ?: route('team', ['occasion' => $product->occasion_id]) }}" target="{{ $product->affiliate_link ? '_blank' : '_self' }}">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/img_h_2.jpg') }}" alt="{{ $product->name }}">
                                <div class="search-card-body">
                                    <h3>{{ \Illuminate\Support\Str::limit($product->name, 45) }}</h3>
                                    <p>{{ \Illuminate\Support\Str::limit($product->description ?: $product->advantages ?: 'Sản phẩm chất lượng cao', 70) }}</p>
                                    <div class="search-meta">
                                        {{ $product->price !== null ? number_format((float) $product->price * 1000, 0, ',', '.') . ' đ' : 'Liên hệ' }}
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($articles->isNotEmpty())
            <div class="search-section">
                <div class="search-section-title">
                    <h2>Bài viết</h2>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                    @foreach($articles as $article)
                        @php
                            $articleImage = $article->images->first()?->image_path;
                        @endphp
                        <div class="col">
                            <a class="search-card" href="{{ route('article.show', $article) }}">
                                <img src="{{ $articleImage ? asset('storage/' . $articleImage) : asset('assets/img/img_h_2.jpg') }}" alt="{{ $article->title }}">
                                <div class="search-card-body">
                                    <h3>{{ \Illuminate\Support\Str::limit($article->title, 45) }}</h3>
                                    <p>{{ \Illuminate\Support\Str::limit($article->description ?: 'Bài viết đang được cập nhật nội dung.', 78) }}</p>
                                    <div class="search-meta">{{ $article->category?->name ?? $article->occasion?->name ?? 'Bài viết' }}</div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($occasions->isNotEmpty())
            <div class="search-section">
                <div class="search-section-title">
                    <h2>Dịp</h2>
                </div>
                <div>
                    @foreach($occasions as $occasion)
                        <a class="occasion-chip" href="{{ route('team', ['occasion' => $occasion->id]) }}">{{ $occasion->name }}</a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
