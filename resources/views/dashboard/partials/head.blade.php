<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title') / Book Library</title>

<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
<!-- Custom styles -->
<style>
    body {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .sidebar {
        min-height: calc(100vh - 56px);
        box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
    }
    .sidebar .nav-link {
        color: #333;
    }
    .sidebar .nav-link.active {
        color: #0d6efd;
    }
    main {
        flex: 1;
    }
    .navbar-brand {
        padding-top: .75rem;
        padding-bottom: .75rem;
    }
    .footer {
        padding: 1.5rem 0;
        color: #6c757d;
        border-top: 1px solid #e9ecef;
    }
</style>

@vite(['resources/js/app.js'])
