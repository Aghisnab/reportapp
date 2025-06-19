@extends('layout.main')

@section('title', 'Edit Plan')

@section('content')
<!-- Start Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 mt-5">
    <div class="breadcrumb-title pe-3">Data Rencana Event</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item"><a href="{{ url('plan') }}">Rencana Event</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End Breadcrumb -->

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Edit Data</h6>
        <div class="pull-right">
            <a href="{{ url('plan') }}" class="btn btn-success btn-sm">
                <i class="fa fa-undo"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('plan.update', $plan->id) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <div class="mb-3">
                <label class="form-label">Event ID:</label>
                <input type="text" name="event_id" class="form-control" placeholder="Plan ID" value="{{ old('event_id', $plan->event_id) }}">
                @error('event_id')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Event:</label>
                <input type="text" name="nama_event" class="form-control" placeholder="Nama Plan" value="{{ old('nama_event', $plan->nama_event) }}">
                @error('nama_event')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Mulai:</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" placeholder="Pilih Tanggal Mulai" value="{{ old('tanggal_mulai', $plan->tanggal_mulai) }}">
                @error('tanggal_mulai')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Selesai:</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" placeholder="Pilih Tanggal Selesai" value="{{ old('tanggal_selesai', $plan->tanggal_selesai) }}">
                @error('tanggal_selesai')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Bulan Event:</label>
                <select name="bulan_event" class="form-select">
                    <option value="" disabled>Pilih Bulan</option>
                    @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $month)
                        <option value="{{ $month }}" {{ (old('bulan_event', $plan->bulan_event) === $month) ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
                @error('bulan_event')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat:</label>
                <input type="text" name="alamat" class="form-control" placeholder="Alamat" value="{{ old('alamat', $plan->alamat) }}">
                @error('alamat')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi:</label>
                <textarea name="deskripsi" class="form-control" placeholder="Deskripsi" style="height:300px;">{{ old('deskripsi', $plan->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Gambar:</label>
                <input type="file" id="gambar" name="gambar" class="form-control">
                @error('gambar')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror

                <!-- Display Current Image if Exists -->
                @if($plan->gambar)
                    <div id="currentImage" class="mt-3">
                        <label class="form-label">Gambar Saat Ini:</label>
                        <img src="{{ asset('storage/' . $plan->gambar) }}" alt="Current Image" class="image-fullsize">
                    </div>
                @endif
            </div>

            <!-- New Image Preview Container -->
            <div id="imagePreviewContainer" style="display:none;">
                <label class="form-label mt-3">Preview Gambar Baru:</label>
                <img id="imagePreview" src="#" alt="Image Preview" class="image-fullsize">
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Update</button>
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
