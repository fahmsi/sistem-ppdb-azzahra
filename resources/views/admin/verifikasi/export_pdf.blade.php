<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Verifikasi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size:12px }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #ccc; padding:6px; text-align:left }
        th { background:#f5f5f5 }
    </style>
</head>
<body>
    <h3>Data Verifikasi Pendaftar</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anak</th>
                <th>Wali</th>
                <th>Gelombang</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $i => $it)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $it->siswa->nama ?? '-' }}</td>
                    <td>{{ $it->siswa->user->name ?? '-' }}</td>
                    <td>{{ $it->pendaftaran->gelombang ?? '-' }}</td>
                    <td>{{ $it->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>