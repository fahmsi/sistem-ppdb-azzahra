<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu Bukti Pendaftaran - Sistem PPDB</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,600,700|lato:400,700" rel="stylesheet" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])

    <style>
        @media print {
            body {
                background-color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            .print-border {
                border: 2px solid #1E40AF !important; /* Primary Blue */
            }
        }
    </style>
</head>
<body class="bg-gray-200 font-body antialiased min-h-screen py-10 px-4 flex flex-col items-center justify-center">

    <!-- Action Buttons (Hidden on Print) -->
    <div class="mb-8 flex gap-4 no-print">
        <a href="{{ route('parent.dashboard') }}" class="px-4 py-2 bg-white text-gray-700 rounded-lg shadow border border-gray-300 hover:bg-gray-50 font-medium transition-colors">
            Kembali ke Dashboard
        </a>
        <button onclick="window.print()" class="px-4 py-2 bg-primary-600 text-white rounded-lg shadow hover:bg-primary-700 font-medium transition-colors flex items-center gap-2">
            Cetak Kartu PDF
        </button>
    </div>

    <!-- Card Wrapper -->
    <div class="bg-white rounded-xl shadow-2xl p-0 overflow-hidden w-full max-w-2xl print:shadow-none print:w-[210mm] print:mx-auto print-border border border-gray-200">
        
        <!-- Header -->
        <div class="bg-primary-700 text-white p-6 border-b-4 border-secondary-500 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <!-- Placeholder Logo -->
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-inner">
                    <div class="w-12 h-12 rounded-full border-2 border-primary-500 flex items-center justify-center bg-gray-50">
                        <span class="text-primary-700 font-bold text-xs uppercase">Logo</span>
                    </div>
                </div>
                <div>
                    <h1 class="text-2xl font-heading font-bold uppercase tracking-wider">Kartu Pendaftaran PPDB</h1>
                    <p class="text-primary-100 text-sm">PAUD Al Qur'an Az-Zahra - Tahun Ajaran 2026/2027</p>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="p-8">
            <div class="flex flex-col md:flex-row gap-8 items-start">
                
                <!-- Photo Container -->
                <div class="w-32 h-40 bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center flex-shrink-0 relative overflow-hidden rounded">
                    @if($siswa->foto)
                        <img src="{{ Storage::url($siswa->foto) }}" class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <span class="text-gray-400 text-sm font-medium text-center px-4">Foto Anak 3x4</span>
                    @endif
                </div>

                <!-- Details Data -->
                <div class="flex-1 w-full">
                    <table class="w-full text-left border-collapse">
                        <tbody>
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-2 text-sm font-semibold text-gray-500 w-1/3">No. Pendaftaran</th>
                                <td class="py-3 px-2 font-bold text-gray-900 text-lg">
                                    REG-{{ $registration->created_at->format('Y') }}-{{ str_pad($registration->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-2 text-sm font-semibold text-gray-500">Gelombang</th>
                                <td class="py-3 px-2 font-medium text-gray-800">
                                    {{ $registration->pendaftaran->gelombang }} ({{ $registration->pendaftaran->tahun_ajaran }})
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-2 text-sm font-semibold text-gray-500">Nama Lengkap</th>
                                <td class="py-3 px-2 font-medium text-gray-800 uppercase">
                                    {{ $siswa->nama }}
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <th class="py-3 px-2 text-sm font-semibold text-gray-500">Tempat, Tanggal Lahir</th>
                                <td class="py-3 px-2 font-medium text-gray-800">
                                    {{ $siswa->tempat_lahir }}, 
                                    {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th class="py-3 px-2 text-sm font-semibold text-gray-500">Status Validasi</th>
                                <td class="py-3 px-2">
                                    <span class="inline-block px-3 py-1 bg-secondary-100 text-secondary-800 font-bold text-xs border border-secondary-300 uppercase tracking-widest">
                                        DITERIMA
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Notes -->
            <div class="mt-10 pt-4 border-t-2 border-dashed border-gray-300">
                <h4 class="font-bold text-gray-800 text-sm mb-2">Catatan Penting:</h4>
                <ul class="list-disc list-inside text-xs text-gray-600 space-y-1">
                    <li>Kartu ini adalah bukti sah pendaftaran PPDB PAUD Al Qur'an Az-Zahra.</li>
                    <li>Harap dibawa saat proses daftar ulang dan pengukuran seragam.</li>
                    <li>Pastikan semua berkas fisik asli (KK, Akta Kelahiran) juga dibawa untuk verifikasi akhir.</li>
                </ul>
            </div>
        </div>
        
    </div>

</body>
</html>
