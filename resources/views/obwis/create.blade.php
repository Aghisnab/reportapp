@extends('layout.main')

@section('content')
<!-- Start Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 mt-5">
    <div class="breadcrumb-title pe-3">Data Objek Wisata</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item active" aria-current="page">Tambah Data Objek Wisata</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End Breadcrumb -->

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Tambah Objek Wisata</h6>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('obwis.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Objek Wisata ID:</label>
                <input type="text" name="obwis_id" class="form-control" placeholder="Objek Wisata ID" required>
                @error('obwis_id')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Objek Wisata:</label>
                <input type="text" name="nama_obwis" class="form-control" placeholder="Nama Objek Wisata" required>
                @error('nama_obwis')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Paling Dicari:</label>
                <input type="checkbox" name="increment_status" id="increment_status" value="1">
            </div>
            <div class="mb-3">
                <label class="form-label">Contact Person:</label>
                <input type="text" name="cp" class="form-control" placeholder="Contact Person (e.g., +628123456789)">
                @error('cp')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat:</label>
                <input type="text" name="alamat" class="form-control" placeholder="Alamat" required>
                @error('alamat')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Link/Koordinat Maps:</label>
                <input type="text" name="maps" class="form-control" placeholder="Link atau Koordinat Maps (Opsional)">
                @error('maps')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar (URL):</label>
                <input type="url" id="gambar" name="gambar" class="form-control" placeholder="Gambar URL" required>
                @error('gambar')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div id="imagePreviewContainer" style="display:none;" class="mb-3">
                <img id="imagePreview" src="#" alt="Image Preview" class="image-fullsize" style="max-width: 100px;">
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary ml-3">Submit</button>
            </div>
        </form>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $('#gambar').on('input', function(){
        var url = $(this).val();
        if (url) {
            $('#imagePreview').attr('src', url);
            $('#imagePreviewContainer').show();
        } else {
            $('#imagePreviewContainer').hide();
        }
    });
});
</script>

@endsection
