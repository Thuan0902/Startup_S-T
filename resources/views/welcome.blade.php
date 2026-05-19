<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tiện ích</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
    <!--Font google -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sekuya&display=swap" rel="stylesheet">
    <style>
        .header .header-search {
            flex: 1 1 360px;
            max-width: 430px;
            margin: 0 28px;
        }

        .header .header-search-form {
            position: relative;
            width: 100%;
        }

        .header .header-search-form input {
            width: 100%;
            height: 42px;
            border: 1px solid rgba(33, 37, 41, 0.12);
            border-radius: 999px;
            padding: 0 48px 0 18px;
            color: #212529;
            background: #fff;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .header .header-search-form input:focus {
            border-color: rgba(220, 53, 69, 0.45);
            box-shadow: 0 8px 22px rgba(220, 53, 69, 0.1);
        }

        .header .header-search-form button {
            position: absolute;
            top: 50%;
            right: 6px;
            width: 32px;
            height: 32px;
            border: 0;
            border-radius: 50%;
            transform: translateY(-50%);
            background: #dc3545;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .logout-icon-button {
            border: 0;
            background: none;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        .logout-icon-button img {
            width: 22px;
            height: 22px;
            object-fit: contain;
            display: block;
        }

        .footer {
            padding: 24px 0 16px;
            background: #0f172a;
            color: #ffffff !important;
        }

        .footer * {
            color: #ffffff !important;
        }

        .footer .widget {
            margin-bottom: 14px;
            padding: 14px 16px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            box-shadow: 0 14px 24px rgba(0, 0, 0, 0.10);
        }

        .footer .widget-heading {
            font-size: 18px;
            margin-bottom: 12px;
            color: #dc3545 !important;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .footer .list-unstyled > li {
            margin-bottom: 8px;
        }

        .footer a {
            color: #ffffff !important;
            transition: color 0.2s ease, transform 0.2s ease;
            text-decoration: none;
        }

        .footer a:hover,
        .footer a:focus {
            color: #dc3545 !important;
            transform: translateX(2px);
        }

        .footer .social-icons a,
        .footer .social-icons a span {
            color: #dc3545 !important;
        }

        .footer .contact-widget .contact-details,
        .footer .support-widget .support-links {
            padding: 0;
            margin: 0;
            list-style: none;
        }

        .footer .contact-item,
        .footer .support-links li {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 10px;
            font-size: 14px;
            line-height: 1.7;
            color: #ffffff !important;
        }

        .footer .contact-item::before,
        .footer .support-links li::before {
            font-family: 'bootstrap-icons';
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
            color: #dc3545;
            font-size: 14px;
            flex-shrink: 0;
        }

        .footer .phone-item::before { content: '\f7a1'; }
        .footer .email-item::before { content: '\f0e0'; }
        .footer .address-item::before { content: '\f3a2'; }
        .footer .support-links li::before { content: '\f142'; }

        .footer .contact-label {
            color: #ffffff !important;
            display: block;
        }

        .footer .widget .btn-learn-more {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #dc3545 !important;
            border: 1px solid rgba(220, 53, 69, 0.22);
            padding: 8px 16px;
            border-radius: 999px;
            background: rgba(220, 53, 69, 0.08);
            transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
        }

        .footer .widget .btn-learn-more:hover {
            background: rgba(220, 53, 69, 0.18);
            border-color: #dc3545;
            color: #dc3545 !important;
            transform: translateY(-1px);
        }

        @media (max-width: 991px) {
            .footer {
                padding: 40px 0 30px;
            }

            .footer .contact-item,
            .footer .support-links li {
                align-items: flex-start;
            }
        }

        @media (max-width: 1199px) {
            .header .header-search {
                order: 3;
                flex-basis: 100%;
                max-width: none;
                margin: 12px 0 0;
            }

            .header .container-fluid {
                flex-wrap: wrap;
            }
        }
    </style>
    <!-- =======================================================
  * Template Name: Active
  * Template URL: https://bootstrapmade.com/active-bootstrap-website-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

            <a href="/" class="logo d-flex align-items-center">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="{{ !empty($sitePageContent['site_logo']) ? asset('storage/' . $sitePageContent['site_logo']) : asset('assets/img/logo.png') }}" alt="Logo">
            </a>

            <div class="header-search">
                <form action="{{ route('search') }}" method="GET" class="header-search-form">
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Tìm sản phẩm, bài viết, dịp..." aria-label="Tìm kiếm">
                    <button type="submit" aria-label="Tìm kiếm">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ $sitePageContent['nav_home'] ?? 'Trang Chủ' }}</a></li>
                    <li><a href="{{ url('services') }}" class="{{ request()->routeIs('services') ? 'active' : '' }}">{{ $sitePageContent['nav_services'] ?? 'Danh Sách Mong Muốn' }}</a></li>
                    <li><a href="{{ url('team') }}" class="{{ request()->routeIs('team') ? 'active' : '' }}">{{ $sitePageContent['nav_team'] ?? 'Cảm Hứng' }}</a></li>
                    <li><a href="{{ url('blog') }}" class="{{ request()->routeIs('blog') ? 'active' : '' }}">{{ $sitePageContent['nav_blog'] ?? 'Bài Viết' }}</a></li>
                    <li><a href="{{ route('occasions') }}" class="{{ request()->routeIs('occasions') ? 'active' : '' }}">{{ $sitePageContent['nav_occasions'] ?? 'Dịp Trong Năm' }}</a></li>
                    <li><a href="{{ url('portfolio') }}" class="{{ request()->routeIs('portfolio') ? 'active' : '' }}">{{ $sitePageContent['nav_portfolio'] ?? 'Hoạt Động' }}</a></li>

                    @auth
                    @if (auth()->user()->isAdmin())
                    <li><a href="{{ route('admin') }}">Admin</a></li>
                    @endif
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-icon-button" title="Đăng xuất" aria-label="Đăng xuất">
                                <img src="{{ asset('icon/session_exit_sign_out_log_logout_ecommerce_icon_224958.png') }}" alt="Đăng xuất">
                            </button>
                        </form>
                    </li>
                    @else
                    <li><a href="{{ route('login') }}">Đăng Nhập</a></li>
                    <li><a href="{{ route('register') }}">Đăng Ký</a></li>
                    @endauth
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

        </div>
    </header>

    @include('partials.admin-content-editor')

    @yield('content')

    <footer id="footer" class="footer light-background">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3 mb-3 mb-md-0">
                    <div class="widget">
                        <h3 class="widget-heading">{{ $sitePageContent['footer_about_title'] ?? 'AGIFT' }}</h3>
                        <p class="mb-4">
                            {{ $sitePageContent['footer_about_description'] ?? 'Khám phá những sản phẩm yêu thích, tạo danh sách cho cuộc sống của riêng bạn và chia sẻ với mọi người. Công cụ hoàn toàn miễn phí cho mọi dịp.' }}
                        </p>
                        <p class="mb-4">
                            <a href="{{ route('home') }}" class="btn-learn-more">{{ $sitePageContent['footer_cta_text'] ?? 'Khám phá ngay >' }}</a>
                        </p>
                    </div>
                    <div class="widget">
                        <h3 class="widget-heading">Kết nối</h3>
                        <ul class="list-unstyled social-icons light mb-3">
                            <li><a href="#"><span class="bi bi-facebook"></span></a></li>
                            <li><a href="#"><span class="bi bi-twitter-x"></span></a></li>
                            <li><a href="#"><span class="bi bi-linkedin"></span></a></li>
                            <li><a href="#"><span class="bi bi-google"></span></a></li>
                            <li><a href="#"><span class="bi bi-google-play"></span></a></li>
                        </ul>
                    </div>
                </div>




                <div class="col-md-6 col-lg-3 pl-lg-5">

                    <div class="widget">
                        <h3 class="widget-heading">{{ $sitePageContent['footer_links_title'] ?? 'Tổng quan' }}</h3>
                        <ul class="list-unstyled">
                            <li><a href="{{ $sitePageContent['footer_link_1_url'] ?? url('blog') }}">{{ $sitePageContent['footer_link_1_label'] ?? 'Blog' }}</a></li>
                            <li><a href="{{ $sitePageContent['footer_link_2_url'] ?? '#' }}">{{ $sitePageContent['footer_link_2_label'] ?? 'Cách thức hoạt động' }}</a></li>
                            <li><a href="{{ $sitePageContent['footer_link_3_url'] ?? '#' }}">{{ $sitePageContent['footer_link_3_label'] ?? 'Chính sách bảo mật' }}</a></li>
                            <li><a href="{{ $sitePageContent['footer_link_4_url'] ?? '#' }}">{{ $sitePageContent['footer_link_4_label'] ?? 'Điều khoản dịch vụ' }}</a></li>
                            <li><a href="{{ $sitePageContent['footer_link_5_url'] ?? '#' }}">{{ $sitePageContent['footer_link_5_label'] ?? 'Trung tâm trợ giúp' }}</a></li>
                            <li><a href="#">....</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3">
                    <div class="widget contact-widget">
                        <h3 class="widget-heading">{{ $sitePageContent['footer_contact_title'] ?? 'Liên hệ' }}</h3>
                        <ul class="list-unstyled contact-details">
                            <li class="contact-item phone-item">
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $sitePageContent['footer_contact_phone'] ?? '0900000000') }}">{{ $sitePageContent['footer_contact_phone'] ?? '0900 000 000' }}</a>
                            </li>
                            <li class="contact-item email-item">
                                <a href="mailto:{{ $sitePageContent['footer_contact_email'] ?? 'hello@agift.com' }}">{{ $sitePageContent['footer_contact_email'] ?? 'hello@agift.com' }}</a>
                            </li>
                            <li class="contact-item address-item">
                                <span class="contact-label">{{ $sitePageContent['footer_contact_address'] ?? '123 Đường ABC, Quận XYZ, TP.HCM' }}</span>
                            </li>
                        </ul>
                    </div>

                </div>
                <div class="col-md-6 col-lg-3 pl-lg-5">

                    <div class="widget support-widget">
                        <h3 class="widget-heading">{{ $sitePageContent['footer_support_title'] ?? 'Hỗ trợ khách hàng' }}</h3>
                        <ul class="list-unstyled support-links">
                            <li><a href="{{ $sitePageContent['footer_support_link_1_url'] ?? '#' }}">{{ $sitePageContent['footer_support_link_1_label'] ?? 'Trung tâm trợ giúp' }}</a></li>
                            <li><a href="{{ $sitePageContent['footer_support_link_2_url'] ?? '#' }}">{{ $sitePageContent['footer_support_link_2_label'] ?? 'Câu hỏi thường gặp' }}</a></li>
                            <li><a href="{{ $sitePageContent['footer_support_link_3_url'] ?? '#' }}">{{ $sitePageContent['footer_support_link_3_label'] ?? 'Chính sách đổi trả' }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>


        </div>
    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Analytics Tracking -->
    <script src="{{ asset('js/analytics-tracking.js') }}"></script>

</body>

</html>