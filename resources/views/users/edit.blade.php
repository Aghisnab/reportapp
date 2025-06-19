@extends('layout.main')

@section('title', 'Edit Pengguna')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Edit Pengguna</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Pengguna</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <strong>Edit Pengguna</strong>
        </div>

        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ $user->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password (Kosongkan jika tidak ingin mengubah)</label>
                    <input type="password" name="password" class="form-control" id="password">
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto</label>
                    <input type="file" name="foto" class="form-control" id="foto">
                </div>
                @if($user->foto)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->name }}" class="image-fullsize" style="max-width: 100px;">
                    </div>
                @endif
                <div class="mb-3">
                    <label for="type" class="form-label">Tipe Pengguna</label>
                    <select name="type" class="form-control" id="type" required>
                        <option value="user" {{ $user->type == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ $user->type == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="staff" {{ $user->type == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
