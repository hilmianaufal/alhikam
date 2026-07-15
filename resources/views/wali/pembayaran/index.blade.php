@extends('layouts.wali.app')

@section('title', 'Riwayat Pembayaran')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
@endphp

<div class="space-y-6">

    <div class="bg-gradient-to-r from-emerald-800 to-emerald-600 rounded-2xl p-6 text-white shadow-sm">
        <h1 class="text-2xl font-bold">Riwayat Pembayaran</h1>
        <p class="text-emerald-100 mt-1">
            Lihat pembayaran yang sudah diterima oleh admin.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Total Pembayaran</p>
            <h2 class="text-2xl font-bold text-emerald-700 mt-1">
                Rp{{ number_format($totalPembayaran, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Jumlah Transaksi</p>
            <h2 class="text-2xl font-bold text-gray-800 mt-1">
                {{ number_format($jumlahTransaksi, 0, ',', '.') }}
            </h2>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <form method="GET" action="{{ route('wali.pembayaran.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Santri</label>
                    <select name="santri_id" class="{{ $inputClass }}">
                        <option value="">Semua Santri</option>
                        @foreach ($santris as $santri)
                            <option value="{{ $santri->id }}" @selected(request('santri_id') == $santri->id)>
                                {{ $santri->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Metode</label>
                    <select name="metode" class="{{ $inputClass }}">
                        <option value="">Semua Metode</option>
                        <option value="tunai" @selected(request('metode') == 'tunai')>Tunai</option>
                        <option value="transfer" @selected(request('metode') == 'transfer')>Transfer</option>
                        <option value="qris" @selected(request('metode') == 'qris')>QRIS</option>
                        <option value="lainnya" @selected(request('metode') == 'lainnya')>Lainnya</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                    <input type="date"
                           name="tanggal_mulai"
                           value="{{ request('tanggal_mulai') }}"
                           class="{{ $inputClass }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
                    <input type="date"
                           name="tanggal_selesai"
                           value="{{ request('tanggal_selesai') }}"
                           class="{{ $inputClass }}">
                </div>

                <div class="flex items-end gap-2">
                    <button class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                        Filter
                    </button>

                    <a href="{{ route('wali.pembayaran.index') }}"
                       class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-5">Daftar Pembayaran</h2>

        <div class="border rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Santri</th>
                        <th class="px-4 py-3 text-left">Pembayaran</th>
                        <th class="px-4 py-3 text-left">Metode</th>
                        <th class="px-4 py-3 text-right">Jumlah</th>
                        <th class="px-4 py-3 text-left">Bukti</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($pembayarans as $pembayaran)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $pembayaran->tanggal_bayar?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 font-medium">{{ $pembayaran->kode_transaksi }}</td>
                            <td class="px-4 py-3">
                                <p class="font-medium">{{ $pembayaran->santri->nama ?? '-' }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ $pembayaran->santri->kelas->nama_kelas ?? '-' }}
                                </p>
                            </td>
                            <td class="px-4 py-3">{{ $pembayaran->tagihan->jenisPembayaran->nama ?? '-' }}</td>
                            <td class="px-4 py-3">{{ ucfirst($pembayaran->metode) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-emerald-700">
                                Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($pembayaran->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                                       target="_blank"
                                       class="px-3 py-2 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                Belum ada riwayat pembayaran.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $pembayarans->links() }}
        </div>
    </div>

</div>
@endsection
