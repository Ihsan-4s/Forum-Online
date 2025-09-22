<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.2.0/mdb.min.css" rel="stylesheet" />

</head>

<body>
    <style>
        /* ===== Navbar Glassmorphism Premium ===== */
        .navbar {
            background: rgba(255, 255, 255, 0.08) !important;
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 16px;
            margin: 12px 32px;
            padding: 6px 18px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        /* Brand */
        .navbar-brand img {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }

        /* Links */
        .navbar .nav-link {
            color: #2f2f2f !important;
            font-weight: 500;
            position: relative;
            padding: 8px 14px;
            transition: all 0.3s ease;
        }

        .navbar .nav-link:hover {
            color: #18cb96 !important;
        }

        /* Underline hover */
        .navbar .nav-link::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: 4px;
            width: 0%;
            height: 2px;
            background-color: #18cb96;
            transition: width 0.3s ease;
        }

        .navbar .nav-link:hover::after {
            width: 100%;
        }

        /* Active state */
        .navbar .nav-link.active {
            color: #18cb96 !important;
            font-weight: 600;
        }

        .navbar .nav-link.active::after {
            width: 100%;
        }

        /* Dropdown */
        .dropdown-menu {
            background: rgba(30, 30, 30, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            margin-top: 8px;
            animation: dropdownFade 0.25s ease;
        }

        .dropdown-item {
            color: #f1f1f1;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background: rgba(13, 202, 240, 0.15);
            color: #18cb96;
            border-radius: 6px;
        }

        /* Avatar */
        .navbar img.rounded-circle {
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: 0.3s;
        }

        .navbar img.rounded-circle:hover {
            border-color: #18cb96;
            box-shadow: 0 0 8px #18cb96;
        }

        /* Animasi Dropdown */
        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(-8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <!-- Container wrapper -->
        <div class="container-fluid mx-5">
            <!-- Toggle button -->
            <button data-mdb-collapse-init class="navbar-toggler" type="button"
                data-mdb-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible wrapper -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Navbar brand -->
                <a class="navbar-brand mt-2 mt-lg-0" href="#">
                    <img src="{{ asset('images/logo.png') }}" height="40" width="100" alt=""
                        loading="lazy" />
                </a>
                <!-- Left links -->
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @if (Auth::check() && Auth::user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Data</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Blablabla</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Your Threads</a>
                        </li>

                    @endif
                </ul>
                <!-- Left links -->
            </div>
            <!-- Collapsible wrapper -->

            <!-- Right elements -->
            <div class="d-flex align-items-center">
                @if (Auth::check())
                    <div class="mx-3">
                        <nav>
                            <ul class="list-unstyled d-flex mb-0">
                                <li><a href="{{ route('threads.create') }}"><button type="button"
                                            class="btn btn-light mx-2"><i class="fa-solid fa-plus"></i>
                                            Create</button></a></li>
                                <li><button type="button" class="btn btn-light"><i
                                            class="fa-solid fa-bell"></i></button></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="dropdown">
                        <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#"
                            id="navbarDropdownMenuAvatar" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img src="https://mdbcdn.b-cdn.net/img/new/avatars/2.webp" class="rounded-circle"
                                height="40" width="40" alt="Black and White Portrait of a Man" loading="lazy" />
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                            <li>
                                <a class="dropdown-item" href="{{ route('account') }}">My profile</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#">Settings</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login.form') }}">
                        <button data-mdb-ripple-init type="button" class="btn btn-link px-3 me-2">Login / Sign
                            Up</button>
                    </a>
                @endif
                <!-- Icon -->
                <!-- Notifications -->

                <!-- Avatar -->

            </div>
            <!-- Right elements -->
        </div>
        <!-- Container wrapper -->
    </nav>
    <!-- Navbar -->

    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.2.0/mdb.umd.min.js"></script>
</body>

</html>
