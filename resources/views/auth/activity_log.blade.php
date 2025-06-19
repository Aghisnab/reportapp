@extends('layout.main')

@section('title', 'Activity Log')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Activity Log</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Activity Log</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Data Activity Log</strong>
            <button id="deleteSelected" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deleteConfirmationModal">Hapus Terpilih</button>
        </div>

        <div class="card-body table-responsive">
            @if($logs->isEmpty())
                <p class="text-center">Tidak ada data log aktivitas.</p>
            @else
                <table id="activityLogTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 5%;"><input type="checkbox" id="selectAll" style="transform: scale(1.5);"></th>
                            <th>Description</th>
                            <th>Subject Type</th>
                            <th>User Email</th>
                            <th>Causer ID</th>
                            <th>Properties</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                <td><input type="checkbox" class="logCheckbox" value="{{ $log->id }}" style="transform: scale(1.5);"></td>
                                <td>{{ $log->description }}</td>
                                <td>{{ $log->subject_type }}</td>
                                <td>
                                    @if ($log->user)
                                        {{ $log->user->email }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $log->causer_id }}</td>
                                <td>{{ $log->properties }}</td>
                                <td>{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus log yang dipilih?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const logCheckboxes = document.querySelectorAll('.logCheckbox');

    selectAllCheckbox.addEventListener('change', function() {
        logCheckboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
    });

    document.getElementById('deleteSelected').addEventListener('click', function() {
        const selectedIds = Array.from(logCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        if (selectedIds.length > 0) {
            $('#deleteConfirmationModal').modal('show');
        } else {
            alert('Tidak ada log yang dipilih.');
        }
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
        const selectedIds = Array.from(logCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        if (selectedIds.length > 0) {
            fetch('{{ route("activity.log.destroy") }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ids: selectedIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '{{ route("activity.log") }}';
                } else {
                    alert('Gagal menghapus log.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    });
});
</script>
@endsection
