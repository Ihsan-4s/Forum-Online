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

    <style>
        /* Tombol utama */
        .btn-primary {
            background-color: #18cb96 !important;
            border-color: #18cb96 !important;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #13a87d !important;
            border-color: #13a87d !important;
        }

        /* Nav pills */
        .nav-pills .nav-link.active {
            background-color: #18cb96 !important;
        }

        .nav-pills .nav-link {
            color: #18cb96;
        }

        .nav-pills .nav-link:hover {
            color: #13a87d;
        }

        /* Form focus */
        .form-outline .form-control:focus~.form-label,
        .form-outline .form-control:focus {
            color: #18cb96 !important;
            border-color: #18cb96 !important;
            box-shadow: 0 0 0 0.2rem rgba(24, 203, 150, 0.25);
        }

        /* Checkbox & radio */
        .form-check-input:checked {
            background-color: #18cb96 !important;
            border-color: #18cb96 !important;
        }

        /* Link highlight */
        a {
            color: #18cb96;
        }

        a:hover {
            color: #13a87d;
        }
    </style>

</head>

<body
    style="background-image: url('{{ asset('images/bg.png') }}') ; background-size: cover; background-repeat: no-repeat; height: 100vh;">


    @if (Session('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
    @endif

    @if (Session('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
    @endif
    <!-- Pills navs -->
    <nav class="mx-5 my-3 d-block text-center">
        <ul class="nav nav-pills mb-3 nav-justified " id="ex1" role="tablist"
            style="max-width: 800px; margin: 0 auto; ">
            <li class="nav-item">
                <a class="nav-link active py-3 px-3" id="tab-login" data-mdb-pill-init href="#pills-login"
                    role="tab">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link py-3 px-3" id="tab-register" data-mdb-pill-init href="#pills-register"
                    role="tab">Register</a>
            </li>
        </ul>
    </nav>
    <!-- Pills navs -->

    <!-- Pills content -->
    <div class="tab-content w-50 d-block mx-auto my-5">
        <div class="tab-pane fade show active" id="pills-login" role="tabpanel" aria-labelledby="tab-login">
            <form method="POST" action="{{ route('login') }}"
                class="w-75 d-block mx-auto p-5 my-5 bg-light shadow bg-body-tertiary rounded">
                @csrf
                <h3 class="text-center">Sign In with :</h3>
                <div class="text-center mb-3">

                    <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                        <i class="fab fa-facebook-f"></i>
                    </button>

                    <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                        <i class="fab fa-google"></i>
                    </button>

                    <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                        <i class="fab fa-twitter"></i>
                    </button>

                    <button data-mdb-ripple-init type="button" class="btn btn-secondary btn-floating mx-1">
                        <i class="fab fa-github"></i>
                    </button>
                </div>

                <p class="text-center">or:</p>

                <!-- Email input -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="email" id="loginName" class="form-control" name="email" />
                    <label class="form-label" for="loginName">Email or username</label>
                </div>

                <!-- Password input -->
                <div data-mdb-input-init class="form-outline mb-4">
                    <input type="password" id="loginPassword" class="form-control" name="password" />
                    <label class="form-label" for="loginPassword">Password</label>
                </div>

                <!-- 2 column grid layout -->
                <div class="d-flex justify-content-center align-items-center mb-4 gap-3">
                    <!-- Checkbox -->
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" value="" id="loginCheck" checked />
                        <label class="form-check-label" for="loginCheck"> Remember me </label>
                    </div>
                    <!-- Simple link -->
                    <a href="#!">Forgot password?</a>
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>
            </form>
        </div>
        <div class="tab-pane fade" id="pills-register" role="tabpanel" aria-labelledby="tab-register">

            <form method="POST" action="{{ route('register') }}"
                class="w-75 d-block mx-auto p-5 my-5 bg-light shadow bg-body-tertiary rounded">
                @csrf
                <div class="text-center mb-3">
                    <h4 class="mb-4">Sign up </h4>

                    <!-- Name input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" id="registerName" class="form-control" name="first_name" />
                        <label class="form-label" for="registerName">First Name</label>
                    </div>

                    <!-- Username input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" id="registerUsername" class="form-control" name="last_name" />
                        <label class="form-label" for="registerUsername">Last Name</label>
                    </div>

                    <!-- Email input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="email" id="registerEmail" class="form-control" name="email" />
                        <label class="form-label" for="registerEmail">Email</label>
                    </div>

                    <!-- Password input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="password" id="registerPassword" class="form-control" name="password" />
                        <label class="form-label" for="registerPassword">Password</label>
                    </div>

                    <!-- Repeat Password input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="password" id="registerRepeatPassword" class="form-control" name="repeat" />
                        <label class="form-label" for="registerRepeatPassword">Repeat password</label>
                    </div>

                    <!-- Checkbox -->
                    <div class="form-check d-flex justify-content-center mb-4">
                        <input class="form-check-input me-2" type="checkbox" value="" id="registerCheck"
                            checked aria-describedby="registerCheckHelpText" />
                        <label class="form-check-label" for="registerCheck">
                            I have read and agree to the terms
                        </label>
                    </div>

                    <!-- Submit button -->
                    <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-block mb-3">Sign
                        Up</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.2.0/mdb.umd.min.js"></script>
</body>

</html>
