<!-- Sidebar -->
<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="#">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('books.index') }}">
                    <i class="bi bi-book"></i> Books
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('authors.index') }}">
                    <i class="bi bi-person"></i> Authors
                </a>
            </li>
        </ul>
    </div>
</nav>
