@extends('welcome')

@section('content')
<style>
    .nav-item {
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        color: #dc3545;
        /* màu đỏ */
        font-size: 14px;
        padding-bottom: 6px;
        border-bottom: 2px solid transparent;
        transition: 0.2s;
        font-family: 'Inter', sans-serif;
    }

    .desc>h2 {
        text-transform: uppercase;
    }

    .desc>p {
        font-size: 200%;
        color: black;
        font-family: 'Inter', sans-serif;
        font-weight: 700;

    }

    .nav-item i {
        font-size: 16px;
    }

    .nav-item:hover {
        color: #a71d2a;
    }

    .nav-item.active {
        color: #dc3545;
        border-bottom: 2px solid #dc3545;
        font-weight: 500;
    }

    /* scroll ngang */
    .nav-scroll {
        overflow-x: auto;
        white-space: nowrap;
    }

    .nav-scroll::-webkit-scrollbar {
        display: none;
    }

    /* From Uiverse.io by satyamchaudharydev */
    .button {
        position: relative;
        transition: all 0.3s ease-in-out;
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
        padding-block: 0.5rem;
        padding-inline: 1.25rem;
        background-color: #dc3545;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #ffff;
        gap: 10px;
        font-weight: bold;
        border: 3px solid #ffffff4d;
        outline: none;
        overflow: hidden;
        font-size: 15px;
        cursor: pointer;
    }

    .icon {
        width: 24px;
        height: 24px;
        transition: all 0.3s ease-in-out;
    }

    .button:hover {
        transform: scale(1.05);
        border-color: #fff9;
    }

    .button:hover .icon {
        transform: translate(4px);
    }

    .button:hover::before {
        animation: shine 1.5s ease-out infinite;
    }

    .button::before {
        content: "";
        position: absolute;
        width: 100px;
        height: 100%;
        background-image: linear-gradient(120deg,
                rgba(255, 255, 255, 0) 30%,
                rgba(255, 255, 255, 0.8),
                rgba(255, 255, 255, 0) 70%);
        top: 0;
        left: -100px;
        opacity: 0.6;
    }

    @keyframes shine {
        0% {
            left: -100px;
        }

        60% {
            left: 100%;
        }

        to {
            left: 100%;
        }
    }

    .post-content {
        width: 100% !important;
        margin: 0 !important;
        padding: 15px;
    }

    .product-card {
        border: 1px solid #edeff2;
        border-radius: 20px;
        overflow: hidden;
        background: #fff;
        transition: transform .25s ease, box-shadow .25s ease;
        min-height: 100%;
    }

    .product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 16px 30px rgba(0, 0, 0, .08);
    }

    .product-thumb {
        display: block;
        overflow: hidden;
        border-bottom: 1px solid #f1f3f5;
    }

    .product-thumb img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        display: block;
        transition: transform .3s ease;
    }

    .product-thumb:hover img {
        transform: scale(1.03);
    }

    .product-body {
        padding: 14px;
    }

    .product-category {
        display: inline-block;
        margin-bottom: 8px;
        color: #6b7280;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .product-name {
        margin-bottom: 8px;
        font-size: 15px;
        line-height: 1.4;
        font-weight: 700;
    }

    .product-name a {
        color: #111;
        text-decoration: none;
    }

    .product-desc {
        color: #5b5b5b;
        margin-bottom: 12px;
        min-height: 48px;
        font-size: 14px;
    }

    .product-meta {
        gap: 10px;
        align-items: center;
    }

    .product-price {
        font-size: 16px;
        font-weight: 700;
        color: #dc3545;
    }

    .product-card .button {
        padding-block: 0.38rem;
        padding-inline: 0.95rem;
        font-size: 13px;
        border-width: 2px;
    }

    .product-add-button {
        position: absolute;
        right: 12px;
        top: 12px;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: none;
        background: rgba(0, 0, 0, .8);
        color: #fff;
        font-size: 22px;
        line-height: 1;
        display: grid;
        place-items: center;
        cursor: pointer;
        box-shadow: 0 12px 24px rgba(0, 0, 0, .16);
    }

    .product-add-button:hover {
        transform: translateY(-1px);
        background: rgba(0, 0, 0, .9);
    }

    .product-meta {
        gap: 12px;
    }

    .product-price {
        font-size: 18px;
        font-weight: 700;
        color: #dc3545;
    }

    .team-occasion-section {
        padding: 0 !important;
        margin: 0;
    }

    .team-occasion-nav {
        overflow-x: auto;
        white-space: nowrap;
        padding: 12px 0;
    }

    .team-occasion-nav::-webkit-scrollbar {
        display: none;
    }

    .team-occasion-nav .d-flex {
        display: block !important;
    }

    .team-occasion-nav a.nav-item {
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

    .team-occasion-nav a.nav-item.active,
    .team-occasion-nav a.nav-item:hover {
        background: #dc3545;
        color: #fff;
        border-color: #dc3545;
        border-bottom-color: #dc3545;
        transform: translateY(-1px);
    }
</style>


<section class="bg-white team-occasion-section">
    <div class="team-occasion-nav container">


        <div class="d-flex flex-wrap align-items-center gap-2">
            <a href="{{ route('team', ['occasion' => null, 'category' => request('category')]) }}" class="nav-item {{ !request('occasion') ? 'active' : '' }}">{{ $pageContent['filter_all'] ?? 'Tất cả dịp' }}</a>
            @forelse ($occasions as $occasion)
                <a href="{{ route('team', ['occasion' => $occasion->id, 'category' => request('category')]) }}" class="nav-item {{ request('occasion') == $occasion->id ? 'active' : '' }}">
                    {{ $occasion->name }}
                </a>
            @empty
                <p class="mb-0">Không có dịp nào.</p>
            @endforelse
        </div>
    </div>
    
</section>

<section id="product-list" class="blog-posts section py-4">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-3">
            @forelse ($products as $product)
                <div class="col">
                    <article class="product-card position-relative" data-aos="fade-up" data-aos-delay="150">
                        <a href="{{ $product->affiliate_link ?: '#' }}" target="_blank" class="product-thumb d-block">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('assets/img/img_h_2.jpg') }}" alt="{{ $product->name }}" class="img-fluid rounded-top">
                        </a>
                        <button type="button" class="product-add-button">+</button>
                        <div class="product-body">
                            <h3 class="product-name"><a href="{{ $product->affiliate_link ?: '#' }}" target="_blank">{{ \Illuminate\Support\Str::limit($product->name, 35) }}</a></h3>
                            <p class="product-desc">{{ \Illuminate\Support\Str::limit($product->description ?: $product->advantages ?: 'Sản phẩm chất lượng cao', 55, '...') }}</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="product-price" style="font-size: 80%">
                                    {{ $product->price !== null ? number_format((float) $product->price * 1000, 0, ',', '.') . ' đ' : 'Liên hệ' }}
                                </span>
                                @if ($product->occasion)
                                    <span class="text-muted small">{{ $product->occasion->name }}</span>
                                @endif
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-secondary text-center">Chưa có sản phẩm nào để hiển thị.</div>
                </div>
            @endforelse
        </div>
    </div>
</section><!-- /Product List Section -->

@endsection
