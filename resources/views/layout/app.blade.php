<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <title>@yield('title', 'Default Title')</title>
    
    <!-- Include Bootstrap CSS, Toastr, FullCalendar, and Custom CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    @stack('css') <!-- Custom CSS pushed from specific pages -->
</head>
<body class="sb-nav-fixed">
    <!-- Top Navigation Bar -->
    @include('layout.topnav')
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <!-- Sidebar -->
            @include('layout.sidebar')
        </div>
        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main style="margin-top: 20px;">
                <div class="container-fluid px-4">
                    @yield('content')

                    @yield('modals')
                </div>
            </main>
        </div>
    </div>
    <!-- Footer -->
    @include('layout.footer')

    <!-- Include jQuery, Bootstrap JS, FullCalendar, Toastr, and Custom JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- Keep only this jQuery -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> <!-- Bootstrap 4 JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    @stack('scripts') <!-- Custom scripts pushed from specific pages -->
</body>
</html>
