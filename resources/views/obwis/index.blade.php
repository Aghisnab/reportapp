@extends('layout.main')

@section('title', 'Data Objek Wisata')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Daftar Objek Wisata</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Objek Wisata</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Data Objek Wisata</strong>
            @if (Auth::check() && in_array(Auth::user()->type, ['admin', 'staff']))
                <a href="{{ url('obwis/create') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-plus"></i> Tambah
                </a>
            @endif
        </div>

        <div class="card-body table-responsive">
            <table id="myTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Objek Wisata</th>
                        <th>Paling Dicari</th>
                        <th>Contact Person</th>
                        <th>Alamat</th>
                        <th>Maps</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($obwis as $item)
                        <tr>
                            <td>{{ $item->nama_obwis }}</td>
                            <td>{{ $item->status }}</td>
                            <td>{{ $item->cp ?? 'No CP' }}</td>
                            <td>{{ $item->alamat }}</td>
                            <td>{{ $item->maps ?? 'No Maps' }}</td>
                            <td>
                                @if($item->gambar)
                                    <img src="{{ $item->gambar }}" alt="{{ $item->nama_obwis }}" class="image-fullsize" style="max-width: 175px; height: auto;">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('obwis/' . $item->id . '/edit') }}" class="btn btn-primary btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                @if (Auth::check() && in_array(Auth::user()->type, ['admin', 'staff']))
                                <form action="{{ route('obwis.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus objek wisata ini?');" style="display:inline;">
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
