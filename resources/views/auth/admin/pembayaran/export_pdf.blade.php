<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Pembayaran</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size:12px }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #ccc; padding:6px; text-align:left }
        th { background:#f5f5f5 }
    </style>
</head>
<body>
    <h3>Rekap Pembayaran</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Siswa</th>
                <th>Gelombang</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $it)
                <tr>
                    <td>{{ $it->created_at->format('d/m/Y') }}</td>
                    <td>{{ $it->pendaftaranDetail->siswa->nama ?? '-' }}</td>
                    <td>{{ $it->pendaftaranDetail->pendaftaran->gelombang ?? '-' }}</td>
                    <td>{{ number_format($it->jumlah,0,',','.') }}</td>
                    <td>{{ $it->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>