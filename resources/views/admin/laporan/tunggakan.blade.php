@extends('layouts.admin.app')

@section('title', 'Laporan Tunggakan')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';

    $bulanList = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
        4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September',
        10 => 'Oktober', 11 => 'November', 12 => 'Desember',
    ];

    $statusLabels = [
        'menunggak' => 'Menunggak',
        'semua' => 'Semua Status',
        'belum_lunas' => 'Belum Lunas',
        'sebagian' => 'Sebagian',
        'lunas' => 'Lunas',
    ];
@endphp

<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Laporan Tunggakan Santri</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Melihat daftar santri yang masih memiliki tagihan belum lunas.
                </p>
            </div>
            <div class="flex flex-col md:flex-row gap-3">
                @can('laporan.export')
                    <a href="{{ route('admin.export.tunggakan', request()->query()) }}"
                    class="px-5 py-3 rounded-xl bg-blue-600 text-white hover:bg-blue-700 text-center">
                        Export Excel
                    </a>
                @endcan

                <a href="{{ route('admin.laporan.tunggakan.print', request()->query()) }}"
                target="_blank"
                class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800 text-center">
                    Cetak / PDF
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.laporan.tunggakan') }}" class="mt-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Santri</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Nama, NIS, atau NISN..."
                           class="{{ $inputClass }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="kelas_id" class="{{ $inputClass }}">
                        <option value="">Semua Kelas</option>
                        @foreach ($kelas as $item)
                            <option value="{{ $item->id }}" @selected(request('kelas_id') == $item->id)>
                                {{ $item->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Asrama</label>
                    <select name="asrama_id" class="{{ $inputClass }}">
                        <option value="">Semua Asrama</option>
                        @foreach ($asramas as $item)
                            <option value="{{ $item->id }}" @selected(request('asrama_id') == $item->id)>
                                {{ $item->nama_asrama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Ajaran</label>
                    <select name="tahun_ajaran_id" class="{{ $inputClass }}">
                        <option value="">Semua Tahun Ajaran</option>
                        @foreach ($tahunAjarans as $item)
                            <option value="{{ $item->id }}" @selected(request('tahun_ajaran_id') == $item->id)>
                                {{ $item->nama_tahun }} - {{ ucfirst($item->semester) }}
                            </option>
                        @endforeach
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
                           placeholder="{{ now()->year }}"
                           class="{{ $inputClass }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="{{ $inputClass }}">
                        <option value="menunggak" @selected($status == 'menunggak')>Menunggak</option>
                        <option value="semua" @selected($status == 'semua')>Semua Status</option>
                        <option value="belum_lunas" @selected($status == 'belum_lunas')>Belum Lunas</option>
                        <option value="sebagian" @selected($status == 'sebagian')>Sebagian</option>
                        <option value="lunas" @selected($status == 'lunas')>Lunas</option>
                    </select>
                </div>

                <div class="md:col-span-4 flex justify-end gap-3">
                    <a href="{{ route('admin.laporan.tunggakan') }}"
                       class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                        Reset
                    </a>

                    <button class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                        Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Santri</p>
            <h2 class="text-2xl font-bold text-gray-800 mt-1">
                {{ number_format($jumlahSantri, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Jumlah Tagihan</p>
            <h2 class="text-2xl font-bold text-gray-800 mt-1">
                {{ number_format($jumlahTagihan, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Total Tagihan</p>
            <h2 class="text-xl font-bold text-gray-800 mt-1">
                Rp{{ number_format($totalTagihan, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Dibayar</p>
            <h2 class="text-xl font-bold text-emerald-700 mt-1">
                Rp{{ number_format($totalDibayar, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Sisa Tunggakan</p>
            <h2 class="text-xl font-bold text-red-700 mt-1">
                Rp{{ number_format($totalSisa, 0, ',', '.') }}
            </h2>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-lg font-bold text-gray-800">Daftar Tunggakan</h2>
                <p class="text-sm text-gray-500">
                    Status: {{ $statusLabels[$status] ?? ucfirst($status) }}
                </p>
            </div>
        </div>

        <div class="border rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">Santri</th>
                        <th class="px-4 py-3 text-left">Kelas / Asrama</th>
                        <th class="px-4 py-3 text-left">Jumlah Tagihan</th>
                        <th class="px-4 py-3 text-right">Total Tagihan</th>
                        <th class="px-4 py-3 text-right">Dibayar</th>
                        <th class="px-4 py-3 text-right">Sisa</th>
                        <th class="px-4 py-3 text-left">Rincian</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($santris as $santri)
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-800">{{ $santri->nama }}</p>
                                <p class="text-xs text-gray-500">NIS: {{ $santri->nis }}</p>
                            </td>

                            <td class="px-4 py-3">
                                <p>{{ $santri->kelas->nama_kelas ?? '-' }}</p>
                                <p class="text-xs text-gray-500">{{ $santri->asrama->nama_asrama ?? '-' }}</p>
                            </td>

                            <td class="px-4 py-3">
                                {{ $santri->jumlah_tagihan }} tagihan
                            </td>

                            <td class="px-4 py-3 text-right">
                                Rp{{ number_format($santri->total_tagihan, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-right text-emerald-700 font-medium">
                                Rp{{ number_format($santri->total_dibayar, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3 text-right text-red-700 font-bold">
                                Rp{{ number_format($santri->total_sisa, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3">
                                <div class="space-y-2">
                                    @foreach ($santri->tagihans->take(3) as $tagihan)
                                        <div class="text-xs bg-gray-50 border rounded-lg p-2">
                                            <p class="font-medium text-gray-800">
                                                {{ $tagihan->jenisPembayaran->nama ?? '-' }}
                                            </p>
                                            <p class="text-gray-500">
                                                Sisa:
                                                Rp{{ number_format($tagihan->nominal - $tagihan->dibayar, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    @endforeach

                                    @if ($santri->tagihans->count() > 3)
                                        <p class="text-xs text-gray-500">
                                            +{{ $santri->tagihans->count() - 3 }} tagihan lainnya
                                        </p>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                Tidak ada data tunggakan sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $santris->links() }}
        </div>
    </div>

</div>
@endsection
