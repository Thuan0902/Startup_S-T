@extends('welcome')

@section('content')
<style>
    .blog-nav {
        overflow-x: auto;
        white-space: nowrap;
        padding: 1rem 0;
    }

    .blog-nav::-webkit-scrollbar {
        display: none;
    }

    .blog-nav a {
        display: inline-flex;
        align-items: center;
        padding: 0.55rem 0.75rem;
        margin-right: 0.65rem;
        border-radius: 999px;
        border: 1px solid rgba(220, 53, 69, 0.16);
        color: #343a40;
        background: #fff;
        font-size: 0.7rem;
        font-weight: 600;
        transition: transform .2s ease, background .2s ease, border-color .2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .blog-nav a.active,
    .blog-nav a:hover {
        background: #dc3545;
        color: #fff;
        border-color: #dc3545;
        transform: translateY(-1px);
    }

    .blog-hero-card,
    .blog-entry-card,
    .blog-entry-card-small,
    .blog-category-card,
    .blog-product-card {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 20px 45px rgba(12, 21, 36, 0.08);
    }

    .blog-hero-card {
        overflow: hidden;
        min-height: 100%;
        max-width: 100%;
    }

    .blog-hero-wrapper {
        width: 100%;
        max-width: 60vw;
        margin: 0 auto;
        height: 440px;
    }

    .blog-hero-card {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .blog-hero-image {
        min-height: 260px;
        max-height: 280px;
        overflow: hidden;
        position: relative;
    }

    .blog-hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .blog-hero-badge {
        position: absolute;
        top: 1.25rem;
        left: 1.25rem;
        background: rgba(255, 255, 255, 0.95);
        color: #dc3545;
        padding: 0.45rem 0.85rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .blog-hero-body {
        padding: 1.3rem 1.3rem 1.5rem;
    }

    .blog-hero-body h2 {
        font-size: clamp(1.75rem, 2.2vw, 2.2rem);
        line-height: 1.08;
        margin-bottom: 0.9rem;
    }

    .blog-hero-body p {
        color: #495057;
        font-size: 0.92rem;
        margin-bottom: 1.1rem;
    }

    .blog-hero-body .hero-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        justify-content: space-between;
        align-items: center;
    }

    .blog-hero-body .hero-meta .text-muted {
        font-size: 0.9rem;
    }

    .blog-posts .blog-entry-card-small,
    .blog-entry-card-small {
        display: flex;
        flex-direction: row;
        gap: 1rem;
        padding: 0.85rem;
        overflow: hidden;
        min-height: 0;
        height: 100%;
        align-items: stretch;
    }

    .blog-entry-card-small .thumb {
        flex: 0 0 40%;
        min-width: 40%;
        min-height: 0;
        overflow: hidden;
        border-radius: 1rem;
    }

    .blog-entry-card-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .blog-posts .blog-entry-card-small .entry-content,
    .blog-entry-card-small .entry-content {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        flex: 1 1 60%;
        min-height: 0;
    }

    .blog-entry-card-small .entry-content p {
        margin-bottom: 0;
    }

    .blog-entry-card-small .meta {
        font-size: 0.74rem;
        color: #6c757d;
        margin-bottom: 0.35rem;
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .blog-entry-card-small h3 {
        font-size: 1rem;
        margin: 0;
        line-height: 1.35;
        max-height: 2.8rem;
        overflow: hidden;
    }

    .blog-entry-card-small p {
        margin: 0;
        color: #6c757d;
        font-size: 0.86rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .related-sidebar {
        display: grid;
        grid-template-rows: repeat(3, 1fr);
        gap: 1rem;
        height: 440px;
    }

    .related-sidebar .blog-entry-card-small {
        min-height: 0;
    }

    .related-panel-title {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 1rem;
        letter-spacing: 0.02em;
        color: #212529;
    }

    .blog-categories-grid {
        padding: 1.25rem;
        background: #f8f9fa;
        border-radius: 1.5rem;
        margin-top: 2rem;
    }

    .blog-category-card {
        padding: 1rem 1.2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform .2s ease, box-shadow .2s ease;
        gap: 1rem;
        min-height: 45px;
    }

    .blog-category-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 35px rgba(12, 21, 36, 0.08);
    }

    .blog-category-card span {
        font-weight: 700;
        color: #212529;
    }

    .blog-category-card small {
        color: #6c757d;
    }

    .blog-product-card {
        overflow: hidden;
        transition: transform .2s ease;
    }

    .blog-product-card:hover {
        transform: translateY(-4px);
    }

    .blog-product-card img {
        width: 100%;
        height: 220px;
        object-fit: cover;
    }

    .blog-product-card-body {
        padding: 1.3rem;
    }

    .blog-product-card-body h3 {
        font-size: 1.05rem;
        margin-bottom: 0.8rem;
        line-height: 1.35;
    }

    .blog-product-card-body p {
        color: #6c757d;
        margin-bottom: 1rem;
        min-height: 3rem;
    }

    .blog-product-card-body .tag {
        display: inline-flex;
        align-items: center;
        padding: 0.45rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        background: #f8f9fa;
        color: #dc3545;
    }

    .blog-tab-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .blog-tab-list a {
        padding: 0.7rem 1rem;
        border-radius: 999px;
        border: 1px solid #dee2e6;
        color: #495057;
        background: #fff;
        text-decoration: none;
        font-size: 0.92rem;
        transition: all .2s ease;
    }

    .blog-tab-list a.active,
    .blog-tab-list a:hover {
        border-color: #dc3545;
        color: #dc3545;
        background: #fff5f6;
    }

    .section-title h2 {
        font-size: clamp(1.9rem, 2.2vw, 2.7rem);
        margin-bottom: 0.5rem;
    }

    .section-title p {
        color: #495057;
        margin-bottom: 2rem;
        font-size: 1.05rem;
    }

    .badge-soft {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.45rem 0.75rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        color: #fff;
        background: #dc3545;
    }

    @media (max-width: 991px) {
        .blog-hero-image {
            min-height: 320px;
        }

        .blog-entry-card-small {
            flex-direction: column;
            max-height: none;
        }

        .blog-entry-card-small .thumb {
            width: 100%;
            min-width: auto;
            min-height: 180px;
        }
    }
</style>

<nav class="blog-nav container">
    <a href="{{ route('blog') }}" class="{{ request()->has('category') ? '' : 'active' }}">{{ $pageContent['nav_all'] ?? 'Tất cả' }}</a>
    @forelse ($categories as $category)
    <a href="{{ route('blog', ['category' => $category->id]) }}" class="{{ request('category') == $category->id ? 'active' : '' }}">
        {{ $category->name }}
    </a>
    @empty
    <p class="mb-0">Chưa có danh mục.</p>
    @endforelse
</nav>

<section id="blog-posts" class="blog-posts section" style="margin-top: -3%;">


    <div class="container">
        @if(empty($articles) || $articles->isEmpty())
        <div class="alert alert-light rounded-4">{{ $pageContent['empty_message'] ?? 'Hiện chưa có bài viết cho danh mục này.' }}</div>
        @else
        @php
        $featured = $articles->first();
        $sidebarArticles = $articles->skip(1)->take(3);
        $categoryCards = $categories->take(12);
        $productArticles = $articles->skip(5)->take(8);
        $featuredImage = $featured->images->first() ? asset('storage/' . $featured->images->first()->image_path) : asset('assets/img/img_h_2.jpg');
        @endphp

        <div class="row gy-4 align-items-stretch">

            <div class="col-xl-7 col-lg-8">
                <div class="blog-hero-wrapper mx-auto">
                    <a href="{{ route('article.show', $featured) }}" class="blog-hero-card text-decoration-none" data-aos="fade-up">
                        <div class="blog-hero-image">
                            <img src="{{ $featuredImage }}" alt="{{ $featured->title }}">
                            <span class="blog-hero-badge">{{ $pageContent['featured_badge'] ?? 'Gợi ý nổi bật' }}</span>
                        </div>
                        <div class="blog-hero-body">
                            <h2>{{ $featured->title }}</h2>
                            <p>{{ \Illuminate\Support\Str::limit($featured->description, 180, '...') }}</p>

                        </div>
                    </a>
                </div>
            </div>

            <div class="col-xl-5 col-lg-4">
                <div class="related-sidebar">
                    @foreach($sidebarArticles as $article)
                    @php
                        $thumb = $article->images->first() ? asset('storage/' . $article->images->first()->image_path) : asset('assets/img/img_h_4.jpg');
                        $articleCategories = $article->categories->pluck('name')->filter()->toArray();
                        if (empty($articleCategories) && $article->category) {
                            $articleCategories = [$article->category->name];
                        }
                        $articleOccasion = $article->occasion?->name;
                    @endphp
                    <article class="blog-entry-card-small" data-aos="fade-up" data-aos-delay="100">
                        <div class="thumb">
                            <img src="{{ $thumb }}" alt="{{ $article->title }}">
                        </div>
                        <div class="entry-content">
                            <div>
                                <div class="meta">
                                    {{ implode(' · ', $articleCategories ?: ['Bài viết']) }}
                                    @if($articleOccasion)
                                        · {{ $articleOccasion }}
                                    @endif
                                    · {{ optional($article->created_at)->format('M d, Y') }}
                                </div>
                                <h3><a href="{{ route('article.show', $article) }}" class="text-dark text-decoration-none">{{ \Illuminate\Support\Str::limit($article->title, 62) }}</a></h3>
                            </div>
                            <p>{{ \Illuminate\Support\Str::limit($article->description, 90, '...') }}</p>
                        </div>
                    </article>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="blog-categories-grid" data-aos="fade-up" data-aos-delay="150">
            <h3 class="mb-3">{{ $pageContent['categories_title'] ?? 'Khám phá theo danh mục' }}</h3>
            <div class="row g-3">
                @foreach($categoryCards as $categoryCard)
                <div class="col-6 col-md-4 col-xl-3">
                    <a href="{{ route('blog', ['category' => $categoryCard->id]) }}" class="blog-category-card text-decoration-none">
                        <div>
                            <span>{{ $categoryCard->name }}</span>
                        </div>
   
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        <div class="mt-5" data-aos="fade-up" data-aos-delay="180">
            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
                <div>
                    <h3>{{ $pageContent['products_title'] ?? 'Gợi ý thêm cho bạn' }}</h3>
                </div>
                <div class="blog-tab-list">
                    <a href="#" class="active">Women&apos;s Fashion</a>
                    <a href="#">Men&apos;s Fashion</a>
                    <a href="#">Unisex Fashion</a>
                    <a href="#">Kids&apos; Fashion</a>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
                @foreach($productArticles as $article)
                @php
                $thumb = $article->images->first() ? asset('storage/' . $article->images->first()->image_path) : asset('assets/img/img_h_2.jpg');
                @endphp
                <div class="col">
                    <article class="blog-product-card h-100">
                        <img src="{{ $thumb }}" alt="{{ $article->title }}">
                        <div class="blog-product-card-body">
                            <div class="tag">{{ $article->category?->name ?? 'Sản phẩm' }}</div>
                            <h3><a href="{{ route('article.show', $article) }}" class="text-dark text-decoration-none">{{ \Illuminate\Support\Str::limit($article->title, 62) }}</a></h3>
                            <p>{{ \Illuminate\Support\Str::limit($article->description, 110, '...') }}</p>
                        </div>
                    </article>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
