<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@if(session('api_token'))
    <script>
        // Store the API token in localStorage
        localStorage.setItem('api_token', '{{ session('api_token') }}');
    </script>
@endif

@if(session('remove_token'))
    <script>
        // Remove the API token from localStorage when user logs out
        localStorage.removeItem('api_token');
    </script>
@endif
