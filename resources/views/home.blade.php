<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <!-- Include meta tags, CSS, etc -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Home</title>
    <link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icofont.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slicknav.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl-carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('css/normalize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom-style.css') }}"> <!-- Link to custom CSS -->
</head>
<body>
    <!-- Include Preloader, Header, etc -->
    @include('partials.preloader')
    @include('partials.header')

    <!-- Main Content -->
    <main class="container-fluid px-4">
        <div class="row dashboard-row">
            <div class="overlay"></div>
            <div class="content col-12">
                <h1>Sistem Informasi Pengelolaan Event dan Objek Wisata</h1><br>
                <h3>Dinas Kebudayaan Dan Pariwisata Kabupaten Cirebon</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card text-white bg-primary shadow h-100 py-2" style="border-radius: 10px;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Event</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $totalEvents }}</div>
                        </div>
                        <div class="icon-circle bg-white text-primary">
                            <i class="fas fa-calendar-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card text-white bg-success shadow h-100 py-2" style="border-radius: 10px;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Rencana Event</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $totalPlans }}</div>
                        </div>
                        <div class="icon-circle bg-white text-success">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card text-white bg-warning shadow h-100 py-2" style="border-radius: 10px;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Event per tahun ini</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $totalEventsThisYear }}</div>
                        </div>
                        <div class="icon-circle bg-white text-warning">
                            <i class="fas fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card text-white bg-danger shadow h-100 py-2" style="border-radius: 10px;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-xs font-weight-bold text-uppercase mb-1">Total Obwis</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $totalObwis }}</div>
                        </div>
                        <div class="icon-circle bg-white text-danger">
                            <i class="fas fa-map-marker-alt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-lg-12 mb-lg-0 mb-4 mt-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="d-flex flex-column h-100">
                                    <p class="mb-1 pt-2 text-bold">Objek Wisata Terbaru</p>
                                    @if($latestObwis)
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
                                    @else
                                        <h3 class="font-weight-bolder">Tidak ada info</h3>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-4 ms-auto text-center mt-5 mt-lg-0">
                                <div class="border-radius-lg h-100">
                                    <div class="position-relative d-flex align-items-center justify-content-center h-100 image-container">
                                        @if($latestObwis && $latestObwis->gambar)
                                            <img class="img-fluid position-relative z-index-2 pt-4" style="max-width: 200px;" src="{{ $latestObwis->gambar }}" alt="{{ $latestObwis->nama_obwis }}">
                                        @else
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

        <div class="row mt-4">
            <!-- Tabel Event
            <div class="col-lg-12 mb-lg-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <h4 class="card-title">Event List</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Event</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Alamat</th>
                                    <th>Gambar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $index => $event)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $event->nama_event }}</td>
                                        <td>{{ \Carbon\Carbon::parse($event->tanggal_mulai)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($event->tanggal_selesai)->format('d-m-Y') }}</td>
                                        <td>{{ $event->alamat }}</td>
                                        <td>
                                            @if($event->gambar)
                                                <img src="{{ asset('storage/' . $event->gambar) }}" alt="{{ $event->nama_event }}" style="max-width: 100px;">
                                            @else
                                                <p>Tidak ada gambar</p>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            Tabel Event -->

            <!-- Tabel Obwis -->
            <div class="col-lg-12 mb-lg-0 mb-4 mt-4">
                <div class="card">
                    <div class="card-body p-3">
                        <h4 class="card-title">Objek Wisata List</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Obwis</th>
                                    <th>Alamat</th>
                                    <th>Disukai</th>
                                    <th>Gambar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($obwis as $index => $ow)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $ow->nama_obwis }}</td>
                                        <td>{{ $ow->alamat }}</td>
                                        <td>{{ $ow->status }}</td>
                                        <td>
                                            @if($ow->gambar)
                                                <img src="{{ $ow->gambar }}" alt="{{ $ow->nama_obwis }}" style="max-width: 100px;">
                                            @else
                                                <p>Tidak ada gambar</p>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tabel Plan -->
            <div class="col-lg-12 mb-lg-0 mb-4 mt-4">
                <div class="card">
                    <div class="card-body p-3">
                        <h4 class="card-title">Rencana Event List</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Rencana Event</th>
                                    <th>Tanggal</th>
                                    <th>Bulan</th>
                                    <th>Alamat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($plans as $index => $plan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $plan->nama_event }}</td>
                                        <td>{{ $plan->tanggal_mulai }}</td>
                                        <td>{{ $plan->bulan_event }}</td>
                                        <td>{{ $plan->alamat }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Include Footer -->
    @include('partials.footer')

    <!-- Include JS Files -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery-migrate-3.0.0.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/easing.js') }}"></script>
    <script src="{{ asset('js/colors.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/jquery.nav.js') }}"></script>
    <script src="{{ asset('js/slicknav.min.js') }}"></script>
    <script src="{{ asset('js/jquery.scrollUp.min.js') }}"></script>
    <script src="{{ asset('js/niceselect.js') }}"></script>
    <script src="{{ asset('js/tilt.jquery.min.js') }}"></script>
    <script src="{{ asset('js/owl-carousel.js') }}"></script>
    <script src="{{ asset('js/jquery.counterup.min.js') }}"></script>
    <script src="{{ asset('js/steller.js') }}"></script>
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
