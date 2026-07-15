@extends('layouts.admin.app')

@section('title', 'Laporan Keuangan')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Laporan Keuangan</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Periode {{ \Carbon\Carbon::parse($tanggalMulai)->format('d/m/Y') }}
                    sampai
                    {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d/m/Y') }}
                </p>
            </div>

            <a href="{{ route('admin.laporan.print', request()->query()) }}"
               target="_blank"
               class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800 text-center">
                Cetak / PDF
            </a>
        </div>

        <form method="GET" action="{{ route('admin.laporan.index') }}" class="mt-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date"
                           name="tanggal_mulai"
                           value="{{ $tanggalMulai }}"
                           class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                    <input type="date"
                           name="tanggal_selesai"
                           value="{{ $tanggalSelesai }}"
                           class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                </div>

                <div class="flex items-end gap-2 md:col-span-2">
                    <button class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                        Tampilkan
                    </button>

                    <a href="{{ route('admin.laporan.index') }}"
                       class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Total Pemasukan</p>
            <h2 class="text-2xl font-bold text-emerald-700 mt-1">
                Rp{{ number_format($totalPemasukan, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Total Pengeluaran</p>
            <h2 class="text-2xl font-bold text-red-700 mt-1">
                Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Saldo Periode</p>
            <h2 class="text-2xl font-bold {{ $saldoPeriode >= 0 ? 'text-gray-800' : 'text-red-700' }} mt-1">
                Rp{{ number_format($saldoPeriode, 0, ',', '.') }}
            </h2>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Total Tagihan</p>
            <h2 class="text-xl font-bold text-gray-800 mt-1">
                Rp{{ number_format($totalTagihan, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Dibayar</p>
            <h2 class="text-xl font-bold text-emerald-700 mt-1">
                Rp{{ number_format($totalDibayarTagihan, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Sisa Tagihan</p>
            <h2 class="text-xl font-bold text-red-700 mt-1">
                Rp{{ number_format($sisaTagihan, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Transaksi Pembayaran</p>
            <h2 class="text-xl font-bold text-gray-800 mt-1">
                {{ $jumlahPembayaran }} Transaksi
            </h2>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Riwayat Kas Periode Ini</h2>

        <div class="border rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Tipe</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Metode</th>
                        <th class="px-4 py-3 text-right">Nominal</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($kasTransactions as $kas)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                {{ $kas->tanggal?->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3 font-medium">
                                {{ $kas->kode }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($kas->tipe == 'pemasukan')
                                    <span class="px-3 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                        Pemasukan
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                        Pengeluaran
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                {{ $kas->kategori }}
                            </td>

                            <td class="px-4 py-3">
                                {{ ucfirst($kas->metode) }}
                            </td>

                            <td class="px-4 py-3 text-right font-medium">
                                Rp{{ number_format($kas->nominal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                Belum ada transaksi kas pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $kasTransactions->links() }}
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-4">Pembayaran Terbaru Periode Ini</h2>

        <div class="border rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Santri</th>
                        <th class="px-4 py-3 text-left">Jenis</th>
                        <th class="px-4 py-3 text-right">Jumlah</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($pembayarans as $pembayaran)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                {{ $pembayaran->tanggal_bayar?->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3 font-medium">
                                {{ $pembayaran->kode_transaksi }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $pembayaran->santri->nama ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $pembayaran->tagihan->jenisPembayaran->nama ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-right font-medium">
                                Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                Belum ada pembayaran pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
