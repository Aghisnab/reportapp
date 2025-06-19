<!-- sidebar.blade.php -->
<nav class="sb-sidenav accordion sb-sidenav-light" id="sidenavAccordion">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading"></div>
            <a class="nav-link" href="{{ route('dashboard.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
            @if (Auth::check() && Auth::user()->type === 'admin') <!-- Cek apakah pengguna adalah admin -->
                <a class="nav-link" href="{{ route('users.index') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                    Pengguna
                </a>
            @endif
            <div class="sb-sidenav-menu-heading">Menu</div>
            @if (Auth::check() && in_array(Auth::user()->type, ['admin', 'staff'])) <!-- Cek apakah pengguna adalah admin, staff, atau kepala dinas -->
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false">
                    <div class="sb-nav-link-icon"><i class="fas fa-clipboard"></i></div>
                    Kegiatan
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <!-- Nested Pages -->
                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav" id="sidenavAccordionPages">
                        <a class="nav-link" href="{{ route('kegiatan.index') }}">Event</a>
                        <a class="nav-link" href="{{ route('plan.index') }}">Rencana Event</a>
                    </nav>
                </div>
            <a class="nav-link" href="calendar">
                <div class="sb-nav-link-icon">
                    <i class="fas fa-calendar"></i>
                </div>Kalender Event
            </a>
            @endif
            <a class="nav-link" href="{{ route('obwis.index') }}">
                <div class="sb-nav-link-icon">
                    <i class="fas fa-map"></i>
                </div>Objek Wisata
            </a>
        @if (Auth::check() && in_array(Auth::user()->type, ['admin', 'staff'])) <!-- Cek apakah pengguna adalah admin, staff, atau kepala dinas -->
            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav">
                    <a class="nav-link" href="layout-static.html">Static Navigation</a>
                    <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                </nav>
            </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false">
                    <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                    Laporan
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <!-- Nested Pages -->
                <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav" id="sidenavAccordionPages">
                        <a class="nav-link" href="{{ route('kegiatan.report') }}">Laporan Event</a>
                        <a class="nav-link" href="{{ route('obwis.report') }}">Laporan Objek Wisata</a>
                    </nav>
                </div>
        @endif
        </div>
    </div>
    <div class="sb-sidenav-footer">
    <div class="sb-sidenav-footer">
    <div class="small">Logged in as:</div>
        @if (Auth::check())
            {{ Auth::user()->name }} <!-- Menampilkan nama pengguna yang sedang login -->
        @else
            Guest <!-- Atau Anda bisa menampilkan pesan lain jika pengguna tidak terautentikasi -->
        @endif
    </div>
</nav>
