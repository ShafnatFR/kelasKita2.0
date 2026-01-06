<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi #{{ $transaksi['id_transaksi'] }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #2563eb; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; background: #d1fae5; color: #065f46; }
        h1 { color: #333; margin-bottom: 20px; }
        .transaksi-wrapper { background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .transaksi-header { border-bottom: 2px solid #e5e7eb; padding-bottom: 20px; margin-bottom: 20px; }
        .transaksi-header h2 { color: #333; margin-bottom: 10px; }
        .transaksi-header p { color: #6b7280; margin-bottom: 5px; }
        .status { display: inline-block; padding: 8px 16px; border-radius: 4px; font-size: 14px; font-weight: bold; text-transform: uppercase; margin-top: 10px; }
        .status.pending { background: #fef3c7; color: #92400e; }
        .status.success { background: #d1fae5; color: #065f46; }
        .status.paid { background: #d1fae5; color: #065f46; }
        .status.failed { background: #fee2e2; color: #991b1b; }
        .status.unpaid { background: #fee2e2; color: #991b1b; }
        .detail-item { padding: 15px; border: 1px solid #e5e7eb; border-radius: 4px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center; }
        .detail-item h4 { color: #333; margin-bottom: 5px; }
        .detail-item .price { font-size: 18px; font-weight: bold; color: #059669; }
        .summary { background: #f9fafb; padding: 20px; border-radius: 8px; margin-top: 20px; }
        .summary-row { display: flex; justify-content: space-between; padding: 10px 0; }
        .summary-row.total { border-top: 2px solid #e5e7eb; margin-top: 10px; padding-top: 15px; font-weight: bold; font-size: 24px; color: #059669; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('transaksi.index') }}" class="back-link">← Kembali ke Riwayat Transaksi</a>

        @if(session('success'))
            <div class="alert">{{ session('success') }}</div>
        @endif

        <h1>Detail Transaksi</h1>

        <div class="transaksi-wrapper">
            <div class="transaksi-header">
                <h2>Transaksi #{{ $transaksi['id_transaksi'] }}</h2>
                <p>Tanggal: {{ date('d M Y H:i', strtotime($transaksi['created_at'])) }}</p>
                <span class="status {{ $transaksi['status_pembayaran'] }}">{{ ucfirst($transaksi['status_pembayaran']) }}</span>
            </div>

            <h3 style="margin-bottom: 15px; color: #333;">Daftar Kelas yang Dibeli:</h3>

            @foreach($transaksi['transaksi_detail'] as $detail)
                <div class="detail-item">
                    <div>
                        <h4>{{ $detail['kelas']['nama_kelas'] }}</h4>
                        <p style="color: #6b7280; font-size: 14px;">Kelas ID: {{ $detail['id_kelas'] }}</p>
                    </div>
                    <div class="price">Rp {{ number_format($detail['harga_saat_beli'], 0, ',', '.') }}</div>
                </div>
            @endforeach

            <div class="summary">
                <div class="summary-row">
                    <span>Jumlah Kelas:</span>
                    <span>{{ count($transaksi['transaksi_detail']) }} kelas</span>
                </div>
                <div class="summary-row total">
                    <span>Total Pembayaran:</span>
                    <span>Rp {{ number_format($transaksi['total_harga'], 0, ',', '.') }}</span>
                </div>
            </div>

            @if($transaksi['status_pembayaran'] === 'pending' || $transaksi['status_pembayaran'] === 'unpaid')
                <div style="margin-top: 20px; padding: 15px; background: #fef3c7; border-radius: 4px; color: #92400e;">
                    <strong>⚠️ Pembayaran Pending</strong>
                    <p>Silakan lanjutkan proses pembayaran untuk mengaktifkan akses ke kelas.</p>
                </div>
            @elseif($transaksi['status_pembayaran'] === 'success' || $transaksi['status_pembayaran'] === 'paid')
                <div style="margin-top: 20px; padding: 15px; background: #d1fae5; border-radius: 4px; color: #065f46;">
                    <strong>✅ Pembayaran Berhasil</strong>
                    <p>Anda sudah dapat mengakses semua kelas yang dibeli.</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>