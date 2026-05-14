<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran - {{ $detail->siswa->nama }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f3f4f6; color: #1f2937; }
        .receipt-container { max-width: 700px; margin: 2rem auto; background: white; border: 2px solid #e5e7eb; border-radius: 12px; overflow: hidden; }
        .receipt-header { background: linear-gradient(135deg, #1e3a5f, #2563eb); color: white; padding: 24px 32px; text-align: center; }
        .receipt-header h1 { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
        .receipt-header p { font-size: 13px; opacity: 0.85; }
        .receipt-body { padding: 32px; }
        .receipt-title { text-align: center; margin-bottom: 24px; }
        .receipt-title h2 { font-size: 18px; font-weight: 700; color: #059669; text-transform: uppercase; letter-spacing: 1px; }
        .receipt-title p { font-size: 12px; color: #6b7280; margin-top: 4px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .info-table th, .info-table td { padding: 10px 16px; text-align: left; border-bottom: 1px solid #f3f4f6; }
        .info-table th { width: 40%; font-weight: 600; color: #6b7280; font-size: 13px; }
        .info-table td { font-weight: 500; color: #1f2937; font-size: 14px; }
        .amount-box { background: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 8px; padding: 16px 24px; text-align: center; margin-bottom: 24px; }
        .amount-box .label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; }
        .amount-box .amount { font-size: 28px; font-weight: 800; color: #059669; margin-top: 4px; }
        .status-badge { display: inline-block; background: #059669; color: white; padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        .footer-note { text-align: center; padding: 16px 32px; background: #f9fafb; border-top: 1px solid #e5e7eb; font-size: 11px; color: #9ca3af; }
        .print-btn { display: block; margin: 16px auto; padding: 10px 24px; background: #2563eb; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
        .print-btn:hover { background: #1d4ed8; }
        @media print {
            body { background: white; }
            .receipt-container { border: none; margin: 0; box-shadow: none; }
            .print-btn { display: none; }
        }
    </style>
</head>
<body>
    <button class="print-btn" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>

    <div class="receipt-container">
        <div class="receipt-header">
            <h1>PAUD AL QUR'AN AZ-ZAHRA</h1>
            <p>Penerimaan Siswa Baru (PSB)</p>
        </div>

        <div class="receipt-body">
            <div class="receipt-title">
                <h2>✅ Bukti Pembayaran Daftar Ulang</h2>
                <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
            </div>

            <table class="info-table">
                <tr>
                    <th>Nama Anak</th>
                    <td>{{ $detail->siswa->nama }}</td>
                </tr>
                <tr>
                    <th>Gelombang</th>
                    <td>{{ $detail->pendaftaran->gelombang }} — {{ $detail->pendaftaran->tahun_ajaran }}</td>
                </tr>
                <tr>
                    <th>Nama Orang Tua</th>
                    <td>{{ $detail->siswa->user->name }}</td>
                </tr>
                <tr>
                    <th>Tanggal Bayar</th>
                    <td>{{ $detail->pembayaran->updated_at->format('d F Y') }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><span class="status-badge">LUNAS — TERVERIFIKASI</span></td>
                </tr>
            </table>

            <div class="amount-box">
                <div class="label">Jumlah Dibayarkan</div>
                <div class="amount">Rp {{ number_format($detail->pembayaran->jumlah, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="footer-note">
            Dokumen ini dicetak secara otomatis oleh sistem PSB PAUD Al Qur'an Az-Zahra dan sah tanpa tanda tangan.
        </div>
    </div>
</body>
</html>
