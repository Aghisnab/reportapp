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
                    <!-- Ensure correct close button for Bootstrap 4 -->
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
            var events = {};

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var calendar = $('#calendar').fullCalendar({
                editable: true,
                events: SITEURL + "/full-calender",
                displayEventTime: false,
                eventRender: function(events, element, view) {
                    events.allDay = events.allDay === 'true';
                },
                selectable: true,
                selectHelper: true,

                select: function(start, end, allDay) {
                    selectedDate = $.fullCalendar.formatDate(start, "Y-MM-DD");
                    loadEventsForDate(selectedDate);
                },

                eventClick: function(events) {
                    $('#eventNameTop').text(events.nama_event || 'No title available');
                    $('#eventAddress').text(events.alamat || 'No address available');
                    $('#eventImage').html(events.gambar ? '<img src="' + events.gambar + '" alt="Event Image" class="img-fluid">' : '');
                    $('#noteContent').val(events.notes || '');
                    $('#eventModal').modal('show');
                }
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
                            response.events.forEach(function(events) {
                                $('#eventList').append(
                                    '<p class="event-item" data-nama="' + events.nama_event + '" data-alamat="' + events.alamat + '" data-id="' + events.id + '">' + events.nama_event + ' (' + events.tanggal_mulai + ')</p>'
                                );
                            });
                            $('#eventModal').modal('show');
                        } else {
                            $('#noEventsMessage').show();
                            $('#eventModal').modal('show');
                        }
                    },
                    error: function() {
                        toastr.error("Failed to load events for this date.");
                    }
                });
            }

            $('#saveNoteBtn').off('click').on('click', function() {
                var noteContent = $('#noteContent').val();
                if (noteContent) {
                    $.ajax({
                        url: SITEURL + '/events/' + events.event_id + '/add-note',
                        type: 'POST',
                        data: {
                            tanggal_catatan: selectedDate,
                            isi_catatan: noteContent
                        },
                        success: function(response) {
                            toastr.success(response.message, 'Event');
                            events.notes = events.notes || noteContent;
                            $('#eventModal').modal('hide');
                        },
                        error: function(response) {
                            toastr.error(response.message, 'Error');
                        }
                    });
                }
            });
        });
    </script>
@endpush
