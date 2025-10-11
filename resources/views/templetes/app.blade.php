<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Forum App')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

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
    <!-- Top Navbar -->
    <nav class="navbar navbar-light bg-white border-bottom d-lg-none px-3">
        <button class="mobile-toggle" id="mobileToggle">
            <i class="fa-solid fa-bars"></i>
        </button>
        <span class="fw-bold text-primary">PointSale</span>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar d-flex flex-column justify-content-between p-3" id="sidebar">
        <div>
            <div class="logo d-none d-lg-flex">
                <i class="fa-solid fa-circle-nodes"></i>
                <span class="logo-text">PointSale</span>
            </div>

            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('index') }}" class="nav-link active"><i
                            class="fa-solid fa-house"></i><span>Home</span></a>
                </li>
                <li><a href="{{ route('threads.create') }}" class="nav-link"><i class="fa-solid fa-plus"></i><span>Create Thread</span></a></li>
                <li><a href="" class="nav-link"><i class="fa-solid fa-tag"></i><span>Tags</span></a></li>
                <li><a href="#" class="nav-link"><i class="fa-solid fa-bookmark"></i><span>Saved</span></a></li>
                <li><a href="#" class="nav-link"><i class="fa-solid fa-trash"></i><span>Trash</span></a></li>
            </ul>
        </div>


        <a href="{{ Auth::check() ? route('account') : route('login') }}"
            class="user-footer d-flex align-items-center gap-2 text-decoration-none text-reset" role="button"
            tabindex="0" title="{{ Auth::check() ? 'View profile' : 'Login' }}">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Guest') }}" alt="">
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
    <script>
        const sidebar = document.getElementById('sidebar');
        const mobileToggle = document.getElementById('mobileToggle');

        // Mobile sidebar toggle
        mobileToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });

        // Tutup sidebar kalau klik di luar (khusus HP)
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 992 && !sidebar.contains(e.target) && !mobileToggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>
</body>

</html>
