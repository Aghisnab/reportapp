<!DOCTYPE html>
<html>
<head>
    <title>Laporan Detail Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #343a40;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        h1, h2, h3 {
            text-align: center;
            color: #121212;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #686f77;
            color: #ffffff;
        }
        .event-info {
            margin-top: 20px;
        }
        .event-info p {
            margin: 5px 0;
        }
        .img-thumbnail {
            width: 100px;
            height: auto;
            border-radius: 5px;
        }
        .col-dokumentasi-video {
            width: 30px; /* Set the width to 30px */
            word-wrap: break-word;
        }
        .img-space {
            margin-bottom: 8px; /* Menambahkan jarak bawah 15px antar gambar */
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Laporan Detail Event</h1>
        <h2>{{ $event->nama_event }}</h2>

        <!-- Informasi Singkat mengenai Event -->
        <div class="event-info">
            <p><strong>Tanggal Mulai:</strong> {{ $event->tanggal_mulai }}</p>
            <p><strong>Tanggal Selesai:</strong> {{ $event->tanggal_selesai }}</p>
            <p><strong>Alamat:</strong> {{ $event->alamat }}</p>
            <p><strong>Deskripsi:</strong> {{ $event->deskripsi }}</p>
            @if($event->note)
                <p><strong>Catatan:</strong> {{ $event->note->isi_catatan }}</p>
            @else
                <p><strong>Catatan:</strong> Tidak ada catatan</p>
            @endif
        </div>

        <h3>Detail Acara</h3>
        <table>
            <thead>
                <tr>
                    <th>Hari Ke</th>
                    <th>Tanggal</th>
                    <th>Rangkaian Acara</th>
                    <th>Dokumentasi Gambar</th>
                    <th class="col-dokumentasi-video">Dokumentasi Video</th>
                </tr>
            </thead>
            <tbody>
                @foreach($details as $index => $detail)
                <tr>
                    <td>{{ $detail->hari_ke }}</td>
                    <td>{{ $detail->tanggal }}</td>
                    <td>{!! nl2br(e($detail->rangkaian_acara)) !!}</td>
                    <td>
                        @if ($detail->dokumentasi1)
                            @php
                                $urls = json_decode($detail->dokumentasi1);
                            @endphp
                            @if (is_array($urls))
                                @foreach ($urls as $url)
                                    <img src="{{ $url }}" alt="Dokumentasi" class="img-space" style="width: 135px; height: auto;">
                                @endforeach
                            @else
                                Tidak ada dokumentasi1
                            @endif
                        @else
                            Tidak ada dokumentasi1
                        @endif
                    </td>
                    <td class="col-dokumentasi-video">
                        @if ($detail->dokumentasi2)
                            @php
                                $urls = json_decode($detail->dokumentasi2);
                            @endphp
                            @if (is_array($urls))
                                @foreach ($urls as $url)
                                    <a href="{{ $url }}" target="_blank">lihat dokumentasi</a><br>
                                @endforeach
                            @else
                                Tidak ada dokumentasi2
                            @endif
                        @else
                            Tidak ada dokumentasi2
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
