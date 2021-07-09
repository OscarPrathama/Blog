<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title')</title>

<!-- Bootstrap CSS -->
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/admin-style.css') }}" rel="stylesheet">

</head>
<body class="post-{{ isset($title) ? Str::lower($title) : 'default' }}-template">

{{-- navbar --}}
@include('admin.layouts.admin-navbar')

{{-- content --}}
@yield('content')

{{-- footer --}}
@include('admin.layouts.admin-footer')

{{-- alert --}}
@include('admin.layouts.admin-alert')

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('vendor/tinymce/jquery.tinymce.min.js') }}"></script>
<script src="{{ asset('js/admin-script.js') }}"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
@yield('script')
@stack('script2')
</body>
</html>
