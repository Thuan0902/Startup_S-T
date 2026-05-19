@extends('welcome')

@section('content')
<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="mb-0">Tạo bài viết mới</h4>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('user.store-article') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" maxlength="200" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="category_ids" class="form-label">Danh mục liên quan (có thể chọn nhiều)</label>
                            <select name="category_ids[]" id="category_ids" class="form-select" multiple size="4">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ in_array((string) $category->id, old('category_ids', []), true) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="product_ids" class="form-label">Sản phẩm liên quan (chỉ sản phẩm của bạn)</label>
                            <select name="product_ids[]" id="product_ids" class="form-select" multiple size="4">
                                @foreach ($userProducts as $product)
                                    <option value="{{ $product->id }}" {{ in_array((string) $product->id, old('product_ids', []), true) ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Chỉ hiển thị sản phẩm đã được duyệt của bạn.</div>
                        </div>

                        <div class="mb-3">
                            <label for="occasion_id" class="form-label">Dịp</label>
                            <select name="occasion_id" id="occasion_id" class="form-select">
                                <option value="">Chọn dịp</option>
                                @foreach ($occasions as $occasion)
                                    <option value="{{ $occasion->id }}" {{ old('occasion_id') == $occasion->id ? 'selected' : '' }}>
                                        {{ $occasion->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="images" class="form-label">Ảnh bài viết (tối đa 3 ảnh)</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                            <div class="form-text">Chọn tối đa 3 ảnh, mỗi ảnh không quá 2MB.</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Gửi để duyệt</button>
                            <a href="{{ route('profile') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection