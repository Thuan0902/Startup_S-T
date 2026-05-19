@extends('Admin.layoutAdmin')

@section('Admin_content')
<div class="body-wrapper-inner">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Quan ly san pham</h4>
                        <p class="card-subtitle">Danh sach san pham</p>

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

                        <form method="GET" action="{{ route('products') }}" class="row g-2 mt-2 mb-3">
                            <div class="col-md-4">
                                <input type="text" name="q" class="form-control" placeholder="Tim theo ten hoac mo ta" value="{{ $search ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <select name="category_id" class="form-select">
                                    <option value="">Tat ca danh muc</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ (string) ($categoryFilter ?? '') === (string) $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="occasion_id" class="form-select">
                                    <option value="">Tat ca dip</option>
                                    @foreach ($occasions as $occasion)
                                        <option value="{{ $occasion->id }}" {{ (string) ($occasionFilter ?? '') === (string) $occasion->id ? 'selected' : '' }}>
                                            {{ $occasion->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">Tat ca trang thai</option>
                                    <option value="1" {{ ($status ?? '') === '1' ? 'selected' : '' }}>Dang hien thi</option>
                                    <option value="0" {{ ($status ?? '') === '0' ? 'selected' : '' }}>Dang an</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Loc</button>
                                <a href="{{ route('products') }}" class="btn btn-light">Reset</a>
                            </div>
                        </form>

                        <div class="table-responsive mt-4">
                            <table class="table mb-0 align-middle fs-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Anh</th>
                                        <th>Ten san pham</th>
                                        <th>Mo ta</th>
                                        <th>Uu diem</th>
                                        <th>Nhuoc diem</th>
                                        <th>Gia</th>
                                        <th>Link lien ket</th>
                                        <th>Danh muc</th>
                                        <th>Dip</th>
                                        <th>Approval</th>
                                        <th>Hien thi</th>
                                        <th>Thao tac</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $product)
                                        <tr>
                                            <td>{{ $product->id }}</td>
                                            <td>
                                                @if ($product->image)
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="56" class="rounded">
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->description ?: '-' }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($product->advantages, 80, '...') ?: '-' }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($product->disadvantages, 80, '...') ?: '-' }}</td>
                                            <td>{{ $product->price !== null ? number_format((float) $product->price, 0, ',', '.') : '-' }}</td>
                                            <td>
                                                @if ($product->affiliate_link)
                                                    <a href="{{ $product->affiliate_link }}" target="_blank" rel="noopener noreferrer">Mo link</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{ $product->category?->name ?: '-' }}</td>
                                            <td>{{ $product->occasion?->name ?: '-' }}</td>
                                            <td>
                                                @if($product->approval_status === 'pending')
                                                    <span class="badge bg-warning">Chờ duyệt</span>
                                                @elseif($product->approval_status === 'approved')
                                                    <span class="badge bg-success">Đã duyệt</span>
                                                @else
                                                    <span class="badge bg-danger">Từ chối</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($product->status)
                                                    <span class="badge bg-success">Bat</span>
                                                @else
                                                    <span class="badge bg-secondary">Tat</span>
                                                @endif
                                            </td>
                                            <td>
                                                <details>
                                                    <summary class="btn btn-sm btn-outline-primary mb-2">Sua</summary>
                                                    <form method="POST" action="{{ route('products.update', $product) }}" class="mb-2" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="text" name="name" class="form-control mb-2" value="{{ $product->name }}" maxlength="200" required>
                                                        <textarea name="description" class="form-control mb-2" rows="2">{{ $product->description }}</textarea>
                                                        <textarea name="advantages" class="form-control mb-2" rows="2" placeholder="Uu diem">{{ $product->advantages }}</textarea>
                                                        <textarea name="disadvantages" class="form-control mb-2" rows="2" placeholder="Nhuoc diem">{{ $product->disadvantages }}</textarea>
                                                        <input type="file" name="image" class="form-control mb-2" accept="image/*">
                                                        <input type="url" name="affiliate_link" class="form-control mb-2" value="{{ $product->affiliate_link }}" placeholder="Link tiep thi lien ket">
                                                        <input type="number" step="0.01" min="0" name="price" class="form-control mb-2" value="{{ $product->price }}">
                                                        <select name="category_id" class="form-select mb-2" required>
                                                            @foreach ($categories as $category)
                                                                <option value="{{ $category->id }}" {{ (int) $product->category_id === (int) $category->id ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <select name="occasion_id" class="form-select mb-2">
                                                            <option value="">Chon dip</option>
                                                            @foreach ($occasions as $occasion)
                                                                <option value="{{ $occasion->id }}" {{ (int) $product->occasion_id === (int) $occasion->id ? 'selected' : '' }}>
                                                                    {{ $occasion->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" class="form-check-input" id="edit_product_status_{{ $product->id }}" name="status" value="1" {{ $product->status ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="edit_product_status_{{ $product->id }}">Hien thi</label>
                                                        </div>
                                                        <button type="submit" class="btn btn-sm btn-primary">Luu</button>
                                                    </form>
                                                </details>
                                                @if($product->approval_status === 'pending')
                                                    <form method="POST" action="{{ route('products.approve', $product) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success">Duyệt</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('products.reject', $product) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Từ chối sản phẩm này?')">Từ chối</button>
                                                    </form>
                                                @endif
                                                <form method="POST" action="{{ route('products.destroy', $product) }}" onsubmit="return confirm('Xoa san pham nay?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Xoa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="12" class="text-center">Chua co du lieu.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Them san pham moi</h5>
                        <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Ten san pham</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" maxlength="200" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mo ta</label>
                                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="advantages" class="form-label">Uu diem</label>
                                <textarea name="advantages" id="advantages" class="form-control" rows="4">{{ old('advantages') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="disadvantages" class="form-label">Nhuoc diem</label>
                                <textarea name="disadvantages" id="disadvantages" class="form-control" rows="4">{{ old('disadvantages') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="image" class="form-label">Anh san pham</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>

                            <div class="mb-3">
                                <label for="affiliate_link" class="form-label">Link lien ket san pham</label>
                                <input type="url" class="form-control" id="affiliate_link" name="affiliate_link" value="{{ old('affiliate_link') }}" placeholder="https://...">
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Gia ban</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="price" name="price" value="{{ old('price') }}">
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Danh muc</label>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    <option value="">Chon danh muc</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ (string) old('category_id') === (string) $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="occasion_id" class="form-label">Dip</label>
                                <select name="occasion_id" id="occasion_id" class="form-select">
                                    <option value="">Chon dip</option>
                                    @foreach ($occasions as $occasion)
                                        <option value="{{ $occasion->id }}" {{ (string) old('occasion_id') === (string) $occasion->id ? 'selected' : '' }}>
                                            {{ $occasion->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Hien thi</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Luu san pham</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
