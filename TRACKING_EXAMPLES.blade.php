<!-- 
    VÍ DỤ: Cách Thêm Tracking Vào Template
    Sao chép các ví dụ này vào file templates của bạn
-->

<!-- EXAMPLE 1: Bài viết trong Blog -->
@foreach($articles as $article)
    <div class="article-card">
        <h3>
            <a href="{{ route('article.show', $article->id) }}" 
               data-article-id="{{ $article->id }}">
                {{ $article->title }}
            </a>
        </h3>
        <p>{{ $article->description }}</p>
    </div>
@endforeach

<!-- EXAMPLE 2: Sản phẩm trong danh sách -->
@foreach($products as $product)
    <div class="product-card">
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
        <h4>
            <a href="{{ route('product.show', $product->id) }}" 
               data-product-id="{{ $product->id }}">
                {{ $product->name }}
            </a>
        </h4>
        <p class="price">${{ $product->price }}</p>
    </div>
@endforeach

<!-- EXAMPLE 3: Buttons với tracking -->
<a href="{{ route('article.show', $article->id) }}" 
   class="btn btn-primary" 
   data-article-id="{{ $article->id }}">
    Xem Chi Tiết
</a>

<!-- EXAMPLE 4: Tracking cho các action khác -->
<!-- Nút Subscribe -->
<button class="track-click" 
        data-track-type="subscription" 
        data-track-id="{{ $product->id }}">
    Subscribe
</button>

<!-- Nút Download -->
<a href="#" 
   class="track-click" 
   data-track-type="download" 
   data-track-id="{{ $article->id }}">
    Tải Xuống
</a>

<!-- Nút Share -->
<button class="track-click" 
        data-track-type="share" 
        data-track-id="{{ $article->id }}">
    Chia Sẻ
</button>

<!-- EXAMPLE 5: Thêm tracking vào nhiều loại element -->
<!-- Links -->
<a href="/article/123" data-article-id="123">Article Title</a>

<!-- Buttons -->
<button data-product-id="456" onclick="window.location.href='/product/456'">
    View Product
</button>

<!-- Images (click vào ảnh) -->
<img src="image.jpg" data-product-id="789" 
     onclick="window.location.href='/product/789'" 
     style="cursor: pointer;">

<!-- EXAMPLE 6: Nếu bạn có custom link không phải article/product -->
<a href="#" class="track-click" 
   data-track-type="custom_type" 
   data-track-id="custom_value">
    Custom Link
</a>

<!-- EXAMPLE 7: Tracking khi click vào liên kết liên quan -->
<div class="related-articles">
    @foreach($relatedArticles as $related)
        <a href="{{ route('article.show', $related->id) }}" 
           data-article-id="{{ $related->id }}">
            {{ $related->title }}
        </a>
    @endforeach
</div>

<!-- EXAMPLE 8: Menu navigation với tracking -->
<nav class="navbar">
    <ul>
        <li>
            <a href="{{ route('home') }}">Trang Chủ</a>
        </li>
        <li>
            <a href="{{ route('blog') }}" data-article-id="blog-home">
                Blog
            </a>
        </li>
        @foreach($categories as $cat)
            <li>
                <a href="{{ route('category.show', $cat->id) }}" 
                   data-article-id="{{ $cat->id }}">
                    {{ $cat->name }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>

<!-- NOTES:
     1. Hãy sử dụng data-article-id cho các link bài viết
     2. Hãy sử dụng data-product-id cho các link sản phẩm
     3. Hãy sử dụng class="track-click" cùng data-track-type cho các loại khác
     
     4. Tracking sẽ tự động ghi lại:
        - Khi nào user click (clicked_at)
        - IP address của user
        - User agent (trình duyệt)
        - URL của trang hiện tại
        
     5. Bạn không cần làm gì đặc biệt, chỉ cần thêm attributes
        Hệ thống sẽ tự động tracking khi có click
-->
