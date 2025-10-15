<!DOCTYPE html>
<html lang="en" data-skin="saas" data-bs-theme="light" data-menu-color="light" data-topbar-color="light"
    data-layout-position="fixed" data-sidenav-size="default" data-sidenav-user="true">
@include('dashboard.includes.head')

<body>
    <!-- Begin page -->
    <div class="wrapper">

        @include('dashboard.includes.sidebar')


        @include('dashboard.includes.topbar')

        <!-- ============================================================== -->
        <!-- Start Main Content -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="container-fluid">
                @yield('content')
            </div>
            <!-- container -->

            @include('dashboard.includes.footer')

        </div>

        <!-- ============================================================== -->
        <!-- End of Main Content -->
        <!-- ============================================================== -->

    </div>
    <!-- END wrapper -->

    @include('dashboard.includes.scripts')

</body>

</html>
