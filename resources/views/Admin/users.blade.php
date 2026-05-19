@extends('Admin.layoutAdmin')

@section('Admin_content')
<div class="body-wrapper-inner">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Quan ly nguoi dung</h4>
                        <p class="card-subtitle">Danh sach tai khoan va phan quyen</p>

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

                        <form method="GET" action="{{ route('users') }}" class="row g-2 mt-2 mb-3">
                            <div class="col-md-5">
                                <input type="text" name="q" class="form-control" placeholder="Tim theo ten hoac email" value="{{ $search }}">
                            </div>
                            <div class="col-md-3">
                                <select name="role" class="form-select">
                                    <option value="">Tat ca role</option>
                                    <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ $role === 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Loc</button>
                                <a href="{{ route('users') }}" class="btn btn-light">Reset</a>
                            </div>
                        </form>

                        <div class="table-responsive mt-4">
                            <table class="table mb-0 align-middle fs-3">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Ten</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>VIP</th>
                                        <th>Tao luc</th>
                                        <th>Thao tac</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->name }}{{ $user->id === auth()->id() ? ' (Ban)' : '' }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if ($user->role === 'admin')
                                                    <span class="badge bg-primary">Admin</span>
                                                @else
                                                    <span class="badge bg-secondary">User</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->isVip())
                                                    <span class="badge bg-warning text-dark">VIP đến {{ $user->vip_expires_at->format('d/m/Y') }}</span>
                                                @else
                                                    <span class="badge bg-secondary">Thường</span>
                                                @endif
                                            </td>
                                            <td>{{ $user->created_at?->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <details>
                                                    <summary class="btn btn-sm btn-outline-primary mb-2">Sua</summary>
                                                    <form method="POST" action="{{ route('users.update', $user) }}" class="mb-2">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="text" name="name" class="form-control mb-2" value="{{ $user->name }}" required>
                                                        <input type="email" name="email" class="form-control mb-2" value="{{ $user->email }}" required>
                                                        <select name="role" class="form-select mb-2" required>
                                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                        </select>
                                                        <input type="password" name="password" class="form-control mb-2" placeholder="Mat khau moi (bo trong neu khong doi)">
                                                        <input type="password" name="password_confirmation" class="form-control mb-2" placeholder="Xac nhan mat khau moi">
                                                        <button type="submit" class="btn btn-sm btn-primary">Luu</button>
                                                    </form>
                                                </details>

                                                @if($user->role !== 'admin')
                                                    <form method="POST" action="{{ route('users.toggle-vip', $user) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm {{ $user->isVip() ? 'btn-warning' : 'btn-success' }}">
                                                            {{ $user->isVip() ? 'Hủy VIP' : 'Cấp VIP' }}
                                                        </button>
                                                    </form>
                                                @endif

                                                <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Xoa tai khoan nay?')">
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
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Lich su phan quyen gan day</h5>
                        <div class="table-responsive mt-3">
                            <table class="table mb-0 align-middle fs-3">
                                <thead>
                                    <tr>
                                        <th>Thoi gian</th>
                                        <th>Nguoi thao tac</th>
                                        <th>Hanh dong</th>
                                        <th>Doi tuong</th>
                                        <th>Tu role</th>
                                        <th>Sang role</th>
                                        <th>Ghi chu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($logs as $log)
                                        <tr>
                                            <td>{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                                            <td>{{ $log->actor?->email ?: '-' }}</td>
                                            <td>{{ $log->action }}</td>
                                            <td>{{ $log->target?->email ?: '-' }}</td>
                                            <td>{{ $log->old_role ?: '-' }}</td>
                                            <td>{{ $log->new_role ?: '-' }}</td>
                                            <td>{{ $log->note ?: '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Chua co log.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
