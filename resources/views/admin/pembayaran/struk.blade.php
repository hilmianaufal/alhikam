<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran - {{ $pembayaran->kode_transaksi }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #111827;
            background: #f3f4f6;
            padding: 20px;
        }

        .struk {
            width: 360px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
        }

        .text-center {
            text-align: center;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 11px;
            color: #6b7280;
        }

        .line {
            border-top: 1px dashed #9ca3af;
            margin: 14px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            gap: 10px;
        }

        .label {
            color: #6b7280;
        }

        .value {
            font-weight: bold;
            text-align: right;
        }

        .total {
            font-size: 16px;
            font-weight: bold;
        }

        .btn-print {
            display: block;
            width: 360px;
            margin: 20px auto;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #047857;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .btn-print {
                display: none;
            }

            .struk {
                width: 100%;
                box-shadow: none;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>

    <button onclick="window.print()" class="btn-print">
        Cetak Struk
    </button>

    <div class="struk">
        <div class="text-center">
            @if (\App\Helpers\AppSetting::logo())
                <img src="{{ \App\Helpers\AppSetting::logo() }}" alt="Logo"
                    style="width: 60px; height: 60px; object-fit: contain; margin: 0 auto 8px auto;">
            @endif

            <div class="title">{{ \App\Helpers\AppSetting::appName() }}</div>
            <div class="subtitle">{{ \App\Helpers\AppSetting::pondokName() }}</div>

            @if (\App\Helpers\AppSetting::address())
                <div class="subtitle">{{ \App\Helpers\AppSetting::address() }}</div>
            @endif
            <div class="subtitle">Bukti Pembayaran Santri</div>
        </div>

        <div class="line"></div>

        <div class="row">
            <div class="label">Kode</div>
            <div class="value">{{ $pembayaran->kode_transaksi }}</div>
        </div>

        <div class="row">
            <div class="label">Tanggal</div>
            <div class="value">{{ $pembayaran->tanggal_bayar?->format('d/m/Y') }}</div>
        </div>

        <div class="row">
            <div class="label">Kasir</div>
            <div class="value">{{ $pembayaran->user->name ?? '-' }}</div>
        </div>

        <div class="line"></div>

        <div class="row">
            <div class="label">Nama Santri</div>
            <div class="value">{{ $pembayaran->santri->nama ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="label">NIS</div>
            <div class="value">{{ $pembayaran->santri->nis ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="label">Kelas</div>
            <div class="value">{{ $pembayaran->santri->kelas->nama_kelas ?? '-' }}</div>
        </div>

        <div class="line"></div>

        <div class="row">
            <div class="label">Pembayaran</div>
            <div class="value">{{ $pembayaran->tagihan->jenisPembayaran->nama ?? '-' }}</div>
        </div>

        <div class="row">
            <div class="label">Metode</div>
            <div class="value">{{ ucfirst($pembayaran->metode) }}</div>
        </div>

        <div class="row">
            <div class="label">Total Tagihan</div>
            <div class="value">Rp{{ number_format($pembayaran->tagihan->nominal ?? 0, 0, ',', '.') }}</div>
        </div>

        <div class="row">
            <div class="label">Sudah Dibayar</div>
            <div class="value">Rp{{ number_format($pembayaran->tagihan->dibayar ?? 0, 0, ',', '.') }}</div>
        </div>

        <div class="line"></div>

        <div class="row total">
            <div>Jumlah Bayar</div>
            <div>Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</div>
        </div>

        <div class="line"></div>

        <div class="text-center subtitle">
            Terima kasih.<br>
            Semoga menjadi keberkahan.
        </div>
    </div>

</body>

</html>
