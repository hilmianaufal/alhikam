@extends('layouts.wali.app')

@section('title', 'Tagihan Saya')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';

    $bulanList = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
        4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
        10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];
@endphp

<div class="space-y-6">

    <div class="bg-gradient-to-r from-emerald-800 to-emerald-600 rounded-2xl p-6 text-white shadow-sm">
        <h1 class="text-2xl font-bold">Tagihan Saya</h1>
        <p class="text-emerald-100 mt-1">
            Lihat daftar tagihan santri dan upload bukti pembayaran.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Total Tagihan</p>
            <h2 class="text-xl font-bold text-gray-800 mt-1">
                Rp{{ number_format($totalTagihan, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Sudah Dibayar</p>
            <h2 class="text-xl font-bold text-emerald-700 mt-1">
                Rp{{ number_format($totalDibayar, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Sisa Tagihan</p>
            <h2 class="text-xl font-bold text-red-700 mt-1">
                Rp{{ number_format($sisaTagihan, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Tagihan Menunggak</p>
            <h2 class="text-xl font-bold text-red-700 mt-1">
                {{ number_format($jumlahMenunggak, 0, ',', '.') }}
            </h2>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <form method="GET" action="{{ route('wali.tagihan.index') }}">
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="{{ $inputClass }}">
                        <option value="">Semua</option>
                        <option value="menunggak" @selected(request('status') == 'menunggak')>Menunggak</option>
                        <option value="belum_lunas" @selected(request('status') == 'belum_lunas')>Belum Lunas</option>
                        <option value="sebagian" @selected(request('status') == 'sebagian')>Sebagian</option>
                        <option value="lunas" @selected(request('status') == 'lunas')>Lunas</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="bulan" class="{{ $inputClass }}">
                        <option value="">Semua Bulan</option>
                        @foreach ($bulanList as $key => $value)
                            <option value="{{ $key }}" @selected(request('bulan') == $key)>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <input type="number"
                           name="tahun"
                           value="{{ request('tahun') }}"
                           class="{{ $inputClass }}"
                           placeholder="{{ now()->year }}">
                </div>

                <div class="flex items-end gap-2">
                    <button class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                        Filter
                    </button>

                    <a href="{{ route('wali.tagihan.index') }}"
                       class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <h2 class="text-lg font-bold text-gray-800 mb-5">Daftar Tagihan</h2>

        <div class="border rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Santri</th>
                        <th class="px-4 py-3 text-left">Tagihan</th>
                        <th class="px-4 py-3 text-right">Nominal</th>
                        <th class="px-4 py-3 text-right">Dibayar</th>
                        <th class="px-4 py-3 text-right">Sisa</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($tagihans as $tagihan)
                        @php
                            $sisa = max($tagihan->nominal - $tagihan->dibayar, 0);
                            $konfirmasiTerakhir = $tagihan->konfirmasiPembayarans->first();
                        @endphp

                        <tr class="hover:bg-gray-50">
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
                                    @if ($tagihan->bulan)
                                        {{ $bulanList[$tagihan->bulan] ?? '-' }} {{ $tagihan->tahun }}
                                    @else
                                        Tanpa bulan
                                    @endif
                                </p>

                                @if ($konfirmasiTerakhir)
                                    <a href="{{ route('wali.konfirmasi-pembayaran.show', $konfirmasiTerakhir) }}"
                                       class="inline-flex mt-2 text-xs text-blue-700 hover:underline">
                                        Status bukti: {{ ucfirst($konfirmasiTerakhir->status) }}
                                    </a>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-right">
                                Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-right text-emerald-700 font-medium">
                                Rp{{ number_format($tagihan->dibayar, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-right text-red-700 font-bold">
                                Rp{{ number_format($sisa, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($tagihan->status === 'lunas')
                                    <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">Lunas</span>
                                @elseif ($tagihan->status === 'sebagian')
                                    <span class="px-3 py-1 rounded-full text-xs bg-amber-100 text-amber-700">Sebagian</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">Belum Lunas</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('wali.tagihan.show', $tagihan) }}"
                                       class="px-3 py-2 rounded-lg bg-sky-100 text-sky-700 hover:bg-sky-200">
                                        Detail
                                    </a>

                                    @if ($tagihan->status !== 'lunas')
                                        <a href="{{ route('wali.konfirmasi-pembayaran.create', ['tagihan_id' => $tagihan->id]) }}"
                                           class="px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200">
                                            Upload Bukti
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                Belum ada tagihan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $tagihans->links() }}
        </div>
    </div>

</div>
@endsection
