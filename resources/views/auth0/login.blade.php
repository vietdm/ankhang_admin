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
    <link rel="icon" type="image/png" sizes="192x192"
        href="{{ asset('assets/media/favicons/favicon-192x192.png') }}">
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
            <div class="bg-body-dark bg-pattern" style="background-image: url('{{ asset('images/bg-pattern-inverse.png') }}');">
                <div class="row mx-0 justify-content-center">
                    <div class="hero-static col-lg-6 col-xl-4">
                        <div class="content content-full overflow-hidden">
                            <div class="py-30 text-center">
                                <div class="link-effect font-w700" style="font-size: 22px; color: #3f9ce8;">
                                    <i class="si si-fire"></i>
                                    <span class="font-size-xl text-primary-dark">An</span><span class="font-size-xl">Khang</span>
                                </div>
                                <h1 class="h4 font-w700 mt-30 mb-10">Chào mừng tới trang quản trị</h1>
                                <h2 class="h5 font-w400 text-muted mb-0">Chúc một ngày tốt lành!</h2>
                            </div>
                            <form action="#" method="post">
                                <div class="block block-themed block-rounded block-shadow">
                                    <div class="block-header bg-gd-dusk">
                                        <h3 class="block-title">Đăng nhập</h3>
                                    </div>
                                    <div class="block-content">
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label for="username">Tài khoản</label>
                                                <input type="text" class="form-control" id="username" name="username">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12">
                                                <label for="password">Mật khẩu</label>
                                                <input type="password" class="form-control" id="password" name="password">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-alt-primary">
                                                    <i class="si si-login mr-10"></i> Đăng nhập
                                                </button>
                                            </div>
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
        $('#form-login').on('submit', function(e) {
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
            $.post('/auth0/login', {
                password,
                _token: "{{ csrf_token() }}"
            }).then((result) => {
                Swal.fire({
                    title: 'Success!',
                    text: result.message,
                    icon: 'success'
                });
                if (typeof result.next == 'string') {
                    setTimeout(() => {
                        window.location.href = result.next;
                    }, 1000);
                    return;
                }
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
            $("#username").focus();
        });
    </script>
</body>

</html>
