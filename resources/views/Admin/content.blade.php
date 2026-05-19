@extends('Admin.layoutAdmin')

@section('Admin_content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Quản lý nội dung trang</h4>
                        <p class="text-muted mb-4">Chỉnh sửa văn bản và ảnh cho Trang chủ, Dịp, và nội dung chung (site).</p>

                        @if(session('content_editor_success'))
                        <div class="alert alert-success">{{ session('content_editor_success') }}</div>
                        @endif
                        @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <ul class="nav nav-pills mb-4" role="tablist">
                            @foreach($pages as $key => $page)
                            <li class="nav-item me-2" role="presentation">
                                <button class="nav-link @if($loop->first) active @endif" id="tab-{{ $key }}-tab" data-bs-toggle="pill" data-bs-target="#tab-{{ $key }}" type="button" role="tab" aria-controls="tab-{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $page['label'] }}</button>
                            </li>
                            @endforeach
                        </ul>

                        <div class="tab-content">
                            @foreach($pages as $key => $page)
                            @php $values = $pageContents[$key] ?? []; @endphp
                            <div class="tab-pane fade @if($loop->first) show active @endif" id="tab-{{ $key }}" role="tabpanel" aria-labelledby="tab-{{ $key }}-tab">
                                <form method="POST" action="{{ route('admin.page-content.update') }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="page" value="{{ $key }}">

                                    <div class="row">
                                        @foreach($page['fields'] as $fieldKey => $field)
                                        @php $value = old("content.{$fieldKey}", $values[$fieldKey] ?? ($field['default'] ?? '')); @endphp
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label" for="{{ $key }}-{{ $fieldKey }}">{{ $field['label'] ?? $fieldKey }}</label>
                                            @if(($field['type'] ?? 'text') === 'textarea')
                                            <textarea id="{{ $key }}-{{ $fieldKey }}" name="content[{{ $fieldKey }}]" class="form-control" rows="4">{{ $value }}</textarea>
                                            @elseif(($field['type'] ?? 'text') === 'image')
                                            @if($value)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $value) }}" alt="{{ $field['label'] ?? $fieldKey }}" class="img-fluid rounded" style="max-height: 160px;">
                                            </div>
                                            @endif
                                            <input id="{{ $key }}-{{ $fieldKey }}" type="file" name="content[{{ $fieldKey }}]" class="form-control" accept="image/*">
                                            @else
                                            <input id="{{ $key }}-{{ $fieldKey }}" type="text" name="content[{{ $fieldKey }}]" class="form-control" value="{{ $value }}">
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-3">Lưu nội dung {{ $page['label'] }}</button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
