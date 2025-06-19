<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Dashboard - SB Admin')</title>
    <!-- Stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.datatables.net/v/dt/dt-2.1.8/datatables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <style>
        /* Custom CSS to control the image size */
        .image-container img {
            max-width: 100%;        /* Ensure the image doesn't exceed the width of its container */
            height: auto;           /* Maintain the aspect ratio */
            border-radius: 10px;    /* Optional: Add rounded corners to the image */
        }
    </style>
</head>

<body class="sb-nav-fixed">

    <!-- Top Navigation -->
    @include('layout.topnav')

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            @include('layout.sidebar')
        </div>

        <!-- Content -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4" style="margin-top:40px";>
                    <!-- Dashboard Main Content -->
                    <div class="welcome-banner text-center mb-4" style="background-color:rgba(245, 245, 245, 0.69); padding: 20px; border-radius: 10px;">
                        <h3 class="mt-4" style="font-size: 1.75rem; color: #007bff;">
                            <i class="fas fa-user-circle"></i> Selamat Datang,
                            @if (Auth::check())
                                @if (Auth::user()->type === 'admin') <!-- Cek apakah pengguna adalah kepala dinas -->
                                    Admin
                                @else
                                    {{ Auth::user()->name }} <!-- Menampilkan nama pengguna yang tidak kepala dinas -->
                                @endif
                            @else
                                Guest <!-- Menampilkan pesan jika pengguna tidak terautentikasi -->
                            @endif
                        </h3>
                        <p class="mt-2" style="font-size: 1.50rem; color: #6c757d;">
                            Sistem Laporan Event dan Objek Wisata
                        </p>
                    </div>

                    <!-- Cards Row -->
                    @if(Auth::user()->type != 'user')
                    <div class="row">
                        <!-- Total Event -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body">Total Event: {{ $totalEvents }}</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="{{ route('kegiatan.index') }}">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <!-- Total Event per bulan ini -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">Total Rencana Event: {{ $totalPlans }}</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="{{ route('kegiatan.index') }}">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <!-- Total Event per tahun ini -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">Total Event per tahun ini: {{ $totalEventsThisYear }}</div> <!-- Ganti dengan data yang sesuai -->
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="{{ route('kegiatan.index') }}">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <!-- Total Obwis -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-danger text-white mb-4">
                                <div class="card-body">Total Obwis: {{ $totalObwis }}</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" href="{{ route('obwis.index') }}">View Details</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Second Row of Cards -->
                    <div class="row mt-4">
                        <!-- First Card for Latest Events -->
                        <div class="col-lg-12 mb-lg-0 mb-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="d-flex flex-column h-100">
                                                <p class="mb-1 pt-2 text-bold">Event Terbaru</p>
                                                @if($latestEvent)
                                                    <h3 class="font-weight-bolder">{{ $latestEvent->nama_event }}</h3>
                                                    <p class="mb-5">
                                                        Start Date: {{ \Carbon\Carbon::parse($latestEvent->tanggal_mulai)->format('d-m-Y') }} <br>
                                                        End Date: {{ \Carbon\Carbon::parse($latestEvent->tanggal_selesai)->format('d-m-Y') }} <br>
                                                        Address: {{ $latestEvent->alamat }}
                                                    </p>
                                                    <a class="text-body text-sm font-weight-bold mb-0 icon-move-right mt-auto" href="{{ route('events.edit', $latestEvent->id) }}">
                                                        Read More
                                                        <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                                                    </a>
                                                @else
                                                    <h3 class="font-weight-bolder">Tidak ada info</h3>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-4 ms-auto text-center mt-5 mt-lg-0">
                                            <div class="border-radius-lg h-100">
                                                <div class="position-relative d-flex align-items-center justify-content-center h-100">
                                                    @if($latestEvent && $latestEvent->gambar)
                                                        <img class="img-fluid position-relative z-index-2 pt-4" style="max-width: 200px;" src="{{ asset('storage/' . $latestEvent->gambar) }}" alt="{{ $latestEvent->nama_event }}">
                                                    @else
                                                        <!-- Ganti dengan ikon tidak ada gambar -->
                                                        <p class="mt-2">Tidak ada gambar tersedia</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Second Card for Latest Obwis -->
                        <div class="col-lg-12 mb-lg-0 mb-4 mt-4">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="d-flex flex-column h-100">
                                                <p class="mb-1 pt-2 text-bold">Objek Wisata Terbaru</p>
                                                <h3 class="font-weight-bolder">{{ $latestObwis->nama_obwis }}</h3>
                                                <p class="mb-5">
                                                    Disukai: {{ number_format($latestObwis->status) }} <br>
                                                    Address: {{ $latestObwis->alamat }} <br>
                                                    Maps:
                                                    @if($latestObwis->maps)
                                                        <a href="{{ $latestObwis->maps }}" target="_blank" class="text-body text-sm font-weight-bold mb-0 icon-move-right mt-auto">
                                                            View on Map
                                                            <i class="fas fa-map-marker-alt text-sm ms-1" aria-hidden="true"></i>
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Map not available</span>
                                                    @endif
                                                </p>
                                                <a class="text-body text-sm font-weight-bold mb-0 icon-move-right mt-auto" href="{{ route('obwis.edit', $latestObwis->id) }}">
                                                    Read More
                                                    <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 ms-auto text-center mt-5 mt-lg-0">
                                            <div class="border-radius-lg h-100">
                                                <div class="position-relative d-flex align-items-center justify-content-center h-100 image-container">
                                                    @if($latestObwis)
                                                        <img class="img-fluid position-relative z-index-2 pt-4" style="max-width: 200px;" src="{{ $latestObwis->gambar }}" alt="{{ $latestObwis->nama_obwis }}">
                                                    @else
                                                        <!-- Ganti dengan ikon tidak ada gambar -->
                                                        <p class="mt-2">Tidak ada gambar tersedia</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="footer pt-3">
                <div class="container-fluid">
                    <div class="row align-items-center justify-content-lg-between">
                        <div class="col-lg-6 mb-lg-0 mb-4">
                            <div class="copyright text-center text-sm text-muted text-lg-start">
                                ©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script>
                                , Dashboard by
                                <a href="https://github.com/Aghisnab" class="font-weight-bold" target="_blank">Aghisnab</a>.
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#tanggal_mulai", { dateFormat: "Y-m-d" });
            flatpickr("#tanggal_selesai", { dateFormat: "Y-m-d" });
        });
    </script>

    <!-- DataTables -->
    <script>
        $(document).ready(function () {
            $('#myTable').DataTable();
        });
    </script>

    <!-- FullCalendar -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: '/event/calendar-data',
                eventClick: function(info) {
                    $('#noteModal').modal('show');
                    $('#event_id').val(info.event.id);
                }
            });
            calendar.render();
        });
    </script>

    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById("chart-bars").getContext("2d");
        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Sales",
                    backgroundColor: "#fff",
                    data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
                }]
            }
        });

        var ctx2 = document.getElementById("chart-line").getContext("2d");
        new Chart(ctx2, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Mobile apps",
                    borderColor: "#cb0c9f",
                    data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                    fill: true,
                }]
            }
        });
    </script>
</body>
</html>
