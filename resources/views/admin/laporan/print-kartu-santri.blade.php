<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Tagihan Santri - {{ $santri->nama }}</title>

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
            max-width: 1000px;
            margin: 0 auto;
            padding: 32px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        h1, h2, h3, p {
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
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }

        .card {
            border: 1px solid #d1d5db;
            padding: 10px;
            border-radius: 8px;
        }

        .card p {
            color: #6b7280;
        }

        .card h3 {
            margin-top: 5px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #d1d5db;
            padding: 8px;
            vertical-align: top;
        }

        th {
            background: #f9fafb;
            text-align: left;
        }

        .btn-print {
            display: block;
            max-width: 1000px;
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
            <img src="{{ \App\Helpers\AppSetting::logo() }}"
                 alt="Logo"
                 style="width: 70px; height: 70px; object-fit: contain; margin-bottom: 8px;">
        @endif

        <h1>{{ \App\Helpers\AppSetting::appName() }}</h1>
        <p class="subtitle">{{ \App\Helpers\AppSetting::pondokName() }}</p>

        @if (\App\Helpers\AppSetting::address())
            <p class="subtitle">{{ \App\Helpers\AppSetting::address() }}</p>
        @endif

        <h2 style="margin-top: 14px;">Kartu Tagihan Santri</h2>
        <p class="subtitle">
            Dicetak pada {{ now()->format('d/m/Y H:i') }}
        </p>
    </div>

    <div class="line"></div>

    <h3>Data Santri</h3>

    <table>
        <tr>
            <th style="width: 180px;">Nama Santri</th>
            <td>{{ $santri->nama }}</td>
            <th style="width: 180px;">NIS / NISN</th>
            <td>{{ $santri->nis }} / {{ $santri->nisn ?? '-' }}</td>
        </tr>

        <tr>
            <th>Kelas</th>
            <td>{{ $santri->kelas->nama_kelas ?? '-' }}</td>
            <th>Asrama</th>
            <td>{{ $santri->asrama->nama_asrama ?? '-' }}</td>
        </tr>

        <tr>
            <th>Wali</th>
            <td>{{ $santri->nama_wali ?? '-' }}</td>
            <th>No HP Wali</th>
            <td>{{ $santri->no_hp_wali ?? '-' }}</td>
        </tr>
    </table>

    <div class="grid">
        <div class="card">
            <p>Jumlah Tagihan</p>
            <h3>{{ number_format($jumlahTagihan, 0, ',', '.') }}</h3>
        </div>

        <div class="card">
            <p>Total Tagihan</p>
            <h3>Rp{{ number_format($totalTagihan, 0, ',', '.') }}</h3>
        </div>

        <div class="card">
            <p>Sudah Dibayar</p>
            <h3>Rp{{ number_format($totalDibayar, 0, ',', '.') }}</h3>
        </div>

        <div class="card">
            <p>Sisa Tagihan</p>
            <h3>Rp{{ number_format($sisaTagihan, 0, ',', '.') }}</h3>
        </div>
    </div>

    <h3>Daftar Tagihan</h3>

    <table>
        <thead>
            <tr>
                <th>Jenis</th>
                <th>Periode</th>
                <th class="right">Nominal</th>
                <th class="right">Dibayar</th>
                <th class="right">Sisa</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($tagihans as $tagihan)
                <tr>
                    <td>{{ $tagihan->jenisPembayaran->nama ?? '-' }}</td>

                    <td>
                        {{ $tagihan->tahunAjaran->nama_tahun ?? '-' }}
                        @if ($tagihan->bulan)
                            <br>
                            <small>Bulan: {{ $tagihan->bulan }} / {{ $tagihan->tahun }}</small>
                        @endif
                    </td>

                    <td class="right">Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}</td>
                    <td class="right">Rp{{ number_format($tagihan->dibayar, 0, ',', '.') }}</td>
                    <td class="right">
                        <strong>Rp{{ number_format($tagihan->nominal - $tagihan->dibayar, 0, ',', '.') }}</strong>
                    </td>
                    <td>{{ str_replace('_', ' ', ucfirst($tagihan->status)) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Belum ada tagihan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <h3>Riwayat Pembayaran</h3>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kode</th>
                <th>Pembayaran</th>
                <th>Metode</th>
                <th>Kasir</th>
                <th class="right">Jumlah</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($pembayarans as $pembayaran)
                <tr>
                    <td>{{ $pembayaran->tanggal_bayar?->format('d/m/Y') }}</td>
                    <td>{{ $pembayaran->kode_transaksi }}</td>
                    <td>{{ $pembayaran->tagihan->jenisPembayaran->nama ?? '-' }}</td>
                    <td>{{ ucfirst($pembayaran->metode) }}</td>
                    <td>{{ $pembayaran->user->name ?? '-' }}</td>
                    <td class="right">Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Belum ada pembayaran.</td>
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
