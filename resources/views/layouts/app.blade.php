<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Oxygen Gym LB')</title>
    <link rel="icon" href="{{ asset('images/o2-icon.png') }}" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light app-layout-body">
<nav class="navbar navbar-expand-lg navbar-dark app-navbar flex-shrink-0">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <button class="navbar-toggler d-md-none me-2 border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand d-flex align-items-center gap-2 mb-0" href="{{ route('dashboard') }}">
                <span class="app-brand-text"> Oxygen Gym</span>
            </a>
        </div>

        <div class="d-flex align-items-center gap-2">
            @auth
                <span class="text-white-50 small">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-outline-light btn-sm" type="submit">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</nav>

<!-- Sidebar + main: independent scroll on md+ -->
<div class="container-fluid app-layout-workspace g-0">
    <div class="row g-0 app-layout-row flex-md-nowrap">
        <aside class="col-md-3 col-lg-2 p-0 app-sidebar app-sidebar-col offcanvas-md offcanvas-start text-bg-dark" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
            <div class="offcanvas-header d-md-none border-bottom border-secondary">
                <h5 class="offcanvas-title text-white" id="sidebarMenuLabel">Menu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" data-bs-target="#sidebarMenu" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-md-flex flex-column p-0 pt-lg-3">
                <div class="p-3 w-100">
                    <div class="small text-uppercase fw-semibold text-white-50 mb-2 px-2">System</div>
                    <div class="list-group list-group-flush app-sidebar-list">
                        <a class="list-group-item list-group-item-action app-sidebar-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}">Dashboard</a>
                        @can('manage-users')
                            {{-- <div class="small text-uppercase fw-semibold text-white-50 mt-3 mb-2 px-2">System</div> --}}
                            <a class="list-group-item list-group-item-action app-sidebar-link @if(request()->routeIs('users.*')) active @endif" href="{{ route('users.index') }}">Users</a>
                        @endcan

                        @can('manage-members')
                            <div class="small text-uppercase fw-semibold text-white-50 mt-3 mb-2 px-2">Members</div>
                            <a class="list-group-item list-group-item-action app-sidebar-link @if(request()->routeIs('members.index') || request()->routeIs('members.edit') || request()->routeIs('members.show')) active @endif" href="{{ route('members.index') }}">Members</a>
                        @endcan

                        @if (auth()->user()->can('manage-subscriptions') || auth()->user()->can('manage-members'))
                            <div class="small text-uppercase fw-semibold text-white-50 mt-3 mb-2 px-2">Subscriptions</div>
                            @can('manage-subscriptions')
                                <a class="list-group-item list-group-item-action app-sidebar-link @if(request()->routeIs('plans.index') || request()->routeIs('plans.edit') || request()->routeIs('plans.show')) active @endif" href="{{ route('plans.index') }}">Plans</a>
                            @endcan
                            <a class="list-group-item list-group-item-action app-sidebar-link @if(request()->routeIs('subscriptions.*')) active @endif" href="{{ route('subscriptions.create') }}">Create Subscription</a>
                        @endif

                    </div>
                </div>
            </div>
        </aside>

        <main class="col-md-9 ms-sm-auto col-lg-10 p-4 app-main-content app-main-col app-main-bg-gradient-brand">
            @include('partials.flash')
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

