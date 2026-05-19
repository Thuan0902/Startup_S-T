@extends('Admin.layoutAdmin')

@section('Admin_content')
<div class="body-wrapper-inner">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Quản lý dịp</h4>
                        <p class="card-subtitle">Danh sách các dịp bán hàng</p>

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

                        <form method="GET" action="{{ route('occasion') }}" class="row g-2 mt-2 mb-3">
                            <div class="col-md-6">
                                <input type="text" name="q" class="form-control" placeholder="Tìm theo tên hoặc mô tả" value="{{ $search ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-select">
                                    <option value="">Tất cả trạng thái</option>
                                    <option value="1" {{ ($status ?? '') === '1' ? 'selected' : '' }}>Đang hiển thị</option>
                                    <option value="0" {{ ($status ?? '') === '0' ? 'selected' : '' }}>Đang ẩn</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Lọc</button>
                                <a href="{{ route('occasion') }}" class="btn btn-light">Reset</a>
                            </div>
                        </form>

                        <div class="table-responsive mt-4">
                            <table class="table mb-0 align-middle fs-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên dịp</th>
                                        <th>Mô tả</th>
                                        <th>Hiển thị</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($occasions as $occasion)
                                        <tr>
                                            <td>{{ $occasion->id }}</td>
                                            <td>{{ $occasion->name }}</td>
                                            <td>{{ $occasion->description ?: '-' }}</td>
                                            <td>
                                                @if ($occasion->status)
                                                    <span class="badge bg-success">Bật</span>
                                                @else
                                                    <span class="badge bg-secondary">Tắt</span>
                                                @endif
                                            </td>
                                            <td>{{ $occasion->created_at?->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <details>
                                                    <summary class="btn btn-sm btn-outline-primary mb-2">Sửa</summary>
                                                    <form method="POST" action="{{ route('occasion.update', $occasion) }}" class="mb-2">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="text" name="name" class="form-control mb-2" value="{{ $occasion->name }}" maxlength="200" required>
                                                        <textarea name="description" class="form-control mb-2" rows="2" maxlength="200">{{ $occasion->description }}</textarea>
                                                        <div class="form-check mb-2">
                                                            <input type="checkbox" class="form-check-input" id="edit_occasion_status_{{ $occasion->id }}" name="status" value="1" {{ $occasion->status ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="edit_occasion_status_{{ $occasion->id }}">Hiển thị</label>
                                                        </div>
                                                        <button type="submit" class="btn btn-sm btn-primary">Lưu</button>
                                                    </form>
                                                </details>
                                                <form method="POST" action="{{ route('occasion.destroy', $occasion) }}" onsubmit="return confirm('Xóa dịp này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Chưa có dịp nào.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $occasions->links() }}
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold mb-4">Thêm dịp mới</h5>
                        <form method="POST" action="{{ route('occasion.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Tên dịp</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" maxlength="200" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea name="description" id="description" class="form-control" rows="4" maxlength="200">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="status" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">Hiển thị</label>
                            </div>

                            <button type="submit" class="btn btn-primary">Lưu dịp</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
