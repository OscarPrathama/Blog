<nav class="visitor-navbar navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="{{ route('frontpage') }}">Logo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{--
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a  class="nav-link {{ request()->is('/') ? ' active' : '' }}"
                        aria-current="page"
                        href="{{ route('frontpage') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a  class="nav-link {{ request()->is('blogs') ? ' active' : '' }}"
                        href="{{ route('blogs') }}">Blogs</a>
                </li>
                <li class="nav-item">
                    <a  class="nav-link {{ request()->is('about-us') ? ' active' : '' }}"
                        href="{{ route('about-us') }}">About Us</a>
                </li>
                <li class="nav-item">
                    <a  class="nav-link {{ request()->is('contact') ? ' active' : '' }}"
                        href="{{ route('contact') }}">Contact Us</a>
                </li>
            </ul>
            --}}
            @if (!empty($main_menu))
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @foreach ($main_menu as $key => $value)
                        @php
                        $menu_request = $value->menu_title == 'Home' ? '/' : \Str::lower(\Str::slug($value->menu_title));
                        @endphp
                        <li class="nav-item">
                            <a  class="nav-link {{ request()->is($menu_request) ? ' active' : '' }}"
                                aria-current="page"
                                href="{{ $value->menu_link }}">{{ $value->menu_title }}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search post" aria-label="Search">
            </form>
        </div>
    </div>
</nav>
