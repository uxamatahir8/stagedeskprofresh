<!DOCTYPE html>
<html lang="en" data-skin="saas" data-bs-theme="light" data-menu-color="light" data-topbar-color="light"
    data-layout-position="fixed" data-sidenav-size="default" data-sidenav-user="true">
@include('dashboard.includes.head')


<style>
    .icon-wait {
        position: fixed;
        width: 100%;
        height: 100%;
        background-color: #fff;
        z-index: 999999;
        opacity: 0.85;
        transition-duration: 2s;
    }

    .icon-wait .icon-wrapper {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .icon-wait .icon-wrapper .icon-main {
        min-height: 85px;
        height: 85px;
        overflow: auto;
        margin: auto;
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        opacity: 0.8;
        text-align: center;
        color: #43054E;
    }
</style>

<body>
    <!-- Begin page -->
    <div class="wrapper">

        <div style="display:none;" id="icon-wait" class="icon-wait">
            <div class="icon-wrapper">
                <div class="icon-main">
                    <div class="spinner-border" style="width: 3rem; height: 3rem;" style="z-index:9999999999;"
                        role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>

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
