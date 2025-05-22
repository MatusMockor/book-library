<!DOCTYPE html>
<html lang="en">
<head>
    @include('dashboard.partials.head')
</head>
<body>
    <div class="d-flex" id="app">
        <!-- Sidebar -->
        @include('dashboard.partials.sidebar')
        
        <!-- Main content wrapper -->
        <div class="d-flex flex-column flex-grow-1">
            <!-- Top navbar -->
            @include('dashboard.partials.navbar')
            
            <!-- Main content -->
            <div class="main-content">
                <!-- Page header -->
                @hasSection('header')
                    <div class="mb-4">
                        <h1 class="page-title">@yield('header')</h1>
                    </div>
                @endif
                
                <!-- Alert messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                <!-- Page content -->
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    @include('dashboard.partials.scripts')
    
    <script>
        // Toggle sidebar on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    document.getElementById('sidebar').classList.toggle('active');
                    document.querySelector('.main-content').classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html>
