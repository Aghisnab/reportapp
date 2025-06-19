@extends('layout.main')

@section('title', 'Edit Objek Wisata')

@section('content')
<!-- Start Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mt-5">
    <div class="breadcrumb-title pe-3">Data Objek Wisata</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item"><a href="{{ url('obwis') }}">Objek Wisata</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Data Objek Wisata</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End Breadcrumb -->

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Edit Objek Wisata</h6>
        <div class="pull-right">
            <a href="{{ url('obwis') }}" class="btn btn-success btn-sm">
                <i class="fa fa-undo"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        @if(session('status'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('status') }}
            </div>
        @endif
        <form id="obwisForm" action="{{ route('obwis.update', $obwis->id) }}" method="POST" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
            <div class="mb-3">
                <label class="form-label">ID Obwis:</label>
                <input type="text" name="obwis_id" class="form-control" placeholder="ID Obwis" value="{{ old('obwis_id', $obwis->obwis_id) }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Nama Obwis:</label>
                <input type="text" name="nama_obwis" class="form-control" placeholder="Nama Obwis" value="{{ old('nama_obwis', $obwis->nama_obwis) }}">
                @error('nama_obwis')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Paling Dicari:</label>
                <div class="form-check">
                    <input type="checkbox" class="logCheckbox" name="increment_status" id="increment_status" class="form-check-input" value="1" {{ old('increment_status', $obwis->increment_status) ? 'checked' : '' }} onchange="document.getElementById('obwisForm').submit();">
                    <label class="form-check-label" for="increment_status">
                        Centang jika objek wisata ini adalah yang paling dicari.
                    </label>
                </div>
                <small class="form-text text-muted">Dengan mencentang ini, Anda menandai objek wisata sebagai yang paling dicari.</small>
            </div>
            <div class="mb-3">
                <label class="form-label">CP (Contact Person):</label>
                <input type="text" name="cp" class="form-control" placeholder="CP (Contact Person)" value="{{ old('cp', $obwis->cp) }}" readonly>
                @error('cp')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Alamat:</label>
                <textarea name="alamat" class="form-control" placeholder="Alamat" readonly>{{ old('alamat', $obwis->alamat) }}</textarea>
                @error('alamat')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Maps (Link):</label>
                <div class="input-group">
                    <input type="url" id="maps" name="maps" class="form-control" placeholder="Google Maps Link" value="{{ old('maps', $obwis->maps) }}" readonly>
                    <button type="button" class="btn btn-secondary" onclick="copyToClipboard()">Copy Link</button>
                </div>
                @error('maps')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                @error('gambar')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror

                <!-- Display Current Image if Exists -->
                @if($obwis->gambar)
                    <div id="currentImage" class="mt-3">
                        <label class="form-label">Gambar Saat Ini:</label>
                        <img src="{{ $obwis->gambar }}" alt="Gambar Saat Ini" class="image-fullsize">
                    </div>
                @endif
            </div>

            <!-- New Image Preview Container -->
            <div id="imagePreviewContainer" style="display:none;">
                <label class="form-label mt-3">Preview Gambar Baru:</label>
                <img id="imagePreview" src="#" alt="Image Preview" class="image-fullsize">
            </div>
        </form>
    </div>
</div>
@endsection

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

function copyToClipboard() {
    var mapsInput = document.getElementById("maps");
    mapsInput.select();
    mapsInput.setSelectionRange(0, 99999); // For mobile devices

    // Copy the text inside the input field
    document.execCommand("copy");

    // Alert the copied text (optional)
    alert("Link copied to clipboard: " + mapsInput.value);
}
</script>
