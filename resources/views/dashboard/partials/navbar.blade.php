<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container-fluid">
        <button class="btn d-md-none me-2" id="sidebarToggle">
            <i class="bi bi-list fs-5"></i>
        </button>
        
        <a class="navbar-brand d-md-none" href="#">
            <i class="bi bi-book-half me-2"></i>Book Library
        </a>
        
        <div class="ms-auto d-flex align-items-center">
            <!-- User profile -->
            <div class="dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="avatar me-2 bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <span class="text-white">{{ substr(Auth::user()->name ?? 'U', 0, 1) }}</span>
                    </div>
                    <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'User' }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>
