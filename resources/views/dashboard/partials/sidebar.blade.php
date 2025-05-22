<!-- Sidebar -->
<nav id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <h5 class="mb-0 d-flex align-items-center">
            <i class="bi bi-book-half me-2"></i>
            Book Library
        </h5>
    </div>
    
    <div class="pt-3">
        <div class="px-3 mb-3">
            <small class="text-uppercase text-muted fw-bold">Main</small>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            
            <div class="px-3 mb-2 mt-4">
                <small class="text-uppercase text-muted fw-bold">Library</small>
            </div>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">
                    <i class="bi bi-book"></i> Books
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('authors.*') ? 'active' : '' }}" href="{{ route('authors.index') }}">
                    <i class="bi bi-person"></i> Authors
                </a>
            </li>
        </ul>
    </div>
</nav>
