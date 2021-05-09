<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">

        {{-- Logo --}}
        <a class="navbar-brand" href="{{ URL::to('/') }}">Logo</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('admin/dashboard') ? ' active' : '' }}" aria-current="page" href="{{ route('dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('admin/pages') ? ' active' : '' }}" href="#" id="pagesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Pages
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="pagesDropdown">
                        <li><a class="dropdown-item" href="{{ route('pages-index') }}">All Pages</a></li>
                        <li><a class="dropdown-item" href="{{ route('pages-create') }}">Add New</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('admin/posts') ? ' active' : '' }}" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Posts
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('posts-index') }}">All Posts</a></li>
                        <li><a class="dropdown-item" href="{{ route('posts-create') }}">Add New</a></li>
                        {{-- <li><hr class="dropdown-divider"></li> --}}
                        {{-- <li><a class="dropdown-item" href="#">Add New Category</a></li> --}}
                        {{-- <li><a class="dropdown-item" href="#">Categories</a></li> --}}
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('media') }}">
                        Media
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link " aria-current="page" href="{{ route('inbox.index') }}">
                        Inbox
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('admin/settings') ? ' active' : '' }}" href="javascript:void(0)" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Settings
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('settings.index') }}">General</a></li>
                        <li><a class="dropdown-item" href="{{ route('menus.index') }}">Menus</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link {{ request()->is('admin/my-profile') ? ' active' : '' }}"
                        aria-current="page"
                        href="{{ route('my-profile') }}">My Profile</a>
                </li>
            </ul>

            <div class="d-flex">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a
                                class="nav-link"
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault();this.closest('form').submit();">
                                {{ __('Log out') }}</a>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</nav>
