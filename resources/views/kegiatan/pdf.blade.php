<!DOCTYPE html>
<html>
<head>
    <title>Laporan Event</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Laporan Event dari {{ $awal }} hingga {{ $akhir }}</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Event</th>
                <th>Nama Event</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Alamat</th>
                <th>Deskripsi</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $index => $event)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $event->event_id }}</td>
                <td>{{ $event->nama_event }}</td>
                <td>{{ $event->tanggal_mulai }}</td>
                <td>{{ $event->tanggal_selesai }}</td>
                <td>{{ $event->alamat }}</td>
                <td>{!! nl2br(e($event->deskripsi)) !!}</td>
                <td>
                    @if($event->artikel)
                        <a href="{{ $event->artikel }}" target="_blank">{{ $event->artikel }}</a>
                    @else
                        Tidak ada artikel
                    @endif
                </td>
                <td>
                    @if($event->note)
                        <p>{{ $event->note->isi_catatan }}</p>
                    @else
                        <p>Tidak ada catatan</p>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
