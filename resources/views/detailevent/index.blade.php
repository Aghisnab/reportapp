@extends('layout.main')

@section('title', 'Detail Event')

@section('content')
<!-- Start Breadcrumb -->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 mt-5">
    <div class="breadcrumb-title pe-3">Detail Event</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0 align-items-center">
                <li class="breadcrumb-item"><a href="{{ url('kegiatan') }}">Events</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Event: {{ $event->nama_event }}</li>
            </ol>
        </nav>
    </div>
</div>
<!-- End Breadcrumb -->

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2 class="mb-0 text-center flex-grow-1">Detail Event: {{ $event->nama_event }}</h2>
        <div class="pull-left">
            <a href="{{ route('events.edit', $event->id) }}" class="btn btn-success btn-sm">
                <i class="fa fa-undo"></i> Back
            </a>
            <a href="{{ route('events.detailevent.create', $event->id) }}" class="btn btn-primary btn-sm">
                <i class="fa fa-plus"></i> Tambah Detail
            </a>
        </div>
    </div>
    <div class="card-body table-responsive">
        @if(session('success'))
            <div class="alert alert-success mb-1 mt-1">
                {{ session('success') }}
            </div>
        @endif

        <table id="myTable" class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-start">Hari Ke-</th>
                    <th class="text-start">Tanggal</th>
                    <th>Rangkaian Acara</th>
                    <th class="text-start col-dokumentasi">Dokumentasi Gambar</th>
                    <th class="text-start col-dokumentasi">Dokumentasi Video</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($details as $detail)
                    <tr>
                        <td class="text-center">{{ $detail->hari_ke }}</td>
                        <td class="text-center">{{ $detail->tanggal }}</td>
                        <td>
                            @php
                                $fullDescription = $detail->rangkaian_acara;
                                $words = explode(' ', $fullDescription);
                                $shortDescription = implode(' ', array_slice($words, 0, 15));
                            @endphp
                            {!! nl2br(e($shortDescription)) !!}
                            @if (count($words) > 15)
                                ... <a href="{{ url('plan/' . $detail->id . '/edit') }}">Lihat Selengkapnya</a>
                            @endif
                        </td>
                        <td class="text-center col-dokumentasi">
                            @if($detail->dokumentasi1)
                                @php
                                    $urls = json_decode($detail->dokumentasi1);
                                @endphp
                                @if(is_array($urls))
                                    @foreach($urls as $url)
                                        <a href="{{ $url }}" target="_blank">lihat dokumentasi</a><br>
                                    @endforeach
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center col-dokumentasi">
                            @if($detail->dokumentasi2)
                                @php
                                    $urls = json_decode($detail->dokumentasi2);
                                @endphp
                                @if(is_array($urls))
                                    @foreach($urls as $url)
                                        <a href="{{ $url }}" target="_blank">lihat dokumentasi</a><br>
                                    @endforeach
                                @else
                                    -
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('events.detailevent.edit', [$event->id, $detail->id]) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('events.detailevent.destroy', [$event->id, $detail->id]) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus detail ini?');">
                                    <i class="fa fa-trash"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada detail event yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@endsection
