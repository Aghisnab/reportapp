/*!
    * Start Bootstrap - SB Admin v7.0.7 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2023 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
    */
    //
// Scripts
//

window.addEventListener('DOMContentLoaded', event => {

    // Toggle the side navigation
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        // if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
        //     document.body.classList.toggle('sb-sidenav-toggled');
        // }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    // public/js/script.js

    document.addEventListener('DOMContentLoaded', function() {
        // Kode JavaScript Anda di sini
        $('#event_name').on('input', function() {
            var query = $(this).val();
            if (query.length > 0) {
                $.ajax({
                    url: "/events/search",
                    method: 'GET',
                    data: { query: query },
                    success: function(data) {
                        var suggestions = '';
                        data.forEach(function(event) {
                            suggestions += '<div class="autocomplete-suggestion" data-event-id="' + event.id + '">' +
                                event.nama_event + ' (' + event.tanggal_mulai + ' - ' + event.tanggal_selesai + ')' +
                                '</div>';
                        });
                        $('#suggestions').html(suggestions);
                    }
                });
            } else {
                $('#suggestions').html('');
            }
        });

        // Menangani klik pada saran
        $(document).on('click', '.autocomplete-suggestion', function() {
            var eventId = $(this).data('event-id');
            var eventName = $(this).text(); // Ambil nama event dari teks saran

            // Set nilai input dengan nama event yang dipilih
            $('#event_name').val(eventName);
            $('#suggestions').html(''); // Hapus saran setelah memilih
        });

        // Menutup saran ketika mengklik di luar
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#event_name').length) {
                $('#suggestions').html('');
            }
        });
    });
});
