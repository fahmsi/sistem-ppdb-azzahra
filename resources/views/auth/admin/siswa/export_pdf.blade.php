<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Siswa</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size:12px }
        table { width:100%; border-collapse: collapse; }
        th, td { border:1px solid #ccc; padding:6px; text-align:left }
        th { background:#f5f5f5 }
    </style>
</head>
<body>
    <h3>Data Induk Siswa</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Nama Panggilan</th>
                <th>Jenis Kelamin</th>
                <th>Orang Tua</th>
                <th>No. Telp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($siswas as $i => $s)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $s->nama }}</td>
                    <td>{{ $s->nama_panggilan ?? '-' }}</td>
                    <td>{{ $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $s->user->name ?? '-' }}</td>
                    <td>{{ $s->no_telpon ?? $s->user->no_telpon ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>