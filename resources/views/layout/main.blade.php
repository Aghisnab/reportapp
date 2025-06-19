<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('assets/img/logo.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://cdn.datatables.net/v/dt/dt-2.1.8/datatables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    @include('layout.topnav')
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            @include('layout.sidebar')
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <!-- JavaScript -->
     <!-- jQuery Library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/v/dt/dt-2.1.8/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>

    <!-- Flatpickr JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Initialize Flatpickr (Optionally, you can place this in a specific view file if needed) -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#tanggal_mulai", {
                dateFormat: "Y-m-d",
                // Menghapus minDate agar bisa memilih tanggal sebelumnya
            });
            flatpickr("#tanggal_selesai", {
                dateFormat: "Y-m-d",
                // Menghapus minDate agar bisa memilih tanggal sebelumnya
            });
        });
    </script>

    <script>
    $(document).ready( function () {
        $('#myTable').DataTable();
        } );
    </script>

    <!-- Kalender -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: '/event/calendar-data',
                eventClick: function(info) {
                    $('#noteModal').modal('show');

                    // Populate form with event ID
                    $('#event_id').val(info.event.id);
                }
            });

            calendar.render();

            // Handle form submission
            $('#noteForm').on('submit', function(e) {
                e.preventDefault();

                const formData = $(this).serialize();
                const eventId = $('#event_id').val();

                $.ajax({
                    url: `/events/${eventId}/notes`,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert('Catatan berhasil disimpan.');
                        $('#noteModal').modal('hide');
                        calendar.refetchEvents(); // Refresh calendar
                    },
                    error: function(error) {
                        alert('Terjadi kesalahan, silakan coba lagi.');
                    }
                });
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#activityLogTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                order: [[6, 'desc']]
            });

            // Select/Deselect all checkboxes
            $('#selectAll').on('click', function() {
                $('.logCheckbox').prop('checked', this.checked);
            });

            // Handle delete selected logs
            $('#deleteSelected').on('click', function() {
                var selectedIds = $('.logCheckbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    alert('Silakan pilih log yang ingin dihapus.');
                    return;
                }

                // Tampilkan modal konfirmasi
                $('#deleteConfirmationModal').modal('show');

                // Set up the confirm delete button
                $('#confirmDelete').off('click').on('click', function() {
                    $.ajax({
                        url: '{{ route("activity.log.destroy") }}',
                        type: 'DELETE',
                        data: {
                            ids: selectedIds,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                window.location.href = response.redirect;
                            } else {
                                alert('Gagal menghapus log.');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat menghapus log.');
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
