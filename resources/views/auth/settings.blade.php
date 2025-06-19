@extends('layout.main')

@section('content')
<div class="container" style="margin-top: 50px;">
    <h2>Pengaturan Pengguna</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Form pengaturan pengguna -->
        <div class="col-md-10">
            <form action="{{ route('auth.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Gambar profil di sebelah kiri -->
                    <div class="col-md-2 text-center" style="margin-top: 15px;">
                        @if ($user->foto && Storage::disk('public')->exists($user->foto))
                            <!-- Menampilkan gambar jika ada -->
                            <img id="current-foto" src="{{ asset('storage/' . $user->foto) }}" alt="Foto Profil" class="img-thumbnail" style="width: 150px; height: 150px;">
                        @else
                            <!-- Menampilkan ikon pengguna jika tidak ada gambar -->
                            <i class="fas fa-user fa-5x"></i>
                        @endif

                    <div class="text-center" style="margin-top: 20px;">
                        <label for="foto" class="btn btn-secondary" style="font-size: 12px;">Unggah Gambar</label>
                        <input type="file" id="foto" name="foto" style="display: none;" accept="image/*">
                    </div>

                        <!-- Elemen untuk menampilkan pratinjau gambar -->
                        <img id="preview-foto" src="" alt="Pratinjau Foto" class="img-thumbnail" style="width: 150px; height: 150px; display: none; margin-top: 10px;">
                    </div>

                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password (optional)">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-eye" id="togglePassword" style="cursor: pointer;"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password">
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-eye" id="togglePasswordConfirm" style="cursor: pointer;"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="margin-top: 20px;">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    $('#foto').change(function(e){
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#preview-foto').attr('src', e.target.result).show(); // Tampilkan pratinjau gambar
            $('#current-foto').attr('src', e.target.result); // Ganti gambar profil dengan pratinjau
        }
        reader.readAsDataURL(this.files[0]); // Baca file yang dipilih
    });

    // Memicu input file ketika label diklik
    $('label[for="foto"]').click(function() {
        $('#foto').click();
    });
});
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    // Preview image logic
    $('#foto').change(function(e){
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#preview-foto').attr('src', e.target.result).show(); // Tampilkan pratinjau gambar
            $('#current-foto').attr('src', e.target.result); // Ganti gambar profil dengan pratinjau
        }
        reader.readAsDataURL(this.files[0]); // Baca file yang dipilih
    });

    // Memicu input file ketika label diklik
    $('label[for="foto"]').click(function() {
        $('#foto').click();
    });

    // Toggle password visibility
    $('#togglePassword').click(function() {
        const passwordField = $('#password');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);

        // Toggle eye icon
        $(this).toggleClass('fa-eye fa-eye-slash');
    });

    // Toggle password confirmation visibility
    $('#togglePasswordConfirm').click(function() {
        const passwordConfirmField = $('#password_confirmation');
        const type = passwordConfirmField.attr('type') === 'password' ? 'text' : 'password';
        passwordConfirmField.attr('type', type);

        // Toggle eye icon
        $(this).toggleClass('fa-eye fa-eye-slash');
    });
});
</script>

@endsection
