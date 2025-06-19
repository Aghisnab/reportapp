@extends('layout.app')

@section('title', 'Laravel 10 Create Event in FullCalendar with Notes')

@section('content')
    <h1>Event Calendar</h1>
    <div id="calendar"></div>
@endsection

@section('modals')
    <!-- Modal for event details -->
    <div id="eventModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 id="eventNameTop"></h4>
                    <div id="eventImage"></div>
                    <p id="eventAddress"></p>
                    <textarea id="noteContent" class="form-control" rows="5" placeholder="Enter your note here..."></textarea>
                    <div id="eventList"></div>
                    <p id="noEventsMessage" style="display:none; color: red;">No events for this day.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="saveNoteBtn" class="btn btn-primary">Save Note</button>
                    <button type="button" id="editEventBtn" class="btn btn-warning" style="display: none;">Edit Event</button>
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
        var selectedEventId = null;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var calendar = $('#calendar').fullCalendar({
            editable: true,
            events: SITEURL + "/full-calender",
            displayEventTime: true,
            eventRender: function(event, element) {
                event.allDay = event.allDay === 'true';
            },
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                selectedDate = $.fullCalendar.formatDate(start, "Y-MM-DD");
                loadEventsForDate(selectedDate);
            },
            eventClick: function(event) {
                $('#eventNameTop').text(event.title || 'No title available');
                $('#eventAddress').text(event.alamat || 'No address available');
                $('#eventImage').html(event.gambar ? '<img src="' + event.gambar + '" alt="Event Image" class="img-fluid">' : '');

                // Set the selected event ID for later use
                selectedEventId = event.id;

                // Load existing notes for this event (if available)
                loadNotesForEvent(selectedEventId);

                if (event.id) {
                    $('#editEventBtn').show().data('event_id', event.id);
                } else {
                    $('#editEventBtn').hide();
                }

                $('#eventModal').modal('show');
            },
        });

        function loadEventsForDate(date) {
            $.ajax({
                url: SITEURL + "/events-for-date",
                type: "GET",
                data: { date: date },
                success: function(response) {
                    $('#eventList').empty();
                    $('#noEventsMessage').hide();
                    if (response.events.length > 0) {
                        response.events.forEach(function(event) {
                            $('#eventList').append(
                                `<p class="event-item" data-nama="${event.nama_event}" data-alamat="${event.alamat}" data-id="${event.id}">${event.nama_event} (${event.tanggal_mulai})</p>`
                            );
                        });
                    } else {
                        $('#noEventsMessage').show();
                    }
                    $('#eventModal').modal('show');
                },
                error: function() {
                    toastr.error("Failed to load events.");
                }
            });
        }

        function loadNotesForEvent(eventId) {
            $.ajax({
                url: SITEURL + '/events/' + eventId + '/notes',
                type: 'GET',
                success: function(response) {
                    if (response.notes.length > 0) {
                        $('#noteContent').val(response.notes[0].isi_catatan); // Load first note for now
                    } else {
                        $('#noteContent').val(''); // Clear the note content if no note exists
                    }
                },
                error: function() {
                    toastr.error("Failed to load notes.");
                }
            });
        }

        $('#saveNoteBtn').off('click').on('click', function() {
            var noteContent = $('#noteContent').val();
            if (noteContent && selectedEventId) {
                $.ajax({
                    url: SITEURL + '/events/' + selectedEventId + '/add-note',
                    type: 'POST',
                    data: {
                        tanggal_catatan: selectedDate,
                        isi_catatan: noteContent
                    },
                    success: function(response) {
                        toastr.success(response.message, 'Event');
                        $('#eventModal').modal('hide');
                    },
                    error: function(response) {
                        toastr.error(response.message, 'Error');
                    }
                });
            }
        });

        $('#editEventBtn').off('click').on('click', function() {
            var eventId = $(this).data('event_id');
            if (eventId) {
                window.location.href = SITEURL + '/events/' + eventId + '/edit';
            }
        });
    });
    </script>
@endpush
