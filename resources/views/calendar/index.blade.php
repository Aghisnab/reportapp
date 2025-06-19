@extends('layout.app')

@section('title', 'Event Calendar with Notes')

@section('content')
    <h1>Event Calendar</h1>
    <div id="calendar"></div>

    <!-- Modal for Adding/Viewing Notes -->
    <div id="noteModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noteModalLabel">Details for <span id="noteDate"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Events:</h6>
                    <ul id="eventList" class="list-unstyled"></ul>
                    <hr>
                    <h6>Note:</h6>
                    <textarea id="noteContent" class="form-control" rows="5" placeholder="Enter your note here..."></textarea>
                </div>
                <div class="modal-footer">
                @if (Auth::check() && in_array(Auth::user()->type, ['admin', 'staff'])) <!-- Cek apakah pengguna adalah admin, staff, atau kepala dinas -->
                        <button type="button" id="deleteNoteBtn" class="btn btn-danger">Delete Note</button>
                        <button type="button" id="saveNoteBtn" class="btn btn-primary">Save Note</button>
                    @endif
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var SITEURL = "{{ url('/') }}";
    var selectedDate = '';

    // Initialize FullCalendar
    $('#calendar').fullCalendar({
        editable: true,
        events: SITEURL + "/calendar/data",
        selectable: true,
        selectHelper: true,
        select: function(start) {
            selectedDate = moment(start).format('YYYY-MM-DD');
            loadDetailsForDate(selectedDate);
        },
        eventRender: function(event, element) {
            // Tidak perlu merubah warna di sini
        },
        dayRender: function(date, cell) {
            // Mengambil data catatan untuk tanggal tertentu
            $.ajax({
                url: SITEURL + "/notes/for-date", // Pastikan URL ini mengembalikan data catatan yang sesuai
                type: "GET",
                data: {
                    date: date.format('YYYY-MM-DD') // Kirim tanggal yang sedang dirender
                },
                success: function(noteResponse) {
                    // Mengambil data event untuk tanggal tertentu
                    $.ajax({
                        url: SITEURL + "/calendar/data", // Pastikan URL ini mengembalikan data event yang sesuai
                        type: "GET",
                        data: {
                            start: date.startOf('day').format('YYYY-MM-DD'),
                            end: date.endOf('day').format('YYYY-MM-DD')
                        },
                        success: function(eventResponse) {
                            // Cek apakah ada catatan di tanggal ini
                            var hasNote = noteResponse.note ? true : false;
                            // Cek apakah ada event di tanggal ini
                            var hasEvent = eventResponse.length > 0;

                            // Tambahkan kelas berdasarkan ada tidaknya catatan dan event
                            if (hasNote && hasEvent) {
                                cell.addClass('has-note-and-event');
                            } else if (hasNote) {
                                cell.addClass('has-note');
                            } else if (hasEvent) {
                                cell.addClass('has-event');
                            } else {
                                // Tidak perlu menambahkan kelas untuk tanggal tanpa catatan dan event,
                                // cell akan tetap tanpa warna (putih)
                                cell.removeClass('has-note has-event has-note-and-event');
                            }
                        }
                    });
                }
            });
        }
    });

        // Memuat detail untuk tanggal yang dipilih
        function loadDetailsForDate(date) {
            $.ajax({
                url: SITEURL + "/notes/for-date",
                type: "GET",
                data: { date: date },
                success: function(response) {
                    $('#noteDate').text(date);
                    loadEventsForDate(date);
                    $('#noteContent').val(response.note ? response.note.isi_catatan : '');
                    currentNoteId = response.note ? response.note.id : null; // Set current note ID
                    $('#deleteNoteBtn').toggle(!!currentNoteId); // Show delete button if note exists
                    $('#noteModal').modal('show'); // Tampilkan modal catatan
                },
                error: function() {
                    alert('Failed to load data.');
                }
            });
        }

        function loadEventsForDate(date) {
        $.ajax({
            url: SITEURL + "/events/for-date",
            type: "GET",
            data: { date: date },
            success: function(response) {
                var events = response.events || [];
                var eventList = $('#eventList');
                eventList.empty();

                if (events.length > 0) {
                    events.forEach(event => {
                        eventList.append(
                            `<li class="event-item" data-id="${event.id}" data-alamat="${event.alamat}" data-nama="${event.nama_event}">
                                <strong>${event.nama_event}</strong>
                                <p style="margin: 0;">${event.alamat}</p>
                                <small>(${event.tanggal_mulai})</small>
                            </li>`
                        );
                    });
                } else {
                    eventList.append('<li>No events for this date.</li>');
                }
            },
            error: function() {
                alert('Failed to load events.');
            }
        });
    }

        $('#saveNoteBtn').off('click').on('click', function() {
        var noteContent = $('#noteContent').val();
        if (currentNoteId) {
            // If a note already exists, update it
            updateNoteForDate(currentNoteId, noteContent);
        } else {
            // Otherwise, save a new note
            saveNoteForDate(selectedDate, noteContent);
        }
    });

    // Function to save note for a specific date
    function saveNoteForDate(date, noteContent) {
        $.ajax({
            url: SITEURL + '/notes/add', // Adjust this URL according to your routes
            type: "POST",
            data: {
                tanggal_catatan: date,
                isi_catatan: noteContent,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                alert('Note saved!');
                $('#calendar').fullCalendar('refetchEvents'); // Reload events after saving note
                $('#noteModal').modal('hide');
            },
            error: function() {
                alert('Failed to save note.');
            }
        });
    }

    // Function to update an existing note
    function updateNoteForDate(noteId, noteContent) {
        $.ajax({
            url: SITEURL + '/notes/update/' + noteId, // Adjust this URL according to your routes
            type: "PUT",
            data: {
                isi_catatan: noteContent,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                alert('Note updated!');
                $('#calendar').fullCalendar('refetchEvents'); // Reload events after updating note
                $('#noteModal').modal('hide');
            },
            error: function() {
                alert('Failed to update note.');
            }
        });
    }

        // Function to delete a note
        $('#deleteNoteBtn').off('click').on('click', function() {
            if (currentNoteId) {
                if (confirm('Are you sure you want to delete this note?')) {
                    deleteNoteForDate(currentNoteId);
                }
            }
        });

        function deleteNoteForDate(noteId) {
            $.ajax({
                url: SITEURL + '/notes/delete/' + noteId, // Adjust this URL according to your routes
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    alert('Note deleted!');
                    $('#calendar').fullCalendar('refetchEvents'); // Reload events after deleting note
                    $('#noteModal').modal('hide');
                },
                error: function() {
                    alert('Failed to delete note.');
                }
            });
        }
    });
</script>
@endpush
