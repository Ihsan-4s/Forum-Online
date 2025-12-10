<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Auth Tabs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .auth-box {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 5% auto;
            padding: 2rem;
        }

        .nav-pills .nav-link {
            border-radius: 30px;
            color: #2962ff;
            border: 1px solid #2962ff;
        }

        .nav-pills .nav-link.active {
            background-color: #2962ff !important;
            color: white !important;
        }

        .btn-primary {
            background-color: #2962ff !important;
            border-color: #2962ff !important;
        }

        .social-buttons button {
            border: 1px solid #ccc;
            background: white;
        }

        .social-buttons i {
            font-size: 1.2rem;
        }
    </style>
</head>

<body style="background-image: url('{{ asset('images/bg.png') }}') ; background-size: cover; background-repeat: no-repeat; height: 100vh;">
    @if (Session('success'))
        <div class="alert alert-success ">
            {{Session::get('success')}}
        </div>
    @endif
    @if (Session('error'))
        <div class="alert alert-danger ">
            {{Session::get('error')}}
        </div>
    @endif
    <div class="auth-box text-center">
        <ul class="nav nav-pills nav-justified mb-4">
            <li class="nav-item ">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#loginTab">Login</button>
            </li>
            <li class="nav-item ms-3">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#registerTab">Register</button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="loginTab">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <h4 class="mb-3">Log in to Your Account</h4>
                    <input type="email" name="email" class="form-control mb-3" placeholder="Email">
                    <input type="password" name="password" class="form-control mb-3" placeholder="Password">
                    <button class="btn btn-primary w-100 mb-3" type="submit">LOG IN</button>
                </form>
            </div>

            <div class="tab-pane fade" id="registerTab">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <h4 class="mb-3">Sign Up for an Account</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3"><input type="text" name="first_name" class="form-control"
                                placeholder="First name"></div>
                        <div class="col-md-6 mb-3"><input type="text" name="last_name" class="form-control"
                                placeholder="Last name"></div>
                    </div>
                    <input type="email" name="email" class="form-control mb-3" placeholder="Email">
                    <input type="password" name="password" class="form-control mb-3" placeholder="Password">
                    <button class="btn btn-primary w-100 mb-3">SIGN UP</button>

                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
