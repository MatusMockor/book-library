<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'Dashboard') | Book Library</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Custom styles -->
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #4895ef;
        --success-color: #4cc9f0;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --gray-color: #6c757d;
        --sidebar-width: 250px;
    }
    
    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        font-family: 'Poppins', sans-serif;
        background-color: #f5f7fb;
    }
    
    /* Sidebar styles */
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        width: var(--sidebar-width);
        z-index: 100;
        padding: 0;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        background-color: white;
        transition: all 0.3s;
    }
    
    .sidebar-header {
        padding: 1.5rem 1rem;
        background-color: var(--primary-color);
        color: white;
    }
    
    .sidebar .nav-link {
        padding: 0.8rem 1rem;
        color: var(--dark-color);
        font-weight: 500;
        border-left: 3px solid transparent;
        transition: all 0.2s;
    }
    
    .sidebar .nav-link:hover {
        background-color: rgba(67, 97, 238, 0.1);
        border-left: 3px solid var(--primary-color);
    }
    
    .sidebar .nav-link.active {
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        border-left: 3px solid var(--primary-color);
    }
    
    .sidebar .nav-link i {
        margin-right: 0.5rem;
    }
    
    /* Navbar styles */
    .navbar {
        padding: 0.75rem 1rem;
        background-color: white !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .navbar-brand {
        font-weight: 600;
        color: var(--primary-color) !important;
    }
    
    .navbar .nav-link {
        color: var(--dark-color) !important;
    }
    
    .navbar .nav-link:hover {
        color: var(--primary-color) !important;
    }
    
    /* Main content area */
    .main-content {
        margin-left: var(--sidebar-width);
        padding: 2rem;
        flex: 1;
        transition: all 0.3s;
    }
    
    /* Cards */
    .card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .card-header {
        background-color: white;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        font-weight: 600;
    }
    
    /* Buttons */
    .btn-primary {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-primary:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
    }
    
    /* Footer */
    .footer {
        background-color: white;
        padding: 1rem 0;
        color: var(--gray-color);
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        text-align: center;
        margin-left: var(--sidebar-width);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            margin-left: calc(-1 * var(--sidebar-width));
        }
        
        .sidebar.active {
            margin-left: 0;
        }
        
        .main-content, .footer {
            margin-left: 0;
        }
        
        .main-content.active, .footer.active {
            margin-left: var(--sidebar-width);
        }
    }
    
    /* Utilities */
    .page-title {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 1.5rem;
    }
    
    /* Table styles */
    .table {
        background-color: white;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    
    .table thead th {
        background-color: rgba(67, 97, 238, 0.1);
        color: var(--primary-color);
        font-weight: 500;
        border-bottom: none;
    }
    
    /* Modal styles */
    .modal-header {
        background-color: var(--primary-color);
        color: white;
    }
    
    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }
</style>

@vite(['resources/js/app.js'])
