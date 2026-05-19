@extends('Admin.layoutAdmin')

@section('Admin_content')
<div class="body-wrapper-inner">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Quan ly bai viet</h4>
                        <p class="card-subtitle">Danh sach bai viet</p>

                        @if (session('success'))
                            <div class="alert alert-success mt-3">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="GET" action="{{ route('article') }}" class="row g-2 mt-2 mb-3">
                            <div class="col-md-3">
                                <input type="text" name="q" class="form-control" placeholder="Tim theo tieu de hoac mo ta" value="{{ $search ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <select name="category_id" class="form-select">
                                    <option value="">Tat ca danh muc</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ (string) ($categoryFilter ?? '') === (string) $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="product_id" class="form-select">
                                    <option value="">Tat ca san pham</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ (string) ($productFilter ?? '') === (string) $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select name="occasion_id" class="form-select">
                                    <option value="">Tat ca dip</option>
                                    @foreach ($occasions as $occasion)
                                        <option value="{{ $occasion->id }}" {{ (string) ($occasionFilter ?? '') === (string) $occasion->id ? 'selected' : '' }}>
                                            {{ $occasion->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1">
                                <select name="status" class="form-select">
                                    <option value="">TT</option>
                                    <option value="1" {{ ($status ?? '') === '1' ? 'selected' : '' }}>On</option>
                                    <option value="0" {{ ($status ?? '') === '0' ? 'selected' : '' }}>Off</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Loc</button>
                                <a href="{{ route('article') }}" class="btn btn-light">Reset</a>
                            </div>
                        </form>

                        <div class="table-responsive mt-4">
                            <table class="table mb-0 align-middle fs-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tieu de</th>
                                        <th>Mo ta</th>
                                        <th>Danh muc</th>
                                        <th>San pham</th>
                                        <th>Dip</th>
                                        <th>Approval</th>
                                        <th>Anh bai viet</th>
                                        <th>Hien thi</th>
                                        <th>Thao tac</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($articles as $article)
                                        <tr>
                                            <td>{{ $article->id }}</td>
                                            <td>{{ $article->title }}</td>
                                            <td>{{ $article->description ?: '-' }}</td>
                                            <td>
                                                @php
                                                    $categoryNames = $article->categories->pluck('name')->implode(', ');
                                                @endphp
                                                {{ $categoryNames !== '' ? $categoryNames : '-' }}
                                            </td>
                                            <td>
                                                @php
                                                    $productNames = $article->products->pluck('name')->implode(', ');
                                                @endphp
                                                {{ $productNames !== '' ? $productNames : '-' }}
                                            </td>
                                            <td>{{ $article->occasion ? $article->occasion->name : '-' }}</td>
                                            <td>
                                                @if($article->approval_status === 'pending')
                                                    <span class="badge bg-warning">Chờ duyệt</span>
                                                @elseif($article->approval_status === 'approved')
                                                    <span class="badge bg-success">Đã duyệt</span>
                                                @else
                                                    <span class="badge bg-danger">Từ chối</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($article->images->count() > 0)
                                                    <div class="d-flex gap-1 flex-wrap">
                                                        @foreach ($article->images->take(3) as $image)
                                                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="article image" width="50" class="rounded">
                                                        @endforeach
                                                    </div>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($article->status)
                                                    <span class="badge bg-success">Bat</span>
                                                @else
                                                    <span class="badge bg-secondary">Tat</span>
                                                @endif
                                            </td>
                                            <td>
                                                <details>
                                                    <summary class="btn btn-sm btn-outline-primary mb-2">Sua</summary>
                                                    <form method="POST" action="{{ route('article.update', $article) }}" class="mb-2" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        @php
                                                            $selectedCategoryIds = $article->categories->pluck('id')->all();
                                                            $selectedProductIds = $article->products->pluck('id')->all();
                                                        @endphp
                                                        <input type="text" name="title" class="form-control mb-2" value="{{ $article->title }}" maxlength="200" required>
                                                        <textarea name="description" class="form-control mb-2" rows="2">{{ $article->description }}</textarea>
                                                        <label class="form-label mb-1">Danh muc lien quan (co the chon nhieu)</label>
                                                        <select name="category_ids[]" class="form-select mb-2" multiple size="4">
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}" {{ in_array((int) $category->id, $selectedCategoryIds, true) ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label class="form-label mb-1">San pham lien quan (co the chon nhieu)</label>
                                                        <select name="product_ids[]" class="form-select mb-2" multiple size="4">
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}" {{ in_array((int) $product->id, $selectedProductIds, true) ? 'selected' : '' }}>
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label class="form-label mb-1">Dip</label>
                                                        <select name="occasion_id" class="form-select mb-2">
                                                            <option value="">Chon dip</option>
                                                            @foreach ($occasions as $occasion)
                                                                <option value="{{ $occasion->id }}" {{ $article->occasion_id == $occasion->id ? 'selected' : '' }}>
                                                                    {{ $occasion->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @if ($article->images->count() > 0)
                                                            <div class="mb-2 d-flex gap-1 flex-wrap">
                                                                @foreach ($article->images->take(3) as $image)
                                                                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="current article image" width="60" class="rounded">
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                        <label class="form-label mb-1">Anh bai viet (toi da 3 anh, upload moi se thay anh cu)</label>
                                                        <input type="file" name="images[]" class="form-control mb-2" multiple accept="image/*">
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" class="form-check-input" id="edit_article_status_{{ $article->id }}" name="status" value="1" {{ $article->status ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="edit_article_status_{{ $article->id }}">Hien thi</label>
                                                        </div>
                                                        <button type="submit" class="btn btn-sm btn-primary">Luu</button>
                                                    </form>
                                                </details>
                                                @if($article->approval_status === 'pending')
                                                    <form method="POST" action="{{ route('article.approve', $article) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">Duyệt</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('article.reject', $article) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Từ chối bài viết này?')">Từ chối</button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('article.destroy', $article) }}" onsubmit="return confirm('Xoa bai viet nay?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Xoa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">Chua co du lieu.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $articles->links() }}
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Them bai viet moi</h5>
                        <form method="POST" action="{{ route('article.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="title" class="form-label">Tieu de</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" maxlength="200" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mo ta</label>
                                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="category_ids" class="form-label">Danh muc lien quan (co the chon nhieu)</label>
                                <select name="category_ids[]" id="category_ids" class="form-select" multiple size="5">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ in_array((string) $category->id, old('category_ids', []), true) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="product_ids" class="form-label">San pham lien quan (co the chon nhieu)</label>
                                <select name="product_ids[]" id="product_ids" class="form-select" multiple size="5">
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" {{ in_array((string) $product->id, old('product_ids', []), true) ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="occasion_id" class="form-label">Dip</label>
                                <select name="occasion_id" id="occasion_id" class="form-select">
                                    <option value="">Chon dip</option>
                                    @foreach ($occasions as $occasion)
                                        <option value="{{ $occasion->id }}" {{ old('occasion_id') == $occasion->id ? 'selected' : '' }}>
                                            {{ $occasion->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="images" class="form-label">Anh bai viet (toi da 3 anh)</label>
                                <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Hien thi</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Luu bai viet</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
