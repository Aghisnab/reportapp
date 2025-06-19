@extends('layout.main')

@section('title', 'Tambah Detail Event')

@section('content')
<!-- Start Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 mt-5">
    <div class="breadcrumb-title pe-3">Tambah Detail Event</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item"><a href="{{ url('kegiatan') }}">Events</a></li>
                <li class="breadcrumb-item"><a href="{{ route('events.detailevent.index', $event->id) }}">Detail Event: {{ $event->nama_event }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Detail</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End Breadcrumb -->

<div class="card">
    <div class="card-header">
        <h6 class="mb-0">Tambah Detail Event untuk: {{ $event->nama_event }}</h6>
        <div class="pull-right">
            <a href="{{ route('events.detailevent.index', $event->id) }}" class="btn btn-success btn-sm">
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

        <form action="{{ route('events.detailevent.store', $event->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Hari Ke-:</label>
                <input type="number" name="hari_ke" class="form-control" placeholder="Hari Ke-" value="{{ old('hari_ke') }}" required>
                @error('hari_ke')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal:</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
                @error('tanggal')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Rangkaian Acara:</label>
                <textarea name="rangkaian_acara" class="form-control" placeholder="Rangkaian Acara" required>{{ old('rangkaian_acara') }}</textarea>
                @error('rangkaian_acara')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Dokumentasi Gambar (URL, pisahkan dengan koma):</label>
                <input type="text" name="dokumentasi1" id="dokumentasi1Input" class="form-control" placeholder="Masukkan URL gambar">
                @error('dokumentasi1')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror

                <!-- Image Preview -->
                <div id="currentImages" class="mt-3">
                    <label class="form-label">Pratinjau Dokumentasi:</label>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Dokumentasi Video (URL, pisahkan dengan koma):</label>
                <input type="text" name="dokumentasi2" id="dokumentasi2Input" class="form-control" placeholder="Masukkan URL Video">
                @error('dokumentasi2')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror

                <!-- Video Preview -->
                <div id="currentVideos" class="mt-3">
                    <label class="form-label">Pratinjau Dokumentasi Video:</label>
                </div>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Simpan Detail Event</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('dokumentasi1Input').addEventListener('input', function() {
    var input = this.value;
    var previewsContainer = document.getElementById('currentImages');
    previewsContainer.innerHTML = '';

    if (input) {
        var urls = input.split(',');
        urls.forEach(function(url) {
            var img = document.createElement('img');
            img.src = url.trim();
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
            img.style.marginBottom = '10px';
            previewsContainer.appendChild(img);
        });
    }
});

document.getElementById('dokumentasi2Input').addEventListener('input', function() {
    var input = this.value;
    var previewsContainer = document.getElementById('currentVideos');
    previewsContainer.innerHTML = '';

    if (input) {
        var urls = input.split(',');
        urls.forEach(function(url) {
            url = url.trim();
            var extension = url.split('.').pop().toLowerCase();
            if (['mp4', 'webm', 'ogg'].includes(extension)) {
                // If the URL is a video
                var video = document.createElement('video');
                video.src = url;
                video.controls = true;
                video.style.maxWidth = '100%';
                video.style.height = 'auto';
                video.style.marginBottom = '10px';
                previewsContainer.appendChild(video);
            } else {
                // If the URL is neither an image nor a video
                var errorMsg = document.createElement('p');
                errorMsg.textContent = 'Unsupported file type: ' + url;
                errorMsg.style.color = 'red';
                previewsContainer.appendChild(errorMsg);
            }
        });
    }
});
</script>

@endsection
