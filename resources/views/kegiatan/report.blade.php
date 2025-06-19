@extends('layout.main')

@section('content')
<div class="container" style="margin-top:30px";>
        <h2>Cetak Laporan Event</h2>
        <form action="{{ route('events.report.generate') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-lg-3">
                    <label for="txtTglAwal">Tanggal Awal</label>
                    <input name="txtTglAwal" type="date" class="form-control" required />
                </div>
                <div class="col-lg-3">
                    <label for="txtTglAkhir">Tanggal Akhir</label>
                    <input name="txtTglAkhir" type="date" class="form-control" />
                </div>
                <div class="col-lg-3">
                    <button type="submit" class="btn btn-success mt-4">Tampilkan</button>
                </div>
            </div>
        </form>

        @if(session('events'))
            <h3 class="mt-5">Data Event dari {{ session('awal') }} hingga {{ session('akhir') }}</h3>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Event</th>
                        <th>Nama Event</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Alamat</th>
                        <th>Deskripsi</th>
                        <th>Catatan</th>
                        <th>Aksi</th> <!-- Tambahkan kolom aksi -->
                    </tr>
                </thead>
                <tbody>
                    @foreach(session('events') as $index => $event)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $event->event_id }}</td>
                        <td>{{ $event->nama_event }}</td>
                        <td>{{ $event->tanggal_mulai }}</td>
                        <td>{{ $event->tanggal_selesai }}</td>
                        <td>{{ $event->alamat }}</td>
                        <td>{!! nl2br(e($event->deskripsi)) !!}</td>
                        <td>
                            @if($event->note)
                                <p>{{ $event->note->isi_catatan }}</p>
                            @else
                                <p>Tidak ada catatan</p>
                            @endif
                        </td>
                        <td> <!-- Tambahkan aksi detail -->
                            <a href="{{ route('events.detailPrint', $event->id) }}" class="btn btn-info">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Tombol untuk mencetak laporan PDF -->
            <form action="{{ route('events.report.pdf') }}" method="post">
                @csrf
                <input type="hidden" name="txtTglAwal" value="{{ session('awal') }}">
                <input type="hidden" name="txtTglAkhir" value="{{ session('akhir') }}">
                <button type="submit" class="btn btn-primary mt-3">Cetak Laporan PDF</button>
            </form>
        @endif
</div>
@endsection
