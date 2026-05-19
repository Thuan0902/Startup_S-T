@extends('Admin.layoutAdmin')

@section('Admin_content')
<div class="body-wrapper-inner">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Quan ly danh muc</h4>
                        <p class="card-subtitle">Danh sach danh muc da tao</p>

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

                        <form method="GET" action="{{ route('category') }}" class="row g-2 mt-2 mb-3">
                            <div class="col-md-6">
                                <input type="text" name="q" class="form-control" placeholder="Tim theo ten hoac mo ta" value="{{ $search ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">Tat ca trang thai</option>
                                    <option value="1" {{ ($status ?? '') === '1' ? 'selected' : '' }}>Dang hien thi</option>
                                    <option value="0" {{ ($status ?? '') === '0' ? 'selected' : '' }}>Dang an</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Loc</button>
                                <a href="{{ route('category') }}" class="btn btn-light">Reset</a>
                            </div>
                        </form>

                        <div class="table-responsive mt-4">
                            <table class="table mb-0 align-middle fs-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ten danh muc</th>
                                        <th>Mo ta</th>
                                        <th>Hien thi</th>
                                        <th>Ngay tao</th>
                                        <th>Thao tac</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->description ?: '-' }}</td>
                                            <td>
                                                @if ($category->status)
                                                    <span class="badge bg-success">Bat</span>
                                                @else
                                                    <span class="badge bg-secondary">Tat</span>
                                                @endif
                                            </td>
                                            <td>{{ $category->created_at?->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <details>
                                                    <summary class="btn btn-sm btn-outline-primary mb-2">Sua</summary>
                                                    <form method="POST" action="{{ route('category.update', $category) }}" class="mb-2">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="text" name="name" class="form-control mb-2" value="{{ $category->name }}" maxlength="200" required>
                                                        <textarea name="description" class="form-control mb-2" rows="2" maxlength="200">{{ $category->description }}</textarea>
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" class="form-check-input" id="edit_status_{{ $category->id }}" name="status" value="1" {{ $category->status ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="edit_status_{{ $category->id }}">Hien thi</label>
                                                        </div>
                                                        <button type="submit" class="btn btn-sm btn-primary">Luu</button>
                                                    </form>
                                                </details>
                                                <form method="POST" action="{{ route('category.destroy', $category) }}" onsubmit="return confirm('Xoa danh muc nay?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Xoa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Chua co du lieu.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Them danh muc moi</h5>
                        <form method="POST" action="{{ route('category.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Ten danh muc</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" maxlength="200" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mo ta</label>
                                <textarea name="description" id="description" class="form-control" rows="4" maxlength="200">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Hien thi</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Luu danh muc</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
