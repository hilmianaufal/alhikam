@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <div class="bg-gradient-to-r from-emerald-800 to-emerald-600 rounded-2xl p-6 text-white shadow-sm">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <p class="text-emerald-100 mt-1">
            Ringkasan keuangan dan tagihan {{ \App\Helpers\AppSetting::pondokName() }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Saldo Kas</p>
                    <h2 class="text-2xl font-bold text-gray-800 mt-1">
                        Rp{{ number_format($saldoKas, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <x-heroicon-o-banknotes class="w-6 h-6 text-emerald-700" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pemasukan Bulan Ini</p>
                    <h2 class="text-2xl font-bold text-emerald-700 mt-1">
                        Rp{{ number_format($pemasukanBulanIni, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                    <x-heroicon-o-arrow-trending-up class="w-6 h-6 text-emerald-700" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Pengeluaran Bulan Ini</p>
                    <h2 class="text-2xl font-bold text-red-700 mt-1">
                        Rp{{ number_format($pengeluaranBulanIni, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                    <x-heroicon-o-arrow-trending-down class="w-6 h-6 text-red-700" />
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500">Santri Aktif</p>
                    <h2 class="text-2xl font-bold text-gray-800 mt-1">
                        {{ number_format($totalSantriAktif, 0, ',', '.') }}
                    </h2>
                </div>

                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <x-heroicon-o-academic-cap class="w-6 h-6 text-blue-700" />
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Total Pemasukan</p>
            <h2 class="text-xl font-bold text-emerald-700 mt-1">
                Rp{{ number_format($totalPemasukan, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Total Pengeluaran</p>
            <h2 class="text-xl font-bold text-red-700 mt-1">
                Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Pembayaran Hari Ini</p>
            <h2 class="text-xl font-bold text-gray-800 mt-1">
                Rp{{ number_format($pembayaranHariIni, 0, ',', '.') }}
            </h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Grafik Kas 6 Bulan</h2>
                    <p class="text-sm text-gray-500">Perbandingan pemasukan dan pengeluaran.</p>
                </div>
            </div>

            <div class="space-y-5">
                @foreach ($chartKas as $item)
                    @php
                        $pemasukanWidth = $maxChartValue > 0 ? ($item['pemasukan'] / $maxChartValue) * 100 : 0;
                        $pengeluaranWidth = $maxChartValue > 0 ? ($item['pengeluaran'] / $maxChartValue) * 100 : 0;
                    @endphp

                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="font-medium text-gray-700">{{ $item['label'] }}</span>
                            <span class="text-gray-500">
                                Rp{{ number_format($item['pemasukan'] - $item['pengeluaran'], 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <div>
                                <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full"
                                         style="width: {{ $pemasukanWidth }}%"></div>
                                </div>
                                <p class="text-xs text-emerald-700 mt-1">
                                    Pemasukan Rp{{ number_format($item['pemasukan'], 0, ',', '.') }}
                                </p>
                            </div>

                            <div>
                                <div class="h-3 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-red-500 rounded-full"
                                         style="width: {{ $pengeluaranWidth }}%"></div>
                                </div>
                                <p class="text-xs text-red-700 mt-1">
                                    Pengeluaran Rp{{ number_format($item['pengeluaran'], 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <p class="text-sm text-gray-500">Tagihan Belum Lunas</p>
                <h2 class="text-3xl font-bold text-red-700 mt-1">
                    {{ number_format($tagihanBelumLunas, 0, ',', '.') }}
                </h2>
                <p class="text-sm text-gray-500 mt-3">
                    Total tunggakan:
                </p>
                <p class="text-xl font-bold text-gray-800">
                    Rp{{ number_format($totalTunggakan, 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Aksi Cepat</h2>

                <div class="space-y-3">
                    <a href="{{ route('admin.pembayaran.create') }}"
                       class="block px-4 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800 text-center">
                        Input Pembayaran
                    </a>

                    <a href="{{ route('admin.tagihan.create') }}"
                       class="block px-4 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 text-center">
                        Generate Tagihan
                    </a>

                    <a href="{{ route('admin.laporan.index') }}"
                       class="block px-4 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 text-center">
                        Lihat Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800">Pembayaran Terbaru</h2>

                <a href="{{ route('admin.pembayaran.index') }}"
                   class="text-sm text-emerald-700 hover:underline">
                    Lihat semua
                </a>
            </div>

            <div class="divide-y">
                @forelse ($pembayaranTerbaru as $pembayaran)
                    <div class="py-3 flex items-center justify-between gap-4">
                        <div>
                            <p class="font-medium text-gray-800">
                                {{ $pembayaran->santri->nama ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $pembayaran->tagihan->jenisPembayaran->nama ?? '-' }}
                                —
                                {{ $pembayaran->tanggal_bayar?->format('d/m/Y') }}
                            </p>
                        </div>

                        <p class="font-bold text-emerald-700 whitespace-nowrap">
                            Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                        </p>
                    </div>
                @empty
                    <div class="py-6 text-center text-gray-500 text-sm">
                        Belum ada pembayaran.
                    </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-bold text-gray-800">Tagihan Belum Lunas</h2>

                <a href="{{ route('admin.tagihan.index') }}"
                   class="text-sm text-emerald-700 hover:underline">
                    Lihat semua
                </a>
            </div>

            <div class="divide-y">
                @forelse ($tagihanTerbaru as $tagihan)
                    <div class="py-3 flex items-center justify-between gap-4">
                        <div>
                            <p class="font-medium text-gray-800">
                                {{ $tagihan->santri->nama ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $tagihan->jenisPembayaran->nama ?? '-' }}
                                —
                                {{ $tagihan->santri->kelas->nama_kelas ?? 'Tanpa kelas' }}
                            </p>
                        </div>

                        <p class="font-bold text-red-700 whitespace-nowrap">
                            Rp{{ number_format($tagihan->nominal - $tagihan->dibayar, 0, ',', '.') }}
                        </p>
                    </div>
                @empty
                    <div class="py-6 text-center text-gray-500 text-sm">
                        Tidak ada tagihan belum lunas.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
