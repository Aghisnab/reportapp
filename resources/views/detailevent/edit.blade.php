@extends('layout.main')

@section('title', 'Edit Detail Event')

@section('content')
<!-- Start Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 mt-5">
    <div class="breadcrumb-title pe-3">Edit Detail Event</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item"><a href="{{ url('kegiatan') }}">Events</a></li>
                <li class="breadcrumb-item"><a href="{{ route('events.detailevent.index', $detail->event_id) }}">Detail Event</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Detail</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End Breadcrumb -->

<div class="card">
    <div class="card-header">
        <h6 class="mb-2 text-center">Edit Detail Event</h6>
        <div class="pull-left">
            <a href="{{ route('events.detailevent.index', $detail->event_id) }}" class="btn btn-success btn-sm">
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

        <form action="{{ route('events.detailevent.update', [$detail->event_id, $detail->id]) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="mb-3">
                <label class="form-label">Hari Ke-:</label>
                <input type="number" name="hari_ke" class="form-control" placeholder="Hari Ke-" value="{{ old('hari_ke', $detail->hari_ke) }}" required>
                @error('hari_ke')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Tanggal:</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $detail->tanggal) }}" required>
                @error('tanggal')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Rangkaian Acara:</label>
                <textarea name="rangkaian_acara" class="form-control" placeholder="Rangkaian Acara" required style="height:20px;">{{ old('rangkaian_acara', $detail->rangkaian_acara) }}</textarea>
                @error('rangkaian_acara')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Dokumentasi Gambar (URL, pisahkan dengan koma):</label>
                @php
                    $dokumentasi1Array = json_decode($detail->dokumentasi1, true);
                @endphp
                <input type="text" name="dokumentasi1" id="dokumentasi1Input" class="form-control" placeholder="Masukkan URL gambar" value="{{ old('dokumentasi1', $dokumentasi1Array ? implode(',', $dokumentasi1Array) : '') }}">
                @error('dokumentasi1')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror

                <!-- Display Current Images if Exists -->
                @if($dokumentasi1Array)
                    <div id="currentImages" class="mt-3">
                        <label class="form-label">Pratinjau Dokumentasi Gambar:</label>
                        @foreach($dokumentasi1Array as $url)
                            <img src="{{ $url }}" alt="Dokumentasi Preview" style="max-width: 100%; height: auto; margin-bottom: 10px;">
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <label class="form-label">Dokumentasi Video (URL, pisahkan dengan koma):</label>
                @php
                    $dokumentasi2Array = json_decode($detail->dokumentasi2, true);
                @endphp
                <input type="text" name="dokumentasi2" id="dokumentasi2Input" class="form-control" placeholder="Masukkan URL video" value="{{ old('dokumentasi2', $dokumentasi2Array ? implode(',', $dokumentasi2Array) : '') }}">
                @error('dokumentasi2')
                    <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                @enderror

                <!-- Display Current Videos if Exists -->
                @if($dokumentasi2Array)
                    <div id="currentVideos" class="mt-3">
                        <label class="form-label">Pratinjau Dokumentasi Video:</label>
                        @foreach($dokumentasi2Array as $url)
                            <video src="{{ $url }}" controls style="max-width: 100%; height: auto; margin-bottom: 10px;"></video>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Update Detail Event</button>
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
