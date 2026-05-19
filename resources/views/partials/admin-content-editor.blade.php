@auth
@if(auth()->user()->isAdmin() && ((!empty($pageEditorKey) && !empty($pageEditorFields)) || !empty($siteEditorFields)))
<style>
    .admin-content-editor {
        position: fixed;
        right: 18px;
        bottom: 18px;
        z-index: 1080;
        font-family: 'Inter', sans-serif;
    }

    .admin-content-editor .dropdown-menu {
        width: min(420px, calc(100vw - 28px));
        max-height: min(74vh, 720px);
        overflow-y: auto;
        border: 0;
        border-radius: 8px;
        box-shadow: 0 18px 48px rgba(33, 37, 41, 0.18);
    }

    .admin-content-editor label {
        font-size: 12px;
        font-weight: 700;
        color: #343a40;
    }

    .admin-content-editor textarea {
        min-height: 92px;
        resize: vertical;
    }
</style>

<div class="admin-content-editor dropdown">
    <button class="btn btn-danger dropdown-toggle shadow" type="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
        <i class="bi bi-pencil-square me-1"></i> Sửa nội dung trang
    </button>

    <div class="dropdown-menu dropdown-menu-end p-3">
        <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
            <div>
                <strong>{{ $pageEditorConfig['label'] ?? 'Trang hiện tại' }}</strong>
                <div class="small text-muted">Chỉ admin nhìn thấy bảng sửa này.</div>
            </div>
        </div>

        @if(session('content_editor_success'))
        <div class="alert alert-success py-2 mb-3">{{ session('content_editor_success') }}</div>
        @endif

        @if($errors->has('page'))
        <div class="alert alert-danger py-2 mb-3">{{ $errors->first('page') }}</div>
        @endif

        @if(!empty($pageEditorKey) && !empty($pageEditorFields))
        <form method="POST" action="{{ route('admin.page-content.update') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="page" value="{{ $pageEditorKey }}">

            @foreach($pageEditorFields as $fieldKey => $field)
            @php
            $value = old("content.{$fieldKey}", $pageContent[$fieldKey] ?? ($field['default'] ?? ''));
            @endphp
            <div class="mb-3">
                <label class="form-label" for="page-content-{{ $fieldKey }}">{{ $field['label'] ?? $fieldKey }}</label>
                @if(($field['type'] ?? 'text') === 'textarea')
                <textarea id="page-content-{{ $fieldKey }}" name="content[{{ $fieldKey }}]" class="form-control" rows="4">{{ $value }}</textarea>
                @elseif(($field['type'] ?? 'text') === 'image')
                @if($value)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $value) }}" alt="{{ $field['label'] ?? $fieldKey }}" class="img-fluid rounded" style="max-height:120px;">
                </div>
                @endif
                <input id="page-content-{{ $fieldKey }}" type="file" name="content[{{ $fieldKey }}]" class="form-control" accept="image/*">
                @else
                <input id="page-content-{{ $fieldKey }}" type="text" name="content[{{ $fieldKey }}]" class="form-control" value="{{ $value }}">
                @endif
            </div>
            @endforeach

            <button type="submit" class="btn btn-danger w-100 mb-4">
                <i class="bi bi-save me-1"></i> Lưu nội dung trang hiện tại
            </button>
        </form>
        @endif

        @if(!empty($siteEditorFields))
        <hr>
        <div class="mb-3">
            <strong>Nội dung chung (nav / footer)</strong>
        </div>
        <form method="POST" action="{{ route('admin.page-content.update') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="page" value="site">

            @foreach($siteEditorFields as $fieldKey => $field)
            @php
            $value = old("content.{$fieldKey}", $sitePageContent[$fieldKey] ?? ($field['default'] ?? ''));
            @endphp
            <div class="mb-3">
                <label class="form-label" for="site-content-{{ $fieldKey }}">{{ $field['label'] ?? $fieldKey }}</label>
                @if(($field['type'] ?? 'text') === 'textarea')
                <textarea id="site-content-{{ $fieldKey }}" name="content[{{ $fieldKey }}]" class="form-control" rows="4">{{ $value }}</textarea>
                @elseif(($field['type'] ?? 'text') === 'image')
                @if($value)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $value) }}" alt="{{ $field['label'] ?? $fieldKey }}" class="img-fluid rounded" style="max-height:120px;">
                </div>
                @endif
                <input id="site-content-{{ $fieldKey }}" type="file" name="content[{{ $fieldKey }}]" class="form-control" accept="image/*">
                @else
                <input id="site-content-{{ $fieldKey }}" type="text" name="content[{{ $fieldKey }}]" class="form-control" value="{{ $value }}">
                @endif
            </div>
            @endforeach

            <button type="submit" class="btn btn-danger w-100">
                <i class="bi bi-save me-1"></i> Lưu nội dung chung
            </button>
        </form>
        @endif
    </div>
</div>
@endif
@endauth
