<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel 10 Create Event in FullCalendar with Notes</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>
<body>
    <div class="container">
        <h1>Laravel 10 Create Event in FullCalendar with Notes</h1>
        <div id="calendar"></div>
    </div>

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
                    <!-- Event Name at the Top -->
                    <h4 id="eventNameTop"></h4> <!-- Event Name at top -->

                    <!-- Event Details: Image, Address -->
                    <div id="eventImage"></div> <!-- Event Image will be added here -->
                    <p id="eventAddress"></p> <!-- Event Address -->

                    <!-- Note Entry Section -->
                    <textarea id="noteContent" class="form-control" rows="5" placeholder="Enter your note here..."></textarea>

                    <!-- Display events for the selected date -->
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

    <script>
        $(document).ready(function() {
            var SITEURL = "{{ url('/') }}";  // Laravel route base URL
            var selectedDate = '';  // Variable to store the selected date
            var events = {};  // Variable to store the event details when clicking on a specific event

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Initialize FullCalendar
            var calendar = $('#calendar').fullCalendar({
                editable: true,
                events: SITEURL + "/full-calender",  // URL for loading events
                displayEventTime: false,
                eventRender: function(events, element, view) {
                    if (events.allDay === 'true') {
                        events.allDay = true;
                    } else {
                        events.allDay = false;
                    }
                },
                selectable: true,
                selectHelper: true,

                // Selecting a date to view events
                select: function(start, end, allDay) {
                    selectedDate = $.fullCalendar.formatDate(start, "Y-MM-DD");  // Store the selected date
                    loadEventsForDate(selectedDate);  // Load events for the selected date
                },

                // Event click to show event details
                eventClick: function(events) {
                    console.log(events);  // Inspect the event object

                    // Set the event details
                    $('#eventNameTop').text(events.nama_event || 'No title available');
                    $('#eventAddress').text(events.alamat || 'No address available');

                    // Show event image if it exists
                    if (events.gambar) {
                        $('#eventImage').html('<img src="' + events.gambar + '" alt="Event Image" class="img-fluid">');
                    } else {
                        $('#eventImage').html('');  // Clear image section if no image
                    }

                    // Set the note content if available
                    $('#noteContent').val(events.notes || '');

                    // Show the modal with event details
                    $('#eventModal').modal('show');
                }
            });

            // Function to load events for a specific date
            function loadEventsForDate(date) {
                $.ajax({
                    url: SITEURL + "/events-for-date",  // Define your endpoint to fetch events for the date
                    type: "GET",
                    data: { date: date },
                    success: function(response) {
                        // Clear the previous event list and hide the no events message
                        $('#eventList').empty();
                        $('#noEventsMessage').hide();

                        // Display events for the selected date
                        if (response.events.length > 0) {
                            response.events.forEach(function(events) {
                                // Assuming 'nama_event' is the title and 'alamat' is the address
                                $('#eventList').append(
                                    '<p class="event-item" data-nama="' + events.nama_event + '" data-alamat="' + events.alamat + '" data-id="' + events.id + '">' + events.nama_event + ' (' + events.tanggal_mulai + ')</p>'
                                );
                            });

                            // Show the modal with the event list
                            $('#eventModal').modal('show');
                        } else {
                            // Show "No events" message and don't show the modal
                            $('#noEventsMessage').show();
                            $('#eventModal').modal('show'); // Still show modal to display message
                        }
                    },
                    error: function() {
                        toastr.error("Failed to load events for this date.");
                    }
                });
            }

            // Handle save button click inside the modal
            $('#saveNoteBtn').off('click').on('click', function() {
                var noteContent = $('#noteContent').val();
                if (noteContent) {
                    $.ajax({
                        url: SITEURL + '/events/' + events.event_id + '/add-note',  // Endpoint for adding note
                        type: 'POST',
                        data: {
                            tanggal_catatan: selectedDate,  // Use the selected date
                            isi_catatan: noteContent
                        },
                        success: function(response) {
                            displayMessage(response.message);

                            // Optionally update the event with the note
                            if (!events.notes) {
                                events.notes = [];
                            }
                            events.notes.push(response.note);

                            // Close the modal and clear the textarea
                            $('#noteContent').val('');
                            $('#eventModal').modal('hide');
                        },
                        error: function(response) {
                            toastr.error("Failed to add note");
                        }
                    });
                } else {
                    toastr.warning("Please enter a note before saving.");
                }
            });

            // Function to display success message
            function displayMessage(message) {
                toastr.success(message, 'Event');
            }
        });
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
