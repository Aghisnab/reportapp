@extends('layout.main')

@section('title', 'Edit Event')

@section('content')
<!-- Start Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 mt-5">
    <div class="breadcrumb-title pe-3">Data Event</div>
    @if(Auth::user()->type != 'user')
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item"><a href="{{ url('kegiatan') }}">Events</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Data Event</li>
            </ol>
        </nav>
    </div>
    @endif
</div>
<!-- End Breadcrumb -->

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Edit Event</h6>
        <div class="pull-right">
            @if (Auth::check() && Auth::user()->type == 'user')
                <a href="{{ route('dashboard.index') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-undo"></i> Kembali
                </a>
            @else
                <a href="{{ url('kegiatan') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-undo"></i> Kembali
                </a>
            @endif

            @if (Auth::check() && in_array(Auth::user()->type, ['admin', 'staff']))
                <a href="{{ route('events.detailevent.index', $events->id) }}" class="btn btn-info btn-sm">
                    <i class="fa fa-list"></i> Detail
                </a>
            @endif
        </div>
    </div>
    <div class="card-body">
        @if(session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('kegiatan.update', $events->id) }}" method="POST" enctype="multipart/form-data">
            @method('PATCH')
            @csrf
            <div class="mb-3">
                <label class="form-label">Event ID:</label>
                <input type="text" name="event_id" class="form-control" placeholder="Event ID" value="{{ old('event_id', $events->event_id) }}">
                @error('event_id')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Event:</label>
                <input type="text" name="nama_event" class="form-control" placeholder="Nama Event" value="{{ old('nama_event', $events->nama_event) }}">
                @error('nama_event')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Mulai:</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" placeholder="Pilih Tanggal Mulai" value="{{ old('tanggal_mulai', $events->tanggal_mulai) }}">
                @error('tanggal_mulai')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal Selesai:</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" placeholder="Pilih Tanggal Selesai" value="{{ old('tanggal_selesai', $events->tanggal_selesai) }}">
                @error('tanggal_selesai')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Bulan Event:</label>
                <select name="bulan_event" class="form-select">
                    <option value="" disabled>Pilih Bulan</option>
                    @foreach(\App\Enums\MonthEnum::cases() as $month)
                        <option value="{{ $month->getName() }}" {{ old('bulan_event', $events->bulan_event) == $month->getName() ? 'selected' : '' }}>
                            {{ $month->getName() }}
                        </option>
                    @endforeach
                </select>
                @error('bulan_event')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat:</label>
                <input type="text" name="alamat" class="form-control" placeholder="Alamat" value="{{ old('alamat', $events->alamat) }}">
                @error('alamat')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi:</label>
                <textarea name="deskripsi" class="form-control" placeholder="Deskripsi" style="height:170px;">{{ old('deskripsi', $events->deskripsi) }}</textarea>
                @error('deskripsi')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Artikel:</label>
                <div class="input-group">
                    <input type="url" id="artikel" name="artikel" class="form-control" placeholder="Masukkan tautan artikel" value="{{ old('artikel', $events->artikel) }}">
                    <button type="button" class="btn btn-secondary" onclick="copyToClipboard()">Copy Link</button>
                </div>
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

                <!-- Display Current Image if Exists -->
                @if($events->gambar)
                    <div id="currentImage" class="mt-3">
                        <label class="form-label">Gambar Saat Ini:</label>
                        <img src="{{ asset('storage/' . $events->gambar) }}" alt="Current Image" class="image-fullsize">
                    </div>
                @endif
            </div>

            <!-- New Image Preview Container -->
            <div id="imagePreviewContainer" style="display:none;">
                <label class="form-label mt-3">Preview Gambar Baru:</label>
                <img id="imagePreview" src="#" alt="Image Preview" class="image-fullsize">
            </div>

            @if (Auth::check() && in_array(Auth::user()->type, ['admin', 'staff']))
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            @endif
        </form>
    </div>
</div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    console.log("Document is ready");

    // Pastikan elemen ada sebelum mengaksesnya
    if ($('#gambar').length) {
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
    } else {
        console.error("Element with ID 'gambar' not found.");
    }
});

function copyToClipboard() {
    var artikelInput = document.getElementById("artikel");
    artikelInput.select();
    artikelInput.setSelectionRange(0, 99999); // For mobile devices

    // Copy the text inside the input field
    document.execCommand("copy");

    // Alert the copied text (optional)
    alert("Link copied to clipboard: " + artikelInput.value);
}
</script>
