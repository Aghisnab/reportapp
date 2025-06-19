@extends('layout.main')

@section('title', 'Data Rencana Event')

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Daftar Rencana Event</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Rencana Event</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Data Rencana Event</strong>
                @if (Auth::check() && in_array(Auth::user()->type, ['admin', 'staff']))
                    <a href="{{ url('plan/create') }}" class="btn btn-success btn-sm">
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
                            <th>Bulan Plan</th>
                            <th>Alamat</th>
                            <th>Deskripsi</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plans as $plan)
                            <tr>
                                <td>{{ $plan->nama_event }}</td>
                                <td>{{ $plan->tanggal_mulai }}</td>
                                <td>{{ $plan->tanggal_selesai }}</td>
                                <td>{{ $plan->bulan_event }}</td>
                                <td>{{ $plan->alamat }}</td>
                                <td>
                                    @php
                                        $fullDescription = $plan->deskripsi;
                                        $words = explode(' ', $fullDescription);
                                        $shortDescription = implode(' ', array_slice($words, 0, 15));
                                    @endphp
                                    {!! nl2br(e($shortDescription)) !!}
                                    @if (count($words) > 15)
                                        ... <a href="{{ url('plan/' . $plan->id . '/edit') }}">Lihat Selengkapnya</a>
                                    @endif
                                </td>
                                <td>
                                    @if($plan->gambar)
                                        <img src="{{ asset('storage/' . $plan->gambar) }}" alt="{{ $plan->nama_event }}" class="image-thumbnail" style="max-width: 175px; height: auto;">
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center mb-2">
                                        <a href="{{ url('plan/' . $plan->id . '/edit') }}" class="btn btn-primary btn-sm me-2" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        @if (Auth::check() && in_array(Auth::user()->type, ['admin', 'staff']))
                                            <form action="{{ route('plan.destroy', $plan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data plan ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    @if (Auth::check() && in_array(Auth::user()->type, ['admin', 'staff']))
                                        @if (!$plan->event_selesai)
                                            <form action="{{ route('plan.setSelesai', $plan->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-lg d-block animate-btn mt-2" title="Set Selesai"
                                                        style="font-size: 16px; padding: 10px 20px; transition: all 0.3s ease;">
                                                    <i class="fas fa-check" style="margin-right: 8px;"></i> Set Selesai
                                                </button>
                                            </form>
                                        @else
                                            <span class="badge bg-success mt-2 d-block" style="font-size: 18px; padding: 10px;">
                                                Selesai
                                            </span>
                                        @endif
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

