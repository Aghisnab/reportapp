@extends('layout.main')

@section('content')
<div class="container" style="margin-top:30px";>
    <h2>Cetak Laporan Objek Wisata</h2>
    <form action="{{ route('obwis.report.generate') }}" method="post">
        @csrf
        <div class="row">
            <div class="col-lg-6">
                <label for="alamat">Alamat</label>
                <input name="alamat" type="text" class="form-control" placeholder="Masukkan alamat..." />
            </div>
            <div class="col-lg-3">
                <button type="submit" class="btn btn-success mt-4">Tampilkan</button>
            </div>
        </div>
    </form>

    @if(session('obwis'))
        <h3 class="mt-5">Data Objek Wisata untuk Alamat: {{ session('alamat') }}</h3>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Obwis</th>
                    <th>Nama Obwis</th>
                    <th>CP</th>
                    <th>Alamat</th>
                    <th>Maps</th>
                    <th>Gambar</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('obwis') as $index => $obwis)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $obwis->obwis_id }}</td>
                    <td>{{ $obwis->nama_obwis }}</td>
                    <td>
                        {{ $obwis->cp ? $obwis->cp : 'No Info Contact Person' }}
                    </td>
                    <td>{{ $obwis->alamat }}</td>
                    <td>
                        {{ $obwis->maps ? $obwis->maps : 'No Info Maps' }}
                    </td>
                    <td>
                        @if($obwis->gambar)
                            <img src="{{ $obwis->gambar }}" alt="Gambar" style="width: 100px; height: auto;">
                        @else
                            <span>Tidak ada gambar</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <form action="{{ route('obwis.report.pdf') }}" method="post">
            @csrf
            <input type="hidden" name="alamat" value="{{ session('alamat') }}">
            <button type="submit" class="btn btn-primary mt-3">Cetak Laporan PDF</button>
        </form>
    @endif
</div>
@endsection
