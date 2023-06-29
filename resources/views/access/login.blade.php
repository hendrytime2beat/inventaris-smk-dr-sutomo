<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/logo/logo.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo/logo.png') }}">
    <title>Login - Inventaris SMK Dr. Sutomo Temanggung</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
    <link id="pagestyle" href="{{ asset('assets/css/soft-ui-dashboard.css?v=1.0.7') }}" rel="stylesheet" />
</head>

<body class="">
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-75">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-8">
                                <div class="card-header pb-0 text-left bg-transparent">
                                    <h3 class="font-weight-bolder text-success text-gradient">Selamat Datang Kembali</h3>
                                    <p class="mb-0">Masukkan username dan password anda untuk login</p>
                                </div>
                                <div class="card-body">
                                    <form role="form" method="post" action="{{ route('access.login.act') }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <label>Username</label>
                                        <div class="mb-3">
                                            @error('username')
                                            <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            <input type="text" class="form-control" name="username" placeholder="Username" id="username" autocomplete="new-password">
                                        </div>
                                        <label>Password</label>
                                        <div class="mb-3">
                                            @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                            <input type="password" class="form-control" name="password" id="password" placeholder="Password" autocomplete=" new-password">
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                                            <label class="form-check-label" for="rememberMe">Ingat Saya</label>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn bg-gradient-success w-100 mt-4 mb-0">Login</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('{{ asset('assets/img/login.png') }}')"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row">
                <div class="col-8 mx-auto text-center mt-1">
                    <p class="mb-0 text-secondary">
                        Copyright © <script>
                            document.write(new Date().getFullYear())
                        </script> Inventaris SMK Dr. Sutomo Temanggung
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script src="{{ asset('assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script>
        localStorage.setItem('menu-active', 'dashboard');
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="{{ asset('assets/js/soft-ui-dashboard.min.js?v=1.0.7') }}"></script>
</body>

</html>