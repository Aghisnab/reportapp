<!DOCTYPE html>
<html>
<head>
    <title>Laporan Objek Wisata</title>
    <style>
        /* Tambahkan gaya CSS sesuai kebutuhan */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        h1 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Laporan Objek Wisata</h1>
    @if(isset($alamat))
        <h2>Untuk Alamat: {{ $alamat }}</h2>
    @endif
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID Obwis</th>
                <th>Nama Obwis</th>
                <th>CP</th>
                <th>Alamat</th>
                <th>Maps</th>
                <th>Gambar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($obwis as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->obwis_id }}</td>
                <td>{{ $item->nama_obwis }}</td>
                <td>{{ $item->cp ? $item->cp : 'No Info Contact Person' }}</td>
                <td>{{ $item->alamat }}</td>
                <td>
                    @if($item->maps)
                        <a href="{{ $item->maps }}" target="_blank">Lihat Maps</a>
                    @else
                        <span>No Info maps</span>
                    @endif
                </td>
                <td>
                    @if($item->gambar)
                        <a href="{{ $item->gambar }}" target="_blank">Lihat Gambar</a>
                    @else
                        <span>Tidak ada gambar</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
