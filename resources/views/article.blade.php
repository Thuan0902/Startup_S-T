@extends('welcome')

@section('content')
<section class="container py-5" style="margin-top: -2%;">

    @php
    $heroImage = $article->images->first() ? asset('storage/' . $article->images->first()->image_path) : asset('assets/img/img_h_2.jpg');
    $secondaryImage = $article->images->get(1) ? asset('storage/' . $article->images->get(1)->image_path) : $heroImage;
    $guideItems = [
    'Sparkle for New Year\'s Eve',
    'Festive Christmas Party Looks',
    'Chic Cocktail Dress Edit',
    'Formal Black-Tie Gown Elegance',
    'Cozy Yet Polished Sweater Dresses',
    'Winter Whites and Metallics',
    'Flattering Fits for Every Body',
    'Little Black Dress Classics',
    ];
    $pickTags = ['BEST OVERALL', 'BEST VALUE', 'BEST BLACK-TIE GOWN', 'BEST SPARKLY MINI'];
    $pickPrices = ['$264.00', '$89.99', '$398.00', '$234.00'];
    @endphp

    <div class="position-relative rounded-4 overflow-hidden shadow-lg mb-5 article-hero" style="height:500px;">
        <img src="{{ $heroImage }}" alt="{{ $article->title }}" class="img-fluid w-100" style="height:100%; object-fit:cover; display:block;">
        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column justify-content-end p-4" style="background:linear-gradient(180deg, rgba(13,18,31,0) 0%, rgba(13,18,31,0.65) 45%, rgba(13,18,31,0.96) 100%);">
            <div class="text-white-50 text-uppercase small mb-2">
                {{ $article->occasion?->name ?? 'DỊP LỄ' }} · {{ $article->category?->name ?? 'DANH MỤC' }}
            </div>
            <h1 class="display-6 fw-bold text-white mb-4">{{ $article->title }}</h1>
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 text-white-75">
                <div class="d-flex align-items-center gap-3">
                    <div class="overflow-hidden rounded-circle bg-white" style="width:3.2rem; height:3.2rem;">
                        <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=200&q=80" alt="Author" class="w-100 h-100" style="object-fit:cover;">
                    </div>
                    <div>
                        <div class="fw-bold text-white">By Sophia Filly</div>
                        <div class="text-uppercase small text-white-50">Senior Writer</div>
                    </div>
                </div>
                <div class="small text-white-50">Published on {{ optional($article->created_at)->format('M d, Y') }}</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <p class="lead mb-4">{{ $article->description ?? 'Đọc tiếp để khám phá những gợi ý phong cách hot nhất và cách phối trang phục phù hợp cho những dịp lễ hội sắp tới.' }}</p>

                    <div class="card border-0 shadow-sm p-4 mb-4">
                        <h2 class="h4 mb-3">Tổng quan: Những lựa chọn hàng đầu của chúng tôi</h2>
                        <div class="list-group list-group-flush">
                            @foreach($topProducts as $product)
                            @php
                            $productImage = $product->image ? asset('storage/' . $product->image) : asset('assets/img/img_h_2.jpg');
                            @endphp
                            <div class="list-group-item border-0 p-0 mb-3 bg-transparent">
                                <div class="d-flex gap-3 align-items-start">
                                    <img src="{{ $productImage }}" alt="{{ $product->name }}" class="rounded-4" style="width:96px; height:96px; object-fit:cover;">
                                    <div class="flex-grow-1">
                                        <h3 class="h6 mb-1">{{ \Illuminate\Support\Str::limit($product->name, 60) }}</h3>
                                        <p class="text-muted mb-2">{{ \Illuminate\Support\Str::limit($product->description, 80) }}</p>
                                        <div class="fw-semibold text-dark" style="font-size: 1rem; font-family: monospace;">
                                            {{ $product->price !== null ? number_format((float) $product->price * 1000, 0, ',', '.') . ' đ' : 'Liên hệ' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @endforeach
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 p-3">
                                <div class="mb-3 text-uppercase small fw-bold text-secondary">What We Like</div>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">All-over sequin embellishment gives a high-impact, festive look for parties.</li>
                                    <li>Fully lined construction improves comfort under sequins.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 p-3">
                                <div class="mb-3 text-uppercase small fw-bold text-secondary">Room For Improvement</div>
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">Made with minimal stretch, so sizing can feel tight if between sizes.</li>
                                    <li class="mb-2">Dry clean only care requirement adds maintenance and cost over time.</li>
                                    <li>Sequin fabric can be delicate and may require careful handling to avoid snags.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h5 class="fw-bold mb-3">{{ $pageContent['related_title'] ?? 'Bài viết liên quan' }}</h5>
                <div class="d-flex flex-column gap-3">
                    @forelse($relatedArticles as $related)
                    @php
                    $relatedThumb = $related->images->first() ? asset('storage/' . $related->images->first()->image_path) : asset('assets/img/img_h_4.jpg');
                    @endphp
                    <a href="{{ route('article.show', $related) }}" class="d-flex align-items-start text-decoration-none text-dark gap-3">
                        <img src="{{ $relatedThumb }}" alt="{{ $related->title }}" class="rounded-4" style="width:84px; height:84px; object-fit:cover;">
                        <div>
                            <div class="small text-muted mb-1">{{ $related->category?->name ?? 'Danh mục khác' }}</div>
                            <div class="fw-semibold">{{ \Illuminate\Support\Str::limit($related->title, 68) }}</div>
                            <div class="text-muted small">{{ optional($related->created_at)->format('M d, Y') }}</div>
                        </div>
                    </a>
                    @empty
                    <p class="mb-0 text-muted">Không tìm thấy bài viết cùng danh mục.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
