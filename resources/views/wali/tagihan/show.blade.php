@extends('layouts.wali.app')

@section('title', 'Detail Tagihan')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Tagihan</h1>
                <p class="text-sm text-gray-500 mt-1">
                    {{ $tagihan->santri->nama ?? '-' }} - {{ $tagihan->jenisPembayaran->nama ?? '-' }}
                </p>
            </div>

            <div class="flex gap-3">
                @if ($tagihan->status !== 'lunas')
                    <a href="{{ route('wali.konfirmasi-pembayaran.create', ['tagihan_id' => $tagihan->id]) }}"
                       class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                        Upload Bukti
                    </a>
                @endif

                <a href="{{ route('wali.tagihan.index') }}"
                   class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Total Tagihan</p>
            <h2 class="text-xl font-bold text-gray-800 mt-1">
                Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Dibayar</p>
            <h2 class="text-xl font-bold text-emerald-700 mt-1">
                Rp{{ number_format($tagihan->dibayar, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Sisa</p>
            <h2 class="text-xl font-bold text-red-700 mt-1">
                Rp{{ number_format($sisaTagihan, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Status</p>
            <h2 class="text-xl font-bold text-gray-800 mt-1">
                {{ str_replace('_', ' ', ucfirst($tagihan->status)) }}
            </h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Data Santri</h2>

            <div class="space-y-3 text-sm">
                <p><span class="text-gray-500">Nama:</span> <strong>{{ $tagihan->santri->nama ?? '-' }}</strong></p>
                <p><span class="text-gray-500">NIS:</span> <strong>{{ $tagihan->santri->nis ?? '-' }}</strong></p>
                <p><span class="text-gray-500">Kelas:</span> <strong>{{ $tagihan->santri->kelas->nama_kelas ?? '-' }}</strong></p>
                <p><span class="text-gray-500">Asrama:</span> <strong>{{ $tagihan->santri->asrama->nama_asrama ?? '-' }}</strong></p>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Informasi Tagihan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Jenis Pembayaran</p>
                    <p class="font-semibold">{{ $tagihan->jenisPembayaran->nama ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Tahun Ajaran</p>
                    <p class="font-semibold">{{ $tagihan->tahunAjaran->nama_tahun ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Bulan / Tahun</p>
                    <p class="font-semibold">
                        {{ $tagihan->bulan ?? '-' }} / {{ $tagihan->tahun ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Jatuh Tempo</p>
                    <p class="font-semibold">
                        {{ $tagihan->tanggal_jatuh_tempo ? $tagihan->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500">Keterangan</p>
                    <p class="font-semibold">{{ $tagihan->keterangan ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-5">Riwayat Pembayaran Tagihan Ini</h2>

        <div class="border rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Metode</th>
                        <th class="px-4 py-3 text-right">Jumlah</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($tagihan->pembayarans as $pembayaran)
                        <tr>
                            <td class="px-4 py-3">{{ $pembayaran->tanggal_bayar?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-medium">{{ $pembayaran->kode_transaksi }}</td>
                            <td class="px-4 py-3">{{ ucfirst($pembayaran->metode) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-emerald-700">
                                Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                                Belum ada pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-5">Status Bukti Pembayaran</h2>

        <div class="border rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-right">Jumlah</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Catatan Admin</th>
                        <th class="px-4 py-3 text-right">Detail</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($tagihan->konfirmasiPembayarans as $konfirmasi)
                        <tr>
                            <td class="px-4 py-3">{{ $konfirmasi->tanggal_bayar?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-right font-medium">
                                Rp{{ number_format($konfirmasi->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">{{ ucfirst($konfirmasi->status) }}</td>
                            <td class="px-4 py-3">{{ $konfirmasi->catatan_admin ?? '-' }}</td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('wali.konfirmasi-pembayaran.show', $konfirmasi) }}"
                                   class="px-3 py-2 rounded-lg bg-sky-100 text-sky-700 hover:bg-sky-200">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                Belum ada bukti pembayaran yang dikirim.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
