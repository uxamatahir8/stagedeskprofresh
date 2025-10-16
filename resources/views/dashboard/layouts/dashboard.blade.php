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
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        @if ($errors->any())
                            <div class="alert alert-danger mt-3">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-info mt-3">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>


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
