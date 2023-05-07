<!doctype html>
<html lang="en" class="no-focus">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Admin | An Khang</title>
    <meta name="author" content="pixelcave">
    <meta name="robots" content="noindex, nofollow">

    <!-- Icons -->
    <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
    <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
    <!-- END Icons -->

    <!-- Stylesheets -->
    <!-- Fonts and Codebase framework -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,400i,600,700&display=swap">
    <link rel="stylesheet" id="css-main" href="assets/css/codebase.min.css">
</head>
<body>
<div id="page-container"
     class="sidebar-o sidebar-inverse enable-page-overlay side-scroll page-header-fixed main-content-narrow">
    <!-- Side Overlay-->
    <aside id="side-overlay">
        <!-- Side Header -->
        <div class="content-header content-header-fullrow">
            <div class="content-header-section align-parent">
                <!-- Close Side Overlay -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-circle btn-dual-secondary align-v-r" data-toggle="layout"
                        data-action="side_overlay_close">
                    <i class="fa fa-times text-danger"></i>
                </button>
                <!-- END Close Side Overlay -->

                <!-- User Info -->
                <div class="content-header-item">
                    <a class="img-link mr-5" href="be_pages_generic_profile.html">
                        <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar15.jpg" alt="">
                    </a>
                    <a class="align-middle link-effect text-primary-dark font-w600"
                       href="be_pages_generic_profile.html">John Smith</a>
                </div>
                <!-- END User Info -->
            </div>
        </div>
        <!-- END Side Header -->
    </aside>
    <!-- END Side Overlay -->

    <nav id="sidebar">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Side Header -->
            <div class="content-header content-header-fullrow px-15">
                <!-- Mini Mode -->
                <div class="content-header-section sidebar-mini-visible-b">
                    <!-- Logo -->
                    <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                                <span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
                            </span>
                    <!-- END Logo -->
                </div>
                <!-- END Mini Mode -->

                <!-- Normal Mode -->
                <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                    <!-- Close Sidebar, Visible only on mobile screens -->
                    <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                    <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r"
                            data-toggle="layout" data-action="sidebar_close">
                        <i class="fa fa-times text-danger"></i>
                    </button>
                    <!-- END Close Sidebar -->

                    <!-- Logo -->
                    <div class="content-header-item">
                        <a class="link-effect font-w700" href="index.html">
                            <i class="si si-fire text-primary"></i>
                            <span class="font-size-xl text-dual-primary-dark">An</span><span
                                class="font-size-xl text-primary">Khang</span>
                        </a>
                    </div>
                    <!-- END Logo -->
                </div>
                <!-- END Normal Mode -->
            </div>
            <!-- END Side Header -->

            <!-- Side Navigation -->
            <div class="content-side content-side-full">
                <ul class="nav-main">
                    <li>
                        <a href="/" class="active">
                            <i class="si si-cup"></i>
                            <span class="sidebar-mini-hide">Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- END Side Navigation -->
        </div>
        <!-- Sidebar Content -->
    </nav>
    <!-- END Sidebar -->

    <!-- Header -->
    <header id="page-header">
        <!-- Header Content -->
        <div class="content-header">
            <!-- Left Section -->
            <div class="content-header-section">
                <!-- Toggle Sidebar -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout"
                        data-action="sidebar_toggle">
                    <i class="fa fa-navicon"></i>
                </button>
                <!-- END Toggle Sidebar -->

                <!-- Open Search Section -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <button type="button" class="btn btn-circle btn-dual-secondary" data-toggle="layout"
                        data-action="header_search_on">
                    <i class="fa fa-search"></i>
                </button>
                <!-- END Open Search Section -->
            </div>
            <!-- END Left Section -->

            <!-- Right Section -->
            <div class="content-header-section">
                <!-- User Dropdown -->
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-rounded btn-dual-secondary" id="page-header-user-dropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user d-sm-none"></i>
                        <span class="d-none d-sm-inline-block">Admin</span>
                        <i class="fa fa-angle-down ml-5"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right min-width-200"
                         aria-labelledby="page-header-user-dropdown">
                        <a class="dropdown-item" href="op_auth_signin.html">
                            <i class="si si-logout mr-5"></i> Sign Out
                        </a>
                    </div>
                </div>
                <!-- END User Dropdown -->
            </div>
            <!-- END Right Section -->
        </div>
        <!-- END Header Content -->

        <!-- Header Search -->
        <div id="page-header-search" class="overlay-header">
            <div class="content-header content-header-fullrow">
                <form action="be_pages_generic_search.html" method="post">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <!-- Close Search Section -->
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <button type="button" class="btn btn-secondary" data-toggle="layout"
                                    data-action="header_search_off">
                                <i class="fa fa-times"></i>
                            </button>
                            <!-- END Close Search Section -->
                        </div>
                        <input type="text" class="form-control" placeholder="Search or hit ESC.."
                               id="page-header-search-input" name="page-header-search-input">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- END Header Search -->

        <!-- Header Loader -->
        <!-- Please check out the Activity page under Elements category to see examples of showing/hiding it -->
        <div id="page-header-loader" class="overlay-header bg-primary">
            <div class="content-header content-header-fullrow text-center">
                <div class="content-header-item">
                    <i class="fa fa-sun-o fa-spin text-white"></i>
                </div>
            </div>
        </div>
        <!-- END Header Loader -->
    </header>
    <!-- END Header -->

    <!-- Main Container -->
    <main id="main-container">
        <!-- Page Content -->
        <div class="content">
            @yield('content')
        </div>
        <!-- END Page Content -->
    </main>
    <!-- END Main Container -->

    <!-- Footer -->
    <footer id="page-footer" class="opacity-0">
        <div class="content py-20 font-size-sm clearfix">
            <div class="float-right">
                Crafted with <i class="fa fa-heart text-pulse"></i> by <a class="font-w600"
                                                                          href="https://1.envato.market/ydb"
                                                                          target="_blank">pixelcave</a>
            </div>
            <div class="float-left">
                <a class="font-w600" href="https://1.envato.market/95j" target="_blank">Codebase 3.4</a> &copy; <span
                    class="js-year-copy"></span>
            </div>
        </div>
    </footer>
    <!-- END Footer -->
</div>
<!-- END Page Container -->

<script src="assets/js/codebase.core.min.js"></script>
<script src="assets/js/codebase.app.min.js"></script>

<!-- Page JS Plugins -->
<script src="assets/js/plugins/chartjs/Chart.bundle.min.js"></script>

<!-- Page JS Code -->
<script src="assets/js/pages/be_pages_dashboard.min.js"></script>
</body>
</html>
