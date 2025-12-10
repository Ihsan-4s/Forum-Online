<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Forum App')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        /* Sidebar container */
        .sidebar {
            min-height: 100vh;
            background-color: #ffffff;
            border-right: 1px solid #dee2e6;
            width: 250px;
            position: fixed;
            transition: all 0.3s ease;
            z-index: 1040;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar .nav-link {
            color: #495057;
            font-weight: 500;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #e9f2ff;
            color: #0d6efd;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            padding: 2rem;
            transition: all 0.3s ease;
        }

        .collapsed+.main-content {
            margin-left: 80px;
        }

        .sidebar .logo {
            font-weight: 600;
            color: #0d6efd;
            padding: 1.2rem 1rem;
            font-size: 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-footer {
            border-top: 1px solid #dee2e6;
            padding: 1rem;
        }

        .user-footer img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        /* Mobile view */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                width: 250px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0 !important;
            }

            .mobile-toggle {
                display: inline-flex !important;
            }
        }

        .mobile-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            background: none;
            border: none;
            font-size: 1.25rem;
            color: #0d6efd;
        }
    </style>
</head>

<body>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column justify-content-between p-3" id="sidebar">
        <div>
            <div class="logo d-none d-lg-flex">
                <i class="fa-solid fa-circle-nodes"></i>
                <span class="logo-text">PointSale</span>
            </div>

            <ul class="nav nav-pills flex-column mb-auto">
                @if (Auth::check() && Auth::user()->role == 'admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.getAllUsers') }}" class="nav-link ">
                            <span>User</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.threads.index') }}" class="nav-link">
                            <span>Threads</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.comments.index') }}" class="nav-link">
                            <span>Comments</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('logout') }}" class="nav-link ">
                            <span>Logout</span>
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('index') }}"
                            class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}">
                            <i class="fa-solid fa-house"></i><span>Threads</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('threads.explore') }}"
                            class="nav-link {{ request()->routeIs('threads.explore') ? 'active' : '' }}">
                            <i class="fa-brands fa-searchengin"></i><span>Explore</span>
                        </a>
                    </li>
                    <li>
                        @auth
                            <a href="{{ route('threads.create') }}"
                                class="nav-link {{ request()->routeIs('threads.create') ? 'active' : '' }}">
                                <i class="fa-solid fa-plus"></i><span>Create Thread</span>
                            </a>
                        @endauth
                    </li>
                    <li>
                        @auth
                            <a href="{{ route('threads.trash') }}"
                                class="nav-link {{ request()->routeIs('threads.trash') ? 'active' : '' }}">
                                <i class="fa-solid fa-trash"></i><span>Trash Bin</span>
                            </a>
                        @endauth
                    </li>
                @endif

            </ul>
        </div>


        <a href="{{ Auth::check() ? route('account.index') : route('login') }}"
            class="user-footer d-flex align-items-center gap-2 text-decoration-none text-reset" role="button"
            tabindex="0" title="{{ Auth::check() ? 'View profile' : 'Login' }}">
            @if (Auth::check() && Auth::user()->profile_picture)
                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" class="rounded-circle mb-3"
                    width="80" height="80" style="object-fit: cover">
            @elseif (Auth::check() && Auth::user()->role == 'admin')
                <img src="https://ui-avatars.com/api/?name=Admin" class="rounded-circle mb-3" width="80"
                    height="80" style="object-fit: cover">
            @else
                <img src="https://ui-avatars.com/api/?name=Guest" class="rounded-circle mb-3" width="80"
                    height="80" style="object-fit: cover">
            @endif
            <div>
                <strong>{{ Auth::user()->name ?? 'Guest' }}</strong><br>
                <small class="text-muted">{{ Auth::check() ? 'Logged in' : 'Not logged in' }}</small>
            </div>
        </a>
    </div>

    <!-- Main content -->
    <div class="main-content" id="mainContent">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('script')
</body>

</html>
