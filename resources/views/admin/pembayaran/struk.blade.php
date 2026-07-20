<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Struk Pembayaran - {{ $pembayaran->kode_transaksi }}
    </title>

    @php
        $kotaTandaTangan =
            \App\Models\Setting::getValue('signature_city') ?: 'Subang';

        $namaTandaTangan =
            \App\Models\Setting::getValue('signature_name')
            ?: ($pembayaran->user->name ?? 'Petugas');

        $jabatanTandaTangan =
            \App\Models\Setting::getValue('signature_position')
            ?: 'Bendahara';
    @endphp

    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.45;
            color: #111827;
            background: #f3f4f6;
            padding: 20px;
        }

        .struk {
            width: 100%;
            max-width: 380px;
            margin: 0 auto;
            padding: 20px;
            overflow: hidden;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.08);
        }

        .text-center {
            text-align: center;
        }

        .logo {
            display: block;
            width: 60px;
            height: 60px;
            margin: 0 auto 8px;
            object-fit: contain;
        }

        .title {
            font-size: 18px;
            font-weight: 700;
            line-height: 1.3;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .subtitle {
            margin-top: 3px;
            font-size: 11px;
            line-height: 1.45;
            color: #6b7280;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .address {
            max-width: 100%;
            padding: 0 4px;
            white-space: normal;
        }

        .receipt-label {
            margin-top: 7px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #374151;
        }

        .line {
            margin: 14px 0;
            border-top: 1px dashed #9ca3af;
        }

        .row {
            display: grid;
            grid-template-columns: minmax(90px, 38%) minmax(0, 1fr);
            gap: 10px;
            margin-bottom: 8px;
            align-items: start;
        }

        .label {
            min-width: 0;
            color: #6b7280;
            overflow-wrap: anywhere;
        }

        .value {
            min-width: 0;
            font-weight: 700;
            text-align: right;
            overflow-wrap: anywhere;
            word-break: break-word;
            white-space: normal;
        }

        .total {
            font-size: 15px;
            font-weight: 700;
        }

        .note {
            padding: 10px;
            margin-top: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background: #f9fafb;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .note-label {
            margin-bottom: 3px;
            font-size: 10px;
            color: #6b7280;
        }

        .note-value {
            font-size: 11px;
            color: #374151;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .signature {
            display: flex;
            justify-content: flex-end;
            margin-top: 24px;
        }

        .signature-box {
            width: 180px;
            max-width: 58%;
            text-align: center;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .signature-city,
        .signature-position {
            font-size: 11px;
        }

        .signature-position {
            margin-top: 3px;
        }

        .signature-space {
            height: 55px;
        }

        .signature-name {
            padding-top: 4px;
            font-size: 11px;
            font-weight: 700;
            border-top: 1px solid #111827;
        }

        .footer {
            margin-top: 18px;
            font-size: 10px;
            line-height: 1.5;
            color: #6b7280;
            text-align: center;
            overflow-wrap: anywhere;
        }

        .btn-print {
            display: block;
            width: 100%;
            max-width: 380px;
            margin: 0 auto 20px;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #047857;
            color: #ffffff;
            font-weight: 700;
            cursor: pointer;
        }

        @page {
            size: 80mm auto;
            margin: 4mm;
        }

        @media print {
            html,
            body {
                width: auto;
                min-width: 0;
                margin: 0;
                padding: 0;
                background: #ffffff;
            }

            .btn-print {
                display: none;
            }

            .struk {
                width: 72mm;
                max-width: 72mm;
                margin: 0 auto;
                padding: 3mm;
                border-radius: 0;
                box-shadow: none;
                overflow: visible;
            }

            .title,
            .subtitle,
            .label,
            .value,
            .signature-box,
            .footer {
                overflow-wrap: anywhere;
                word-break: break-word;
            }
        }
    </style>
</head>

<body>

    <button type="button" onclick="window.print()" class="btn-print">
        Cetak Struk
    </button>

    <div class="struk">

        <div class="text-center">
            @if (\App\Helpers\AppSetting::logo())
                <img
                    src="{{ \App\Helpers\AppSetting::logo() }}"
                    alt="Logo"
                    class="logo"
                >
            @endif

            <div class="title">
                {{ \App\Helpers\AppSetting::appName() }}
            </div>

            <div class="subtitle">
                {{ \App\Helpers\AppSetting::pondokName() }}
            </div>

            @if (\App\Helpers\AppSetting::address())
                <div class="subtitle address">
                    {!! nl2br(e(\App\Helpers\AppSetting::address())) !!}
                </div>
            @endif

            @if (\App\Helpers\AppSetting::phone())
                <div class="subtitle">
                    Telp: {{ \App\Helpers\AppSetting::phone() }}
                </div>
            @endif

            <div class="receipt-label">
                Bukti Pembayaran Santri
            </div>
        </div>

        <div class="line"></div>

        <div class="row">
            <div class="label">Kode Transaksi</div>
            <div class="value">
                {{ $pembayaran->kode_transaksi }}
            </div>
        </div>

        <div class="row">
            <div class="label">Tanggal</div>
            <div class="value">
                {{ $pembayaran->tanggal_bayar?->format('d/m/Y') ?? '-' }}
            </div>
        </div>

        <div class="row">
            <div class="label">Petugas</div>
            <div class="value">
                {{ $pembayaran->user->name ?? '-' }}
            </div>
        </div>

        <div class="line"></div>

        <div class="row">
            <div class="label">Nama Santri</div>
            <div class="value">
                {{ $pembayaran->santri->nama ?? '-' }}
            </div>
        </div>

        <div class="row">
            <div class="label">NIS</div>
            <div class="value">
                {{ $pembayaran->santri->nis ?? '-' }}
            </div>
        </div>

        <div class="row">
            <div class="label">Kelas</div>
            <div class="value">
                {{ $pembayaran->santri->kelas->nama_kelas ?? '-' }}
            </div>
        </div>

        <div class="row">
            <div class="label">Asrama</div>
            <div class="value">
                {{ $pembayaran->santri->asrama->nama_asrama ?? '-' }}
            </div>
        </div>

        <div class="line"></div>

        <div class="row">
            <div class="label">Pembayaran</div>
            <div class="value">
                {{ $pembayaran->tagihan->jenisPembayaran->nama ?? '-' }}
            </div>
        </div>

        <div class="row">
            <div class="label">Tahun Ajaran</div>
            <div class="value">
                {{ $pembayaran->tagihan->tahunAjaran->nama_tahun ?? '-' }}
            </div>
        </div>

        <div class="row">
            <div class="label">Metode</div>
            <div class="value">
                {{ strtoupper($pembayaran->metode ?? '-') }}
            </div>
        </div>

        <div class="row">
            <div class="label">Total Tagihan</div>
            <div class="value">
                Rp{{ number_format($pembayaran->tagihan->nominal ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <div class="row">
            <div class="label">Total Terbayar</div>
            <div class="value">
                Rp{{ number_format($pembayaran->tagihan->dibayar ?? 0, 0, ',', '.') }}
            </div>
        </div>

        <div class="row">
            <div class="label">Sisa Tagihan</div>
            <div class="value">
                Rp{{ number_format(
                    max(
                        ($pembayaran->tagihan->nominal ?? 0)
                        - ($pembayaran->tagihan->dibayar ?? 0),
                        0
                    ),
                    0,
                    ',',
                    '.'
                ) }}
            </div>
        </div>

        <div class="line"></div>

        <div class="row total">
            <div>Jumlah Bayar</div>
            <div class="value">
                Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
            </div>
        </div>

        @if ($pembayaran->keterangan)
            <div class="note">
                <div class="note-label">
                    Keterangan
                </div>

                <div class="note-value">
                    {{ $pembayaran->keterangan }}
                </div>
            </div>
        @endif

        <div class="signature">
            <div class="signature-box">
                <div class="signature-city">
                    {{ $kotaTandaTangan }},
                    {{ now()->format('d/m/Y') }}
                </div>

                <div class="signature-position">
                    {{ $jabatanTandaTangan }}
                </div>

                <div class="signature-space"></div>

                <div class="signature-name">
                    {{ $namaTandaTangan }}
                </div>
            </div>
        </div>

        <div class="footer">
            Terima kasih atas pembayarannya.<br>
            Struk ini merupakan bukti pembayaran yang sah.

            @if (\App\Helpers\AppSetting::footerText())
                <br>
                {{ \App\Helpers\AppSetting::footerText() }}
            @endif
        </div>

    </div>

</body>

</html>
