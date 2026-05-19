@extends('welcome')

@section('content')
<h1>{{ $pageContent['title'] ?? 'Trang Portfolio' }}</h1>
<p>{{ $pageContent['description'] ?? 'Đây là trang Portfolio. Nội dung sẽ được cập nhật sau.' }}</p>
@endsection
