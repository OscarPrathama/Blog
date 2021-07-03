<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title')</title>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Bootstrap CSS -->
<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
<link href="{{ asset('css/style.css') }}" rel="stylesheet">

{{-- custom css --}}
@stack('style')

</head>
<body class="post-{{ isset($title) ? Str::lower($title) : 'default' }}-template">

{{-- navbar --}}
@if ( \Route::current()->getName() != 'login')
    @include('layouts.navbar')
@endif

{{-- content --}}
@yield('content')

{{-- alert --}}
@if ( \Route::current()->getName() != 'login')
    @include('layouts.footer')
@endif

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="{{ asset('js/jquery-3.5.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
<script src="{{ asset('js/script.js') }}"></script>

@yield('script')
</body>
</html>
