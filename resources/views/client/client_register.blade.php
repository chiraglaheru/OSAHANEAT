<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Client Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/preloader.min.css') }}">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet">
</head>

<body>
<div class="auth-page">
    <div class="container-fluid p-0">
        <div class="row g-0">

            <!-- LEFT SIDE FORM -->
            <div class="col-xxl-3 col-lg-4 col-md-5">
                <div class="auth-full-page-content d-flex p-sm-5 p-4">
                    <div class="w-100">
                        <div class="d-flex flex-column h-100">

                            <div class="mb-4 text-center">
                                <a href="#" class="d-block auth-logo">
                                    <img src="{{ asset('assets/images/logo-sm.svg') }}" height="28">
                                    <span class="logo-txt">Client Register</span>
                                </a>
                            </div>

                            <div class="auth-content my-auto">

                                <div class="text-center">
                                    <h5>Welcome Back!</h5>
                                    <p class="text-muted">Sign in to continue to Client.</p>
                                </div>

                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                @endif

                                @if (Session::has('error'))
                                    <li>{{ Session::get('error') }}</li>
                                @endif

                                @if (Session::has('success'))
                                    <li>{{ Session::get('success') }}</li>
                                @endif

                                <form class="mt-4" action="{{ route('client.register.submit') }}" method="post">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label">Restaurant Name</label>
                                        <input type="text" name="name" class="form-control" placeholder="Enter Name">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" class="form-control" placeholder="Enter Phone">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Address</label>
                                        <input type="text" name="address" class="form-control" placeholder="Enter Address">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" placeholder="Enter Email">
                                    </div>

                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <label class="form-label">Password</label>
                                            <a href="{{ route('admin.forget_password') }}" class="text-muted">Forgot password?</a>
                                        </div>

                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control" placeholder="Enter password">
                                            <button class="btn btn-light" type="button">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <button class="btn btn-primary w-100" type="submit">Register</button>
                                    </div>
                                </form>

                                <div class="mt-5 text-center">
                                    <p class="text-muted mb-0">
                                        Already have an account?
                                        <a href="{{ route('client.login') }}" class="text-primary fw-semibold">
                                            Login now
                                        </a>
                                    </p>
                                </div>

                            </div>

                            <div class="mt-4 text-center">
                                <p class="mb-0">
                                    © <script>document.write(new Date().getFullYear())</script>
                                    OshanaEat. Crafted with ❤️ by Osahaneat
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDE BLUE PANEL -->
            <div class="col-xxl-9 col-lg-8 col-md-7">
                <div class="auth-bg pt-md-5 p-4 d-flex">
                    <div class="bg-overlay bg-primary"></div>

                    <div class="row justify-content-center align-items-center w-100">
                        <div class="col-xl-7 text-white text-center">

                            <h3 class="mb-4">Why Restaurants Love This Platform</h3>

                            <p class="lead">
                                Managing orders across multiple food stalls is now effortless.
                                This system keeps everything organized and fast.
                            </p>

                            <p class="mt-4">
                                This ordering system reduces manual work and errors,
                                helping outlets serve customers better every day.
                            </p>

                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script src="{{ asset('backend/assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('backend/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>
