@extends('layout.main')

@section('content')
<!-- Start Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 mt-5">
    <div class="breadcrumb-title pe-3">Data Event</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item active" aria-current="page">Tambah Data Event</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End Breadcrumb -->

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Add Event</h6>
    </div>
    <div class="card-body">
        @if(session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('kegiatan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label class="form-label">Event ID:</label>
                <input type="text" name="event_id" class="form-control" placeholder="Event ID">
                @error('event_id')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Event:</label>
                <input type="text" name="nama_event" class="form-control" placeholder="Nama Event">
                @error('nama_event')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Mulai:</label>
                <input type="text" id="tanggal_mulai" name="tanggal_mulai" class="form-control" placeholder="Pilih Tanggal Mulai">
                @error('tanggal_mulai')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Selesai:</label>
                <input type="text" id="tanggal_selesai" name="tanggal_selesai" class="form-control" placeholder="Pilih Tanggal Selesai">
                @error('tanggal_selesai')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Bulan Event:</label>
                <select name="bulan_event" class="form-select">
                    <option value="" disabled selected>Pilih Bulan</option>
                    @foreach(App\Enums\MonthEnum::cases() as $month)
                        <option value="{{ $month->value }}">{{ $month->value }}</option>
                    @endforeach
                </select>
                @error('bulan_event')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat:</label>
                <input type="text" name="alamat" class="form-control" placeholder="Alamat">
                @error('alamat')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi:</label>
                <textarea name="deskripsi" class="form-control" placeholder="Deskripsi"></textarea>
                @error('deskripsi')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Artikel:</label>
                <input type="url" name="artikel" class="form-control" placeholder="Masukkan tautan artikel">
                @error('artikel')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar:</label>
                <input type="file" id="gambar" name="gambar" class="form-control">
                @error('gambar')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div id="imagePreviewContainer" style="display:none;">
            <img id="imagePreview" src="#" alt="Image Preview" class="image-fullsize">
        </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary ml-3">Submit</button>
            </div>
        </form>
    </div>
</div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    $('#gambar').change(function(){
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#imagePreviewContainer').show();
            }
            reader.readAsDataURL(input.files[0]);
        }
    });
});
</script>
