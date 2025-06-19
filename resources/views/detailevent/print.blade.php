@extends('layout.main')

@section('title', 'Print Detail Event')

@section('content')
<div class="container mt-4">
    <h2 class="text-center">Detail Event: {{ $event->nama_event }}</h2>

    <table id="myTable" class="table table-bordered">
        <thead>
            <tr>
                <th class="text-start">Hari Ke-</th>
                <th class="text-start">Tanggal</th>
                <th>Rangkaian Acara</th>
                <th>Dokumentasi Gambar</th>
                <th>Dokumentasi Video</th>
            </tr>
        </thead>
        <tbody>
            @forelse($details as $detail)
                <tr>
                    <td class="text-center">{{ $detail->hari_ke }}</td>
                    <td class="text-center">{{ $detail->tanggal }}</td>
                    <td>{!! nl2br(e($detail->rangkaian_acara)) !!}</td>
                    <td class="text-center">
                        @if($detail->dokumentasi1)
                            @php
                                $urls = json_decode($detail->dokumentasi1);
                            @endphp
                            @if(is_array($urls))
                                @foreach($urls as $url)
                                    <img src="{{ $url }}" alt="Dokumentasi" class="img-space" style="width: 135px; height: auto;"><br>
                                @endforeach
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if($detail->dokumentasi2)
                            @php
                                $urls = json_decode($detail->dokumentasi2);
                            @endphp
                            @if(is_array($urls))
                                @foreach($urls as $url)
                                    <a href="{{ $url }}" target="_blank">{{ $url }}</a><br>
                                @endforeach
                            @else
                                -
                            @endif
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada detail event yang ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tombol Print dan Kembali -->
    <div class="d-flex justify-content-between mt-2">
        <!-- Tombol Kembali ke Laporan Event di Kiri -->
        <a href="{{ route('kegiatan.report') }}" class="btn btn-primary btn-lg shadow-lg p-3 mb-2 rounded">
            <i class="fa fa-arrow-left"></i>
        </a>

        <!-- Tombol Print di Kanan -->
        <a href="{{ route('events.printDetail', $event->id) }}" class="btn btn-secondary btn-lg shadow-lg p-3 mb-2 rounded">
            <i class="fa fa-print"></i> <strong>Print</strong>
        </a>
    </div>
</div>
@endsection
