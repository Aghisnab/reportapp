<!-- topnav.blade.php -->
<nav class="sb-topnav navbar navbar-expand navbar-light bg-light">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="{{ route('dashboard.index') }}" style="display: flex; align-items: center;">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="logo" style="margin-right: 10px; margin-top: 18px;">
    </a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0" onsubmit="return false;">
        <div class="input-group">
            <input id="searchInput" class="form-control" type="text" placeholder="Search for..." aria-label="Search" aria-describedby="btnNavbarSearch" />
            <button class="btn btn-primary" id="btnNavbarSearch" type="button" onclick="performSearch()"><i class="fas fa-search"></i></button>
        </div>
    </form>
    <!-- Navbar User Menu-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
        <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            @if (Auth::check())
                @php
                    // Mengambil data pengguna yang sedang login
                    $user = Auth::user();
                    $profileImage = $user->foto ?? null; // Menggunakan kolom 'foto' untuk gambar profil
                @endphp

                @if ($profileImage)
                    <!-- Jika pengguna memiliki gambar profil -->
                    <img src="{{ asset('storage/' . $profileImage) }}" alt="{{ $user->name }}" class="profile-img rounded-circle">
                @else
                    <!-- Jika pengguna tidak memiliki gambar profil -->
                    <span class="icon-rounded"><i class="fas fa-user fa-fw"></i></span>
                @endif
            @else
                <!-- Jika pengguna belum login -->
                <span class="icon-rounded"><i class="fas fa-user fa-fw"></i></span>
            @endif
        </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item" href="{{ route('auth.settings') }}">Settings</a></li>
                @if (Auth::check() && in_array(Auth::user()->type, ['admin', 'staff'])) <!-- Cek apakah pengguna adalah admin, staff, atau kepala dinas -->
                <li><a class="dropdown-item" href="{{ route('activity.log') }}">Activity Log</a></li>
                @endif
                <li><hr class="dropdown-divider" /></li>
                <li>
                    <a class="dropdown-item logout-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<!-- Modal Konfirmasi Logout -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin logout?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmLogout">Logout</button>
            </div>
        </div>
    </div>
</div>

<script>
    let logoutForm;

    function createLogoutForm() {
        logoutForm = document.createElement('form');
        logoutForm.method = 'POST';
        logoutForm.action = "{{ route('logout') }}"; // Pastikan ini dalam tanda kutip ganda

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = "{{ csrf_token() }}"; // Pastikan ini dalam tanda kutip ganda
        logoutForm.appendChild(csrfToken);

        document.body.appendChild(logoutForm);
    }

    // Event listener untuk tombol logout di modal
    document.getElementById('confirmLogout').addEventListener('click', function() {
        if (!logoutForm) {
            createLogoutForm(); // Buat form jika belum ada
        }
        logoutForm.submit(); // Kirim form
    });
</script>

