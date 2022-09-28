<!doctype html>
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> --}}
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ env('APP_NAME') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    {{-- icon --}}
    <link rel="stylesheet" href="https://unpkg.com/@tabler/icons@latest/iconfont/tabler-icons.min.css">
    <!-- Styles -->
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/demo.min.css') }}" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/datatables/datatables.min.css') }}" />

    @stack('style')

</head>

<body class="">
    <div class="page">
        <header class="navbar navbar-expand-md navbar-dark navbar-overlap d-print-none">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="/">
                        <img src="{{ asset('static/logo-white.svg') }}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
                    </a>
                </h1>
                <div class="navbar-nav flex-row order-md-last">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                            <span class="avatar avatar-sm" style="background-image: url(/static/avatar/user-1.png)"></span>
                            <div class="d-none d-xl-block ps-2">
                                @if (Auth::check())
                                    <div>{{ auth()->user()->name }}</div>
                                    <div class="mt-1 small text-muted">{{ auth()->user()->getRoleNames()[0] }}</div>
                                @endif
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            @if (Auth::check())
                                <a href="{{ route('profile.index') }}" class="dropdown-item">
                                    <i class="ti ti-settings fs-2 me-1"></i> Pengaturan Akun
                                </a>
                                <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="ti ti-logout fs-2 me-1"></i> Logout
                                </a>
                                <x-form id="logout-form" action="{{ route('logout') }}" style="display: none;"> </x-form>
                            @else
                                <a href="/" class="dropdown-item">
                                    <i class="ti ti-home fs-2 me-1"></i> Home </a>
                                <a href="{{ route('login') }}" class="dropdown-item">
                                    <i class="ti ti-login fs-2 me-1"></i> Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="dropdown-item">
                                        <i class="ti ti-user-plus fs-2 me-1"></i> Daftar </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <div class="d-flex flex-column flex-md-row flex-fill align-items-stretch align-items-md-center">
                        <ul class="navbar-nav">
                            @foreach ($menuData as $m)                            
                                @if (Auth::check() && Auth::user()->hasRole($m->role))
                                    <li class="nav-item @isset($m->sub) dropdown @endisset">
                                        <a href="@if(isset($m->sub)) #navbar-base @else {{ url($m->url) }} @endif" 
                                            class="nav-link @isset($m->sub) dropdown-toggle @endisset 
                                            @if(isset($m->sub))
                                                @if (Str::startsWith(Route::currentRouteName(), collect($m->sub)->pluck('url')->all()))
                                                    active
                                                @endif
                                            @else
                                                {{ (Str::startsWith(Route::currentRouteName(), $m->url)) ? 'active' : '' }}
                                            @endif
                                            "
                                            @isset($m->sub)
                                                data-bs-toggle="dropdown"
                                                data-bs-auto-close="outside" role="button" aria-expanded="false"
                                            @endisset
                                            >
                                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                                <i class="ti ti-{{ $m->icon }} fs-2"></i>
                                            </span>
                                            <span class="nav-link-title">
                                                {{ $m->title }}
                                            </span>
                                        </a>
                                        @isset($m->sub)
                                            <div class="dropdown-menu">
                                                @foreach ($m->sub as $sub)
                                                    @if (Auth::check() && Auth::user()->hasRole($sub->role))
                                                        <a href="{{ url($sub->url) }}" class="dropdown-item {{ (Str::startsWith(Route::currentRouteName(), $sub->url)) ? 'active' : '' }}">
                                                            {{ $sub->title }}
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endisset
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        <div class="page-wrapper">
            <div class="page-body">
                @yield('content')
            </div>
            <footer class="footer footer-transparent d-print-none p-1">
                <div class="container-xl">
                    <div class="row text-center align-items-center flex-row-reverse">
                        <div class="col-lg-auto ms-lg-auto">
                            
                        </div>
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <ul class="list-inline list-inline-dots mb-0">
                                <li class="list-inline-item">
                                    Copyright &copy; 2022
                                    <a href="." class="link-secondary">Tabler</a>.
                                    All rights reserved.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Tabler Libs -->

    <!-- Tabler Core -->
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('dist/js/tabler.min.js') }}"></script>
    <script src="{{ asset('dist/js/demo.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="{{ asset('vendor/datatables/datatables.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    <script src="{{ asset('js/sweetalert/sweetalert2@11.js') }}"></script>


    @yield('page-script')

</body>

</html>