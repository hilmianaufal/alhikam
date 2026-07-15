<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tunggakan Santri</title>

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
            max-width: 1100px;
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
            grid-template-columns: repeat(5, 1fr);
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
            max-width: 1100px;
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

        <h2 style="margin-top: 14px;">Laporan Tunggakan Santri</h2>
        <p class="subtitle">
            Dicetak pada {{ now()->format('d/m/Y H:i') }}
        </p>
    </div>

    <div class="line"></div>

    <div class="grid">
        <div class="card">
            <p>Santri</p>
            <h3>{{ number_format($jumlahSantri, 0, ',', '.') }}</h3>
        </div>

        <div class="card">
            <p>Jumlah Tagihan</p>
            <h3>{{ number_format($jumlahTagihan, 0, ',', '.') }}</h3>
        </div>

        <div class="card">
            <p>Total Tagihan</p>
            <h3>Rp{{ number_format($totalTagihan, 0, ',', '.') }}</h3>
        </div>

        <div class="card">
            <p>Dibayar</p>
            <h3>Rp{{ number_format($totalDibayar, 0, ',', '.') }}</h3>
        </div>

        <div class="card">
            <p>Sisa</p>
            <h3>Rp{{ number_format($totalSisa, 0, ',', '.') }}</h3>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Santri</th>
                <th>Kelas / Asrama</th>
                <th>Jumlah Tagihan</th>
                <th class="right">Total Tagihan</th>
                <th class="right">Dibayar</th>
                <th class="right">Sisa</th>
                <th>Rincian Tagihan</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($santris as $santri)
                <tr>
                    <td>
                        <strong>{{ $santri->nama }}</strong>
                        <br>
                        <small>NIS: {{ $santri->nis }}</small>
                    </td>

                    <td>
                        {{ $santri->kelas->nama_kelas ?? '-' }}
                        <br>
                        <small>{{ $santri->asrama->nama_asrama ?? '-' }}</small>
                    </td>

                    <td>{{ $santri->jumlah_tagihan }} tagihan</td>

                    <td class="right">
                        Rp{{ number_format($santri->total_tagihan, 0, ',', '.') }}
                    </td>

                    <td class="right">
                        Rp{{ number_format($santri->total_dibayar, 0, ',', '.') }}
                    </td>

                    <td class="right">
                        <strong>Rp{{ number_format($santri->total_sisa, 0, ',', '.') }}</strong>
                    </td>

                    <td>
                        @foreach ($santri->tagihans as $tagihan)
                            <div style="margin-bottom: 6px;">
                                <strong>{{ $tagihan->jenisPembayaran->nama ?? '-' }}</strong>
                                <br>
                                <small>
                                    Tagihan Rp{{ number_format($tagihan->nominal, 0, ',', '.') }},
                                    Dibayar Rp{{ number_format($tagihan->dibayar, 0, ',', '.') }},
                                    Sisa Rp{{ number_format($tagihan->nominal - $tagihan->dibayar, 0, ',', '.') }}
                                </small>
                            </div>
                        @endforeach
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">
                        Tidak ada data tunggakan.
                    </td>
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
