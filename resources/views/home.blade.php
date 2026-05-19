@extends('welcome')

@section('content')

@if (session('success'))
<div class="container mt-4">
    <div class="alert alert-success">{{ session('success') }}</div>
</div>
@endif

@if (session('error'))
<div class="container mt-4">
    <div class="alert alert-danger">{{ session('error') }}</div>
</div>
@endif





<main class="main">

    <!-- About Section -->
    <section id="about" class="about section">

        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-lg-7 mb-5 mb-lg-0 order-lg-2" data-aos="fade-up" data-aos-delay="400">
                    <div class="swiper init-swiper">
                        <script type="application/json" class="swiper-config">
                            {
                                "loop": true,
                                "speed": 600,
                                "autoplay": {
                                    "delay": 5000
                                },
                                "slidesPerView": "auto",
                                "pagination": {
                                    "el": ".swiper-pagination",
                                    "type": "bullets",
                                    "clickable": true
                                },
                                "breakpoints": {
                                    "320": {
                                        "slidesPerView": 1,
                                        "spaceBetween": 40
                                    },
                                    "1200": {
                                        "slidesPerView": 1,
                                        "spaceBetween": 1
                                    }
                                }
                            }
                        </script>
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ !empty($pageContent['hero_slide_1']) ? asset('storage/' . $pageContent['hero_slide_1']) : asset('assets/img/img_h_6.jpg') }}" alt="Image" class="img-fluid">
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ !empty($pageContent['hero_slide_2']) ? asset('storage/' . $pageContent['hero_slide_2']) : asset('assets/img/img_h_7.jpg') }}" alt="Image" class="img-fluid">
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ !empty($pageContent['hero_slide_3']) ? asset('storage/' . $pageContent['hero_slide_3']) : asset('assets/img/img_h_8.jpg') }}" alt="Image" class="img-fluid">
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
                <div class="col-lg-4 order-lg-1">
                    <span class="section-subtitle" data-aos="fade-up">{{ $pageContent['hero_subtitle'] ?? 'Chào Mừng' }}</span>
                    <h1 class="mb-4" data-aos="fade-up">
                        {{ $pageContent['hero_title'] ?? 'Tạo danh sách mong muốn của bạn' }}
                    </h1>
                    <p data-aos="fade-up">
                        {{ $pageContent['hero_description'] ?? 'Hãy tạo danh sách của riêng bạn cho các dịp trọng đại (Sinh nhật, Giáng sinh, Lễ Tết). Thêm mặt hàng từ cửa hàng bất kỳ và chia sẻ với mọi người bạn yêu quý.' }}

                    </p>
                    <p class="mt-5" data-aos="fade-up">
                        <a href="#" class="btn btn-get-started">{{ $pageContent['hero_button'] ?? 'KHÁM PHÁ NGAY' }}</a>
                    </p>
                </div>
            </div>
        </div>
    </section><!-- /About Section -->
    <!-- Tabs Section -->
    <section id="tabs" class="tabs section light-background">

        <div class="container">
            <div class="row gap-x-lg-4 justify-content-between">
                <div class="col-lg-4 js-custom-dots">
                    <a href="#" class="service-item link horizontal d-flex active" data-aos="fade-left" data-aos-delay="0">
                        <div class="service-icon color-1 mb-4">
                            <i class="bi bi-alarm"></i>
                        </div>
                        <!-- /.icon -->
                        <div class="service-contents">
                            <h3>1. LẬP DANH SÁCH YÊU THƯƠNG CỦA BẠN</h3>
                            <p>
                                Lưu quà và sản phẩm yêu thích từ Shopee, Lazada, TikTok Shop cho mọi dịp quan trọng.
                            </p>
                        </div>
                        <!-- /.service-contents-->
                    </a>
                    <!-- /.service -->

                    <a href="#" class="service-item link horizontal d-flex" data-aos="fade-left" data-aos-delay="100">
                        <div class="service-icon color-2 mb-4">
                            <i class="bi bi-bag-check"></i>
                        </div>
                        <!-- /.icon -->
                        <div class="service-contents">
                            <h3>2. XEM ĐÁNH GIÁ KHÁCH QUAN</h3>
                            <p>
                                Theo dõi nhận xét trung lập, chọn lọc từ đội ngũ quản lý thông tin.
                            </p>
                        </div>
                        <!-- /.service-contents-->
                    </a>
                    <!-- /.service -->

                    <a href="#" class="service-item link horizontal d-flex" data-aos="fade-left" data-aos-delay="200">
                        <div class="service-icon color-3 mb-4">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <!-- /.icon -->
                        <div class="service-contents">
                            <h3>3. CHỌN SẢN PHẨM PHÙ HỢP</h3>
                            <p>
                                Theo dõi giá, nhận báo giảm giá và quản lý danh sách trên mọi thiết bị.
                            </p>
                        </div>
                        <!-- /.service-contents-->
                    </a>
                    <!-- /.service -->

                    <a href="#" class="service-item link horizontal d-flex" data-aos="fade-left" data-aos-delay="300">
                        <div class="service-icon color-4 mb-4">
                            <i class="bi bi-easel"></i>
                        </div>
                        <!-- /.icon -->
                        <div class="service-contents">
                            <h3>4. CHIA SẺ DỄ DÀNG</h3>
                            <p>
                                Gửi danh sách yêu thích cho người thân, tránh mua trùng nhờ tính năng đánh dấu đã mua.
                            </p>
                        </div>
                        <!-- /.service-contents-->
                    </a>
                    <!-- /.service -->
                </div>

                <div class="col-lg-8">
                    <div class="swiper init-swiper-tabs">
                        <script type="application/json" class="swiper-config">
                            {
                                "loop": true,
                                "speed": 600,
                                "autoHeight": true,
                                "autoplay": {
                                    "delay": 5000
                                },
                                "slidesPerView": "auto",
                                "pagination": {
                                    "el": ".swiper-pagination",
                                    "type": "bullets",
                                    "clickable": true
                                },
                                "breakpoints": {
                                    "320": {
                                        "slidesPerView": 1,
                                        "spaceBetween": 40
                                    },
                                    "1200": {
                                        "slidesPerView": 1,
                                        "spaceBetween": 1
                                    }
                                }
                            }
                        </script>
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="{{ !empty($pageContent['tabs_slide_1']) ? asset('storage/' . $pageContent['tabs_slide_1']) : asset('assets/img/img_h_1.jpg') }}" alt="Image" class="img-fluid">
                                <!-- <div class="p-4">
                                    <h3 class="text-black h5 mb-3">Modern and clean design</h3>
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <p>
                                                Far far away, behind the word mountains, far from the
                                                countries Vokalia and Consonantia, there live the blind
                                                texts. Separated they live in Bookmarksgrove right at the
                                                coast of the Semantics, a large language ocean.
                                            </p>
                                            <p>
                                                A small river named Duden flows by their place and
                                                supplies it with the necessary regelialia. It is a
                                                paradisematic country, in which roasted parts of sentences
                                                fly into your mouth.
                                            </p>
                                        </div>
                                        <div class="col-lg-4">
                                            <ul class="list-unstyled list-check">
                                                <li>Far far away, behind the word</li>
                                                <li>Far from the countries Vokalia</li>
                                                <li>Separated they live in Bookmarksgrove</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ !empty($pageContent['tabs_slide_2']) ? asset('storage/' . $pageContent['tabs_slide_2']) : asset('assets/img/img_h_2.jpg') }}" alt="Image" class="img-fluid">
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ !empty($pageContent['tabs_slide_3']) ? asset('storage/' . $pageContent['tabs_slide_3']) : asset('assets/img/img_h_3.jpg') }}" alt="Image" class="img-fluid">
                            </div>
                            <div class="swiper-slide">
                                <img src="{{ !empty($pageContent['tabs_slide_4']) ? asset('storage/' . $pageContent['tabs_slide_4']) : asset('assets/img/img_h_4.jpg') }}" alt="Image" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- /Tabs Section -->



    <!-- Stats Section -->
    <section id="stats" class="stats section light-background">

        <div class="container">

            <div class="row gy-4 justify-content-center">

                <div class="col-lg-5">
                    <div class="images-overlap">
                        <img src="{{ !empty($pageContent['stats_image']) ? asset('storage/' . $pageContent['stats_image']) : asset('assets/img/img_v_1.jpg') }}" alt="student" class="img-fluid img-1" data-aos="fade-up">
                    </div>
                </div>

                <div class="col-lg-4 ps-lg-5">
                    <span class="content-subtitle">{{ $pageContent['stats_subtitle'] ?? 'Thống Kê' }}</span>
                    <h2 class="content-title">{{ $pageContent['stats_title'] ?? 'Sự Hài Lòng Của Khách Hàng' }}</h2>
                    <p class="lead">
                        {{ $pageContent['stats_description'] ?? 'Sự hài lòng của khách hàng là ưu tiên hàng đầu của chúng tôi. Chúng tôi cam kết cung cấp dịch vụ chất lượng và sản phẩm tốt nhất để đáp ứng nhu cầu của bạn.' }}
                    </p>
                    <p class="mb-5">
                        Thống kê được những con số ấn tượng về sự hài lòng của khách hàng, chúng tôi tự hào về những thành tựu đã đạt được và cam kết tiếp tục cải thiện để mang đến trải nghiệm tốt nhất cho bạn.
                    </p>
                    <div class="row mb-5 count-numbers">

                        <!-- Start Stats Item -->
                        <div class="col-4 counter" data-aos="fade-up" data-aos-delay="100">
                            <span data-purecounter-separator="true" data-purecounter-start="0" data-purecounter-end="3919" data-purecounter-duration="1" class="purecounter number"></span>
                            <span class="d-block">Khách Hàng</span>
                        </div>
                        <!-- End Stats Item -->

                        <!-- Start Stats Item -->
                        <div class="col-4 counter" data-aos="fade-up" data-aos-delay="200">
                            <span data-purecounter-separator="true" data-purecounter-start="0" data-purecounter-end="2831" data-purecounter-duration="1" class="purecounter number"></span>
                            <span class="d-block">Lưu Lượng Khách Hàng</span>
                        </div>
                        <!-- End Stats Item -->

                        <!-- Start Stats Item -->
                        <div class="col-4 counter" data-aos="fade-up" data-aos-delay="300">
                            <span data-purecounter-separator="true" data-purecounter-start="0" data-purecounter-end="1914" data-purecounter-duration="1" class="purecounter number"></span>
                            <span class="d-block">Đánh Giá</span>
                        </div>
                        <!-- End Stats Item -->

                    </div>
                </div>

            </div>

        </div>
    </section><!-- /Stats Section -->


    <section id="services-2" class="services-2 section">

        <div class="container">
            <div class="row justify-content-center" data-aos="fade-up">
                <div class="col-md-6 col-lg-4">
                    <span class="content-subtitle">{{ $pageContent['services_subtitle'] ?? 'Dịch vụ' }}</span>
                    <h2 class="content-title">
                        {{ $pageContent['services_title'] ?? 'Công cụ gợi ý quà tặng cho mọi dịp' }}
                    </h2>
                    <p class="lead">
                        {{ $pageContent['services_description'] ?? 'Lưu ý tưởng, so sánh sản phẩm, theo dõi giá và chia sẻ danh sách yêu thích với người thân một cách dễ dàng.' }}
                    </p>
                    <p class="mb-5">
                        Separated they live in Bookmarksgrove right at the coast of the
                        Semantics, a large language ocean.
                    </p>
                    <p>
                        <a href="#" class="btn btn-get-started">{{ $pageContent['services_button'] ?? 'Bắt đầu' }}</a>
                    </p>
                </div>
                <div class="col-md-6 col-lg-6 ps-lg-5">
                    <div class="row">
                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="services-item" data-aos="fade-up" data-aos-delay="">
                                <div class="services-icon">
                                    <i class="bi bi-search"></i>
                                </div>
                                <div>
                                    <h3>Square</h3>
                                    <p>Separated they live in Bookmarksgrove right at the coast</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="services-item" data-aos="fade-up" data-aos-delay="100">
                                <div class="services-icon">
                                    <i class="bi bi-command"></i>
                                </div>
                                <div>
                                    <h3>Technology</h3>
                                    <p>Separated they live in Bookmarksgrove right at the coast</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="services-item" data-aos="fade-up" data-aos-delay="200">
                                <div class="services-icon">
                                    <i class="bi bi-grid"></i>
                                </div>
                                <div>
                                    <h3>Brilliant Ideas</h3>
                                    <p>Separated they live in Bookmarksgrove right at the coast</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-6 col-sm-6 col-md-6 col-lg-6">
                            <div class="services-item" data-aos="fade-up" data-aos-delay="300">
                                <div class="services-icon">
                                    <i class="bi bi-globe"></i>
                                </div>
                                <div>
                                    <h3>Blueprint</h3>
                                    <p>Separated they live in Bookmarksgrove right at the coast</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- /Services 2 Section -->

    <!-- Blog Posts Section -->
    <section id="blog-posts" class="blog-posts section">
        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <p>{{ $pageContent['blog_kicker'] ?? 'Gợi Ý Sản Phẩm' }}</p>
            <h2>{{ $pageContent['blog_title'] ?? 'Những Bài Viết Mới Nhất' }}</h2>
        </div><!-- End Section Title -->
        <div class="container">

            <div class="row gy-4">
                @forelse($latestArticles as $article)
                @php
                $thumb = $article->images->first() ? asset('storage/' . $article->images->first()->image_path) : asset('assets/img/img_h_4.jpg');
                $categoryName = $article->category?->name ?? 'Bài viết';
                $authorName = $article->user?->name ?? 'AGIFT';
                @endphp
                <div class="col-md-6 col-lg-4">
                    <div class="post-entry" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 + 100 }}">
                        <a href="{{ route('article.show', $article) }}" class="thumb d-block"><img src="{{ $thumb }}" alt="{{ $article->title }}" class="img-fluid rounded"></a>

                        <div class="post-content">
                            <div class="meta">
                                <a href="{{ route('article.show', $article) }}" class="cat">{{ $categoryName }}</a> •
                                <span class="date">{{ optional($article->created_at)->format('M d, Y') }}</span>
                            </div>
                            <h3><a href="{{ route('article.show', $article) }}">{{ \Illuminate\Support\Str::limit($article->title, 62) }}</a></h3>
                            <p>
                                {{ \Illuminate\Support\Str::limit($article->description, 110, '...') }}
                            </p>

                            <div class="d-flex author align-items-center">
                                <div class="pic">
                                    <img src="assets/img/team/team-3.jpg" alt="Image" class="img-fluid rounded-circle">
                                </div>
                                <div class="author-name">
                                    <strong class="d-block">{{ $authorName }}</strong>
                                    <span class="">Lead Product Designer</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-secondary">Hiện chưa có bài viết mới nhất để hiển thị.</div>
                </div>
                @endforelse
            </div>
        </div>
    </section><!-- /Blog Posts Section -->



    <!-- Services 2 Section -->

    <!-- Pricing Section -->
    <section id="pricing" class="pricing section light-background">


    </section><!-- /Pricing Section -->

    <!-- Faq Section -->
    <section id="faq" class="faq section">
        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <p>{{ $pageContent['faq_kicker'] ?? 'Hỏi đáp' }}</p>
            <h2>{{ $pageContent['faq_title'] ?? 'Câu Hỏi Thường Gặp' }}</h2>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up">
            <div class="row">
                <div class="col-12">
                    <div class="custom-accordion" id="accordion-faq">
                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-faq-1">
                                    Tất cả mọi thắc mắc của bạn – Chúng tôi luôn sẵn sàng lắng nghe
                                </button>
                            </h2>

                            <div id="collapse-faq-1" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion-faq">
                                <div class="accordion-body">
                                    Far far away, behind the word mountains, far from the countries
                                    Vokalia and Consonantia, there live the blind texts. Separated
                                    they live in Bookmarksgrove right at the coast of the Semantics,
                                    a large language ocean.
                                </div>
                            </div>
                        </div>
                        <!-- .accordion-item -->

                        <div class="accordion-item">
                            <h2 class="mb-0">
                                <button class="btn btn-link collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-faq-2" "="">
                Không tìm thấy thứ bạn cần? Vui lòng liên hệ với đội ngũ hỗ trợ khách hàng của chúng tôi.
              </button>
            </h2>
            <div id=" collapse-faq-2" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion-faq">
                                    <div class="accordion-body">
                                        A small river named Duden flows by their place and supplies it
                                        with the necessary regelialia. It is a paradisematic country, in
                                        which roasted parts of sentences fly into your mouth.
                                    </div>
                        </div>
                    </div>
                    <!-- .accordion-item -->

                    <!-- .accordion-item -->

                </div>
            </div>
        </div>
        </div>
    </section><!-- /Faq Section -->

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials section">
        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>{{ $pageContent['testimonials_title'] ?? 'TRẢI NGHIỆM NGƯỜI DÙNG TẠI AGIFT' }}</h2>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="swiper init-swiper">
                        <script type="application/json" class="swiper-config">
                            {
                                "loop": true,
                                "speed": 600,
                                "autoplay": {
                                    "delay": 5000
                                },
                                "slidesPerView": "auto",
                                "pagination": {
                                    "el": ".swiper-pagination",
                                    "type": "bullets",
                                    "clickable": true
                                },
                                "breakpoints": {
                                    "320": {
                                        "slidesPerView": 1,
                                        "spaceBetween": 40
                                    },
                                    "1200": {
                                        "slidesPerView": 1,
                                        "spaceBetween": 1
                                    }
                                }
                            }
                        </script>
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="testimonial mx-auto">
                                    <figure class="img-wrap">
                                        <img src="assets/img/testimonials/testimonials-1.jpg" alt="Image" class="img-fluid">
                                    </figure>
                                    <h3 class="name">Adam Aderson</h3>
                                    <blockquote>
                                        <p>
                                            “There live the blind texts. Separated they live in
                                            Bookmarksgrove right at the coast of the Semantics, a large
                                            language ocean.”
                                        </p>
                                    </blockquote>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial mx-auto">
                                    <figure class="img-wrap">
                                        <img src="assets/img/testimonials/testimonials-2.jpg" alt="Image" class="img-fluid">
                                    </figure>
                                    <h3 class="name">Lukas Devlin</h3>
                                    <blockquote>
                                        <p>
                                            “There live the blind texts. Separated they live in
                                            Bookmarksgrove right at the coast of the Semantics, a large
                                            language ocean.”
                                        </p>
                                    </blockquote>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="testimonial mx-auto">
                                    <figure class="img-wrap">
                                        <img src="assets/img/testimonials/testimonials-3.jpg" alt="Image" class="img-fluid">
                                    </figure>
                                    <h3 class="name">Kayla Bryant</h3>
                                    <blockquote>
                                        <p>
                                            “There live the blind texts. Separated they live in
                                            Bookmarksgrove right at the coast of the Semantics, a large
                                            language ocean.”
                                        </p>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section><!-- /Testimonials Section -->

</main>











@endsection
