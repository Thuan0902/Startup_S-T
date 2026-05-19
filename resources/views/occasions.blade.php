@extends('welcome')

@section('content')
<style>
    .page-hero {
        padding: 3rem 0 1rem;
    }

    .team-occasion-nav {
        overflow-x: auto;
        white-space: nowrap;
        padding: 1rem 0 0;
    }

    .team-occasion-nav::-webkit-scrollbar {
        display: none;
    }

    .team-occasion-nav .nav-item {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.65rem 0.95rem;
        margin-right: 0.65rem;
        border-radius: 999px;
        border: 1px solid rgba(220, 53, 69, 0.18);
        color: #343a40;
        background: #fff;
        font-size: 0.82rem;
        font-weight: 600;
        transition: transform .2s ease, background .2s ease, border-color .2s ease;
        text-decoration: none;
        white-space: nowrap;
    }

    .team-occasion-nav .nav-item.active,
    .team-occasion-nav .nav-item:hover {
        background: #dc3545;
        color: #fff;
        border-color: #dc3545;
        transform: translateY(-1px);
    }

    .blog-nav {
        overflow-x: auto;
        white-space: nowrap;
        padding: 1rem 0 0;
    }

    .blog-nav::-webkit-scrollbar {
        display: none;
    }

    .blog-nav a {
        display: inline-flex;
        align-items: center;
        padding: 0.55rem 0.9rem;
        margin-right: 0.65rem;
        border-radius: 999px;
        border: 1px solid rgba(108, 117, 125, 0.25);
        color: #495057;
        background: #fff;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: all .2s ease;
    }

    .blog-nav a.active,
    .blog-nav a:hover {
        background: #f8f1f2;
        color: #dc3545;
        border-color: #dc3545;
    }

    .blog-hero-card,
    .blog-entry-card-small,
    .blog-category-card,
    .product-card {
        background: #fff;
        border-radius: 1.5rem;
        box-shadow: 0 20px 45px rgba(12, 21, 36, 0.08);
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
        padding: 1.3rem;
    }

    .blog-hero-body h2 {
        font-size: clamp(1.75rem, 2.2vw, 2.2rem);
        line-height: 1.08;
        margin-bottom: 0.9rem;
    }

    .blog-hero-body p {
        color: #495057;
        margin-bottom: 1.1rem;
    }

    .blog-entry-card-small {
        display: flex;
        gap: 1rem;
        padding: 0.85rem;
        margin-bottom: 1rem;
        overflow: hidden;
    }

    .blog-entry-card-small .thumb {
        flex: 0 0 120px;
        min-width: 120px;
        min-height: 120px;
        overflow: hidden;
        border-radius: 1rem;
    }

    .blog-entry-card-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .blog-entry-card-small .entry-content {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        width: 100%;
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

    .blog-category-card {
        padding: 1rem 1.2rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform .2s ease, box-shadow .2s ease;
        gap: 1rem;
        min-height: 55px;
        color: #212529;
        border: 1px solid #eceeef;
    }

    .blog-category-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 16px 35px rgba(12, 21, 36, 0.08);
    }

    .blog-category-card span {
        font-weight: 700;
    }

    .blog-category-card small {
        color: #6c757d;
    }

    .product-card,
    .article-card {
        overflow: hidden;
        transition: transform .2s ease, box-shadow .2s ease;
        border: 1px solid #edeff2;
        background: #fff;
        border-radius: 1.5rem;
    }

    .product-card:hover,
    .article-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 30px rgba(0, 0, 0, 0.08);
    }

    .product-thumb,
    .article-thumb {
        display: block;
        overflow: hidden;
    }

    .product-thumb img,
    .article-thumb img {
        width: 100%;
        height: 190px;
        object-fit: cover;
        display: block;
        transition: transform .3s ease;
    }

    .product-thumb:hover img,
    .article-thumb:hover img {
        transform: scale(1.03);
    }

    .product-body,
    .article-body {
        padding: 1rem 1rem 1.2rem;
    }

    .product-name,
    .article-name {
        margin-bottom: 0.6rem;
        font-size: 1rem;
        line-height: 1.4;
        font-weight: 700;
    }

    .product-name a,
    .article-name a {
        color: #111;
        text-decoration: none;
    }

    .product-desc,
    .article-desc {
        color: #5b5b5b;
        margin-bottom: 0.9rem;
        min-height: 48px;
        font-size: 0.92rem;
    }

    .product-meta,
    .article-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        font-size: 0.92rem;
    }

    .product-price {
        font-size: 1rem;
        font-weight: 700;
        color: #dc3545;
    }

    .article-tag {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.8rem;
        border-radius: 999px;
        background: #f8f9fa;
        color: #dc3545;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        margin-bottom: 0.8rem;
    }

    .section-title h2 {
        font-size: clamp(1.9rem, 2.2vw, 2.7rem);
        margin-bottom: 0.5rem;
    }

    .section-title p {
        color: #495057;
        margin-bottom: 1.5rem;
        font-size: 1rem;
    }

    @media (max-width: 991px) {
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

<section class="page-hero bg-white py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h1 class="display-6">{{ $pageContent['page_title'] ?? 'Khám phá dịp trong năm' }}</h1>
            <p class="text-muted mb-0">{{ $pageContent['page_description'] ?? 'Chọn danh mục và dịp phù hợp để tìm bài viết và gợi ý sản phẩm cho mọi khoảnh khắc.' }}</p>
        </div>

        <div class="blog-nav mt-4">
            <a href="{{ route('occasions', ['occasion' => request('occasion'), 'category' => null]) }}" class="{{ !request('category') ? 'active' : '' }}">{{ $pageContent['filter_all'] ?? 'Tất cả dịp' }}</a>
            @foreach($occasions as $occasion)
            <a href="{{ route('occasions', ['occasion' => $occasion->id, 'category' => request('category')]) }}" class="nav-item {{ request('occasion') == $occasion->id ? 'active' : '' }}">{{ $occasion->name }}</a>
            @endforeach
        </div>
    </div>
</section>

<!-- <section class="section py-5">
    <div class="container">
        <div class="section-title text-center mb-4">
            <h2>{{ $pageContent['categories_section_title'] ?? 'Danh mục bài viết' }}</h2>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            @foreach($categories as $category)
            <div class="col">
                <a href="{{ route('occasions', ['category' => $category->id, 'occasion' => request('occasion')]) }}" class="blog-category-card d-block text-decoration-none">
                    <span>{{ $category->name }}</span>
                    <small>Khám phá</small>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section> -->

<section class="blog-posts section" style="margin-top: -2%;">
    <div class="container">
        <div class="section-title text-center mb-4">
            <h2>{{ $pageContent['articles_section_title'] ?? 'Bài viết phù hợp dịp' }}</h2>
        </div>

        @if(empty($articles) || $articles->isEmpty())
        <div class="alert alert-light rounded-4">Hiện chưa có bài viết cho bộ lọc này.</div>
        @else
        @php
        $featured = $articles->first();
        $sidebarArticles = $articles->skip(1)->take(4);
        $categoryCards = $categories->take(12);
        $featuredImage = $featured->images->first() ? asset('storage/' . $featured->images->first()->image_path) : asset('assets/img/img_h_2.jpg');
        @endphp



        @php
        $articleCards = $articles->skip(1)->take(8);
        @endphp
        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
            @foreach($articleCards as $article)
            @php
            $thumb = $article->images->first() ? asset('storage/' . $article->images->first()->image_path) : asset('assets/img/img_h_4.jpg');
            @endphp
            <div class="col">
                <article class="article-card h-100" data-aos="fade-up">
                    <a href="{{ route('article.show', $article) }}" class="article-thumb d-block">
                        <img src="{{ $thumb }}" alt="{{ $article->title }}">
                    </a>
                    <div class="article-body">
                        <span class="article-tag">{{ $article->occasion?->name ?? 'Dịp' }}</span>
                        <h3 class="article-name"><a href="{{ route('article.show', $article) }}" class="text-dark text-decoration-none">{{ \Illuminate\Support\Str::limit($article->title, 60) }}</a></h3>
                        <p class="article-desc">{{ \Illuminate\Support\Str::limit($article->description, 95, '...') }}</p>
                        <div class="article-meta">
                            <span>{{ $article->category?->name ?? 'Danh mục' }}</span>
                            <span class="text-muted">{{ optional($article->created_at)->format('d M, Y') }}</span>
                        </div>
                    </div>
                </article>
            </div>
            @endforeach
        </div>


        @endif
    </div>
</section>

<section class="section py-5">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 align-items-start mb-4">
            <div>
                <h2>{{ $pageContent['products_section_title'] ?? 'Gợi ý sản phẩm' }}</h2>
                <p class="text-muted mb-0">{{ $pageContent['products_section_description'] ?? 'Sản phẩm phù hợp với dịp và danh mục bạn đang tìm.' }}</p>
            </div>

        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-4">
            @forelse($products as $product)
            <div class="col">
                <article class="product-card h-100" data-aos="fade-up">
                    <a href="{{ $product->affiliate_link ?: '#' }}" target="_blank" class="product-thumb d-block">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/img_h_2.jpg') }}" alt="{{ $product->name }}">
                    </a>
                    <div class="product-body">
                        <div class="product-category">{{ $product->category?->name ?? 'Danh mục' }}</div>
                        <h3 class="product-name"><a href="{{ $product->affiliate_link ?: '#' }}" target="_blank">{{ \Illuminate\Support\Str::limit($product->name, 58) }}</a></h3>
                        <p class="product-desc">{{ \Illuminate\Support\Str::limit($product->description ?: $product->advantages ?: 'Sản phẩm phù hợp mọi dịp.', 85, '...') }}</p>
                        <div class="product-meta">
                            <span class="product-price">{{ $product->price !== null ? number_format((float) $product->price * 1000, 0, ',', '.') . ' đ' : 'Liên hệ' }}</span>
                            <span class="text-muted" style="font-size:.85rem;">{{ $product->occasion?->name ?? 'Dịp' }}</span>
                        </div>
                    </div>
                </article>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-secondary text-center">Chưa có sản phẩm phù hợp.</div>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection