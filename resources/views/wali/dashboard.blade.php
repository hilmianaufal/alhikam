@extends('layouts.wali.app')

@section('title', 'Dashboard Wali Santri')

@section('content')
<div class="space-y-6">

    <div class="bg-gradient-to-r from-emerald-800 to-emerald-600 rounded-2xl p-6 text-white shadow-sm">
        <h1 class="text-2xl font-bold">Dashboard Wali Santri</h1>
        <p class="text-emerald-100 mt-1">
            Pantau tagihan dan pembayaran santri secara mudah.
        </p>
    </div>

    @if ($santris->isEmpty())
        <div class="bg-white rounded-2xl shadow-sm border p-8 text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-amber-100 flex items-center justify-center mb-4">
                <x-heroicon-o-exclamation-triangle class="w-8 h-8 text-amber-600" />
            </div>

            <h2 class="text-xl font-bold text-gray-800">Akun belum terhubung dengan santri</h2>
            <p class="text-gray-500 mt-2">
                Silakan hubungi admin pondok untuk menghubungkan akun wali ini dengan data santri.
            </p>
        </div>
    @else

        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <p class="text-sm text-gray-500">Jumlah Santri</p>
                <h2 class="text-2xl font-bold text-gray-800 mt-1">
                    {{ $santris->count() }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <p class="text-sm text-gray-500">Total Tagihan</p>
                <h2 class="text-2xl font-bold text-gray-800 mt-1">
                    Rp{{ number_format($totalTagihan, 0, ',', '.') }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <p class="text-sm text-gray-500">Sudah Dibayar</p>
                <h2 class="text-2xl font-bold text-emerald-700 mt-1">
                    Rp{{ number_format($totalDibayar, 0, ',', '.') }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border p-5">
                <p class="text-sm text-gray-500">Sisa Tagihan</p>
                <h2 class="text-2xl font-bold text-red-700 mt-1">
                    Rp{{ number_format($sisaTagihan, 0, ',', '.') }}
                </h2>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Data Santri</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach ($santris as $santri)
                    <div class="border rounded-2xl p-5">
                        <div class="flex items-start gap-4">
                            <div class="w-14 h-14 rounded-xl bg-emerald-100 flex items-center justify-center">
                                <x-heroicon-o-academic-cap class="w-7 h-7 text-emerald-700" />
                            </div>

                            <div>
                                <h3 class="font-bold text-gray-800">
                                    {{ $santri->nama }}
                                </h3>

                                <p class="text-sm text-gray-500">
                                    NIS: {{ $santri->nis }}
                                </p>

                                <div class="mt-3 text-sm text-gray-600 space-y-1">
                                    <p>Kelas: {{ $santri->kelas->nama_kelas ?? '-' }}</p>
                                    <p>Asrama: {{ $santri->asrama->nama_asrama ?? '-' }}</p>
                                    <p>Status: {{ ucfirst($santri->status) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Tagihan Belum Lunas</h2>
                        <p class="text-sm text-gray-500">
                            {{ $jumlahBelumLunas }} tagihan belum selesai.
                        </p>
                    </div>
                </div>

                <div class="border rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-3 text-left">Santri</th>
                                <th class="px-4 py-3 text-left">Tagihan</th>
                                <th class="px-4 py-3 text-right">Sisa</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @forelse ($tagihanBelumLunas as $tagihan)
                                <tr>
                                    <td class="px-4 py-3">
                                        <p class="font-medium">{{ $tagihan->santri->nama ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $tagihan->santri->kelas->nama_kelas ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-4 py-3">
                                        <p class="font-medium">
                                            {{ $tagihan->jenisPembayaran->nama ?? '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            Total Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}
                                            —
                                            Dibayar Rp{{ number_format($tagihan->dibayar, 0, ',', '.') }}
                                        </p>
                                    </td>

                                    <td class="px-4 py-3 text-right font-bold text-red-700">
                                        Rp{{ number_format($tagihan->nominal - $tagihan->dibayar, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                        Tidak ada tagihan belum lunas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Riwayat Pembayaran</h2>

                <div class="border rounded-xl overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-3 text-left">Tanggal</th>
                                <th class="px-4 py-3 text-left">Pembayaran</th>
                                <th class="px-4 py-3 text-right">Jumlah</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y">
                            @forelse ($pembayaranTerbaru as $pembayaran)
                                <tr>
                                    <td class="px-4 py-3">
                                        {{ $pembayaran->tanggal_bayar?->format('d/m/Y') }}
                                    </td>

                                    <td class="px-4 py-3">
                                        <p class="font-medium">
                                            {{ $pembayaran->tagihan->jenisPembayaran->nama ?? '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $pembayaran->santri->nama ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-4 py-3 text-right font-bold text-emerald-700">
                                        Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-6 text-center text-gray-500">
                                        Belum ada pembayaran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection
