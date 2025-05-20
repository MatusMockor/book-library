<!DOCTYPE html>
<html lang="en">
<head>
    @include('dashboard.partials.head')
</head>
<body>
    @include('dashboard.partials.navbar')

    <div class="container-fluid">
        <div class="row">
            @include('dashboard.partials.sidebar')

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <!-- Page content -->
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @include('dashboard.partials.footer')

    @include('dashboard.partials.scripts')
</body>
</html>
