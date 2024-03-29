<!DOCTYPE html>
<html>

<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <title>Sign in</title>

    <!-- Site favicon -->
    <link rel=" shortcut icon" type="image/png" sizes="16x16" href="{{ asset('vendors/images/logo-login-register.png') }}">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/core.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/icon-font.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/styles/style.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/styles/scrollbar.css') }}">

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119386393-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-119386393-1');
    </script>
</head>

<body class="login-page">
    <div class="login-header box-shadow">
        <div class="container-fluid d-flex justify-content-between align-items-center">
            <div class="brand-logo">
                <a>
                    <img src="{{ asset('vendors/images/logo-boarding-house.png') }}" alt="">
                </a>
            </div>
        </div>
    </div>

    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 col-lg-7">
                    <img src="{{ asset('vendors/images/login-img-background.png') }}" alt="">
                </div>
                <div class="col-md-6 col-lg-5">
                    <div class="login-box bg-white box-shadow border-radius-10">
                        <div class="login-title">
                            <h2 class="text-center text-primary">Sign in</h2>
                        </div>

                        {{-- NOTE: alert success message after registration --}}
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- NOTE: alert error message after registration --}}
                        @if (session('errorLogin'))
                            <div class="alert alert-danger" role="alert">
                                <strong>Error! </strong>{{ session('errorLogin') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif


                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul style="list-style-type:circle">
                                    @foreach ($errors->all() as $error)
                                        <li><strong>Error! </strong>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.action') }}">
                            @csrf
                            <div class="input-group custom">
                                <input type="text" class="form-control form-control-lg"
                                    placeholder="Username or Email address" name="credential" id="username" autofocus
                                    autocomplete="on" required value="{{ old('credential') }}">

                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="icon-copy dw dw-user1"></i></span>
                                </div>
                            </div>
                            <div class="input-group custom">
                                <input type="password" class="form-control form-control-lg" placeholder="Password"
                                    id="password" name="password" required minlength="6"
                                    oninvalid="this.setCustomValidity('Password must be at least 6 characters')"
                                    oninput="this.setCustomValidity('')" value="{{ old('password') }}">
                                <div class="input-group-append custom">
                                    <span class="input-group-text"><i class="dw dw-eye" id="togglePassword"></i></span>
                                </div>
                            </div>
                            <div class="row pb-30">
                                <div class="col-6">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="remember_me"
                                            name="remember_me">
                                        <label class="custom-control-label" for="remember_me">Remember me</label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="forgot-password">
                                        <a href="{{ url('forgot-password') }}">Forgot password?</a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="input-group mb-0">
                                        <input class="btn btn-primary btn-lg btn-block" type="submit" value="Login"
                                            onclick="checkEmail()">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row" style="margin-top: 5%">
                            <div class="col-sm-12">
                                <div class="input-group mb-0">
                                    Don't have an account?
                                    <a href="{{ route('register') }}"
                                        style="margin-left: 3%; color: blue">Register</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="font-16 weight-600 pt-10 pb-10 text-center" data-color="#707373">
                                    Or
                                </div>

                                <div class="input-group mb-0">
                                    <a class="btn btn-outline-primary btn-lg btn-block d-flex justify-content-flex-start"
                                        href="{{ route('auth.googleRedirect') }}"><img
                                            src="{{ asset('vendors/images/google-logo.png') }}"
                                            style="height: 30px; width: 30px; margin-right: 10%; margin-left: 5%" />
                                        Sign in with Google</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- js -->
    <script src="{{ asset('vendors/scripts/core.js') }}"></script>
    <script src="{{ asset('vendors/scripts/script.min.js') }}"></script>
    <script src="{{ asset('vendors/scripts/process.js') }}"></script>
    <script src="{{ asset('vendors/scripts/layout-settings.js') }}"></script>
    <script src="{{ asset('vendors/scripts/validate.js') }}"></script>
</body>

</html>
