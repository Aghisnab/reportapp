@extends('layout.main')

@section('title', 'Data Event')

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Daftar Event</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Events</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Data Event</strong>
                @if (Auth::check() && in_array(Auth::user()->type, ['admin', 'staff']))
                    <a href="{{ url('kegiatan/create') }}" class="btn btn-success btn-sm">
                        <i class="fa fa-plus"></i> Tambah
                    </a>
                @endif
            </div>

            <div class="card-body table-responsive">
                <table id="myTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Event</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Bulan Event</th>
                            <th>Alamat</th>
                            <th>Deskripsi</th>
                            <th>Artikel</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($events as $event)
                            <tr>
                                <td>{{ $event->nama_event }}</td>
                                <td>{{ $event->tanggal_mulai }}</td>
                                <td>{{ $event->tanggal_selesai }}</td>
                                <td>{{ $event->bulan_event }}</td>
                                <td>{{ $event->alamat }}</td>
                                <td>
                                    @php
                                        $fullDescription = $event->deskripsi;
                                        $words = explode(' ', $fullDescription);
                                        $shortDescription = implode(' ', array_slice($words, 0, 15));
                                    @endphp
                                    {{ $shortDescription }}
                                    @if (count($words) > 15)
                                        ... <a href="{{ url('events/' . $event->id . '/edit') }}">Lihat Selengkapnya</a>
                                    @endif
                                </td>
                                <td class="artikel-column">
                                    @if($event->artikel)
                                        @php
                                            $artikelText = $event->artikel;
                                            $maxLength = 30; // Batas panjang teks yang ditampilkan
                                            $displayText = strlen($artikelText) > $maxLength ? substr($artikelText, 0, $maxLength) . '...' : $artikelText;
                                        @endphp
                                        <a href="{{ $event->artikel }}" target="_blank">{{ $displayText }}</a>
                                    @else
                                        Tidak ada artikel
                                    @endif
                                </td>
                                <td>
                                    @if($event->gambar)
                                        <img src="{{ asset('storage/' . $event->gambar) }}" alt="{{ $event->nama_event }}" class="image-fullsize" style="max-width: 135px; height: auto;">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ url('events/' . $event->id . '/edit') }}" class="btn btn-primary btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if (Auth::check() && in_array(Auth::user()->type, ['admin', 'staff']))
                                        <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data event ini?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
