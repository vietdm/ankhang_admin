<!doctype html>
<html lang="en" class="no-focus">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>{{ config('info.title') }}</title>
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="{{ asset('assets/media/favicons/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('assets/media/favicons/favicon-192x192.png') }}">
    <link rel="apple-touch-icon" sizes="180x180"
          href="{{ asset('assets/media/favicons/apple-touch-icon-180x180.png') }}">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Fonts and Codebase framework -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap">
    <link rel="stylesheet" id="css-main" href="{{ asset('assets/css/codebase.css') }}">
</head>
<body>

<div id="page-container" class="main-content-boxed">
    <main id="main-container">
        <div class="bg-image bg-pattern"
             style="background-image: url('{{ asset('assets/media/photos/photo34@2x.jpg') }}');">
            <div class="row mx-0 justify-content-center bg-white-op-95">
                <div class="hero-static col-lg-6 col-xl-4">
                    <div class="content content-full overflow-hidden">
                        <div class="py-30 text-center">
                            <div class="link-effect text-pulse font-w700">
                                <i class="si si-fire" style="color: #3eb2f2"></i>
                                <span class="font-size-xl text-pulse-dark">An</span>
                                <span class="font-size-xl" style="color: #3eb2f2">Khang</span>
                            </div>
                            <h1 class="h4 font-w700 mt-30 mb-10">Welcome back, Administator</h1>
                        </div>
                        <form id="form-login" action="" method="post">
                            <div class="block block-themed block-rounded block-shadow w-100 m-auto"
                                 style="max-width: 550px">
                                <div class="block-header" style="background-color: #3eb2f2">
                                    <h3 class="block-title text-center font-w600" style="font-size: 22px">Đăng nhập</h3>
                                </div>
                                <div class="block-content">
                                    <div class="form-group text-center">
                                        <img class="img-avatar img-avatar96"
                                             src="{{ asset('assets/media/avatars/avatar15.jpg') }}" alt="">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-12">
                                            <input type="password" class="form-control text-center" id="password"
                                                   placeholder="Password"/>
                                        </div>
                                    </div>
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn btn-alt-primary">
                                            <i class="si si-lock-open mr-10"></i>
                                            Login
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="{{ asset('assets/js/codebase.core.min.js') }}"></script>
<script src="{{ asset('assets/js/codebase.app.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $('#form-login').on('submit', function (e) {
        e.preventDefault();
        const password = $('#password').val().trim();
        if (password === '') {
            Swal.fire({
                title: 'Error!',
                text: 'Hãy nhập mật khẩu để đăng nhập',
                icon: 'error'
            });
            return;
        }
        $.post('/auth0/login', {password, _token: "{{ csrf_token() }}"}).then((result) => {
            Swal.fire({
                title: 'Success!',
                text: result.message,
                icon: 'success'
            });
            setTimeout(() => {
                const url = new URL(window.location.href);
                const next = url.searchParams.get("next");
                window.location.href = next ?? '/';
            }, 1000);
        }).catch((error) => {
            Swal.fire({
                title: 'Error!',
                text: error.responseJSON.message,
                icon: 'error'
            });
        });
    });
    $(() => {
        $("#password").focus();
    });
</script>
</body>
</html>
