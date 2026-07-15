<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #111827;
            font-size: 12px;
            background: #f3f4f6;
            padding: 24px;
        }

        .paper {
            background: white;
            max-width: 960px;
            margin: 0 auto;
            padding: 32px;
        }

        .center {
            text-align: center;
        }

        h1,
        h2,
        h3 {
            margin: 0;
        }

        .subtitle {
            color: #6b7280;
            margin-top: 4px;
        }

        .line {
            border-top: 2px solid #111827;
            margin: 20px 0;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #d1d5db;
            padding: 12px;
            border-radius: 8px;
        }

        .card p {
            margin: 0;
            color: #6b7280;
        }

        .card h3 {
            margin-top: 6px;
            font-size: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
        }

        th {
            background: #f9fafb;
            text-align: left;
        }

        .right {
            text-align: right;
        }

        .btn-print {
            display: block;
            max-width: 960px;
            margin: 0 auto 20px auto;
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

            .paper {
                max-width: 100%;
                padding: 0;
            }
        }
    </style>
</head>

<body>

    <button onclick="window.print()" class="btn-print">
        Cetak / Simpan PDF
    </button>

    <div class="paper">
        <div class="center">
            @if (\App\Helpers\AppSetting::logo())
                <img src="{{ \App\Helpers\AppSetting::logo() }}" alt="Logo"
                    style="width: 70px; height: 70px; object-fit: contain; margin-bottom: 8px;">
            @endif

            <h1>{{ \App\Helpers\AppSetting::appName() }}</h1>
            <p class="subtitle">{{ \App\Helpers\AppSetting::pondokName() }}</p>

            @if (\App\Helpers\AppSetting::address())
                <p class="subtitle">{{ \App\Helpers\AppSetting::address() }}</p>
            @endif
            <h2 style="margin-top: 12px;">Laporan Keuangan</h2>
            <p class="subtitle">
                Periode {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }}
                sampai
                {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d/m/Y') }}
            </p>
        </div>

        <div class="line"></div>

        <div class="grid">
            <div class="card">
                <p>Total Pemasukan</p>
                <h3>Rp{{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
            </div>

            <div class="card">
                <p>Total Pengeluaran</p>
                <h3>Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
            </div>

            <div class="card">
                <p>Saldo Periode</p>
                <h3>Rp{{ number_format($saldoPeriode, 0, ',', '.') }}</h3>
            </div>

            <div class="card">
                <p>Total Tagihan</p>
                <h3>Rp{{ number_format($totalTagihan, 0, ',', '.') }}</h3>
            </div>

            <div class="card">
                <p>Dibayar</p>
                <h3>Rp{{ number_format($totalDibayarTagihan, 0, ',', '.') }}</h3>
            </div>

            <div class="card">
                <p>Sisa Tagihan</p>
                <h3>Rp{{ number_format($sisaTagihan, 0, ',', '.') }}</h3>
            </div>
        </div>

        <h3>Riwayat Kas</h3>

        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Tipe</th>
                    <th>Kategori</th>
                    <th>Metode</th>
                    <th class="right">Nominal</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($kasTransactions as $kas)
                    <tr>
                        <td>{{ $kas->tanggal?->format('d/m/Y') }}</td>
                        <td>{{ $kas->kode }}</td>
                        <td>{{ ucfirst($kas->tipe) }}</td>
                        <td>
                            {{ $kas->kategori }}
                            @if ($kas->keterangan)
                                <br>
                                <small>{{ $kas->keterangan }}</small>
                            @endif
                        </td>
                        <td>{{ ucfirst($kas->metode) }}</td>
                        <td class="right">Rp{{ number_format($kas->nominal, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="center">Tidak ada transaksi kas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h3 style="margin-top: 24px;">Riwayat Pembayaran</h3>

        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Kode</th>
                    <th>Santri</th>
                    <th>Jenis</th>
                    <th>Kasir</th>
                    <th class="right">Jumlah</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($pembayarans as $pembayaran)
                    <tr>
                        <td>{{ $pembayaran->tanggal_bayar?->format('d/m/Y') }}</td>
                        <td>{{ $pembayaran->kode_transaksi }}</td>
                        <td>
                            {{ $pembayaran->santri->nama ?? '-' }}
                            <br>
                            <small>{{ $pembayaran->santri->nis ?? '-' }}</small>
                        </td>
                        <td>{{ $pembayaran->tagihan->jenisPembayaran->nama ?? '-' }}</td>
                        <td>{{ $pembayaran->user->name ?? '-' }}</td>
                        <td class="right">Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="center">Tidak ada pembayaran.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 40px; display: flex; justify-content: flex-end;">
            <div class="center" style="width: 220px;">
                <p>Subang, {{ now()->format('d/m/Y') }}</p>
                <br><br><br>
                <p>________________________</p>
            </div>
        </div>
    </div>

</body>

</html>
