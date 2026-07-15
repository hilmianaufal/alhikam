@extends('layouts.admin.app')

@section('title', 'Kartu Tagihan Santri')

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

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Kartu Tagihan Santri</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Lihat tagihan dan riwayat pembayaran per santri.
                </p>
            </div>

            @if ($selectedSantri)
                <a href="{{ route('admin.laporan.kartu-santri.print', array_merge(['santri' => $selectedSantri->id], request()->except('santri_id'))) }}"
                   target="_blank"
                   class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800 text-center">
                    Cetak Kartu
                </a>
            @endif
        </div>

        <form method="GET" action="{{ route('admin.laporan.kartu-santri') }}" class="mt-6">
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

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Santri</label>
                    <select name="santri_id" class="{{ $inputClass }}">
                        <option value="">Pilih Santri</option>
                        @foreach ($santris as $santri)
                            <option value="{{ $santri->id }}" @selected(request('santri_id') == $santri->id)>
                                {{ $santri->nama }} — NIS: {{ $santri->nis }} — {{ $santri->kelas->nama_kelas ?? 'Tanpa kelas' }}
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status Tagihan</label>
                    <select name="status" class="{{ $inputClass }}">
                        <option value="">Semua Status</option>
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
                           placeholder="{{ now()->year }}"
                           class="{{ $inputClass }}">
                </div>

                <div class="md:col-span-3 flex items-end justify-end gap-3">
                    <a href="{{ route('admin.laporan.kartu-santri') }}"
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

    @if (! $selectedSantri)
        <div class="bg-white rounded-2xl shadow-sm border p-8 text-center">
            <div class="w-16 h-16 mx-auto rounded-full bg-emerald-100 flex items-center justify-center mb-4">
                <x-heroicon-o-identification class="w-8 h-8 text-emerald-700" />
            </div>

            <h2 class="text-xl font-bold text-gray-800">Pilih santri terlebih dahulu</h2>
            <p class="text-gray-500 mt-2">
                Cari nama santri, lalu pilih dari daftar untuk melihat kartu tagihannya.
            </p>
        </div>
    @else

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-5">Data Santri</h2>

                <div class="space-y-4 text-sm">
                    <div>
                        <p class="text-gray-500">Nama Santri</p>
                        <p class="font-semibold text-gray-800">{{ $selectedSantri->nama }}</p>
                    </div>

                    <div>
                        <p class="text-gray-500">NIS / NISN</p>
                        <p class="font-semibold text-gray-800">
                            {{ $selectedSantri->nis }} / {{ $selectedSantri->nisn ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Kelas</p>
                        <p class="font-semibold text-gray-800">
                            {{ $selectedSantri->kelas->nama_kelas ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Asrama</p>
                        <p class="font-semibold text-gray-800">
                            {{ $selectedSantri->asrama->nama_asrama ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-500">Akun Wali</p>
                        <p class="font-semibold text-gray-800">
                            {{ $selectedSantri->user->name ?? '-' }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $selectedSantri->user->email ?? '' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-4 gap-5">
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

                <div class="md:col-span-4 bg-white rounded-2xl shadow-sm border p-5">
                    <p class="text-sm text-gray-500">Jumlah Transaksi Pembayaran</p>
                    <h2 class="text-2xl font-bold text-gray-800 mt-1">
                        {{ number_format($jumlahPembayaran, 0, ',', '.') }} transaksi
                    </h2>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Daftar Tagihan</h2>

            <div class="border rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Jenis</th>
                            <th class="px-4 py-3 text-left">Periode</th>
                            <th class="px-4 py-3 text-right">Nominal</th>
                            <th class="px-4 py-3 text-right">Dibayar</th>
                            <th class="px-4 py-3 text-right">Sisa</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Jatuh Tempo</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse ($tagihans as $tagihan)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium">
                                    {{ $tagihan->jenisPembayaran->nama ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $tagihan->tahunAjaran->nama_tahun ?? '-' }}
                                    @if ($tagihan->bulan)
                                        <br>
                                        <span class="text-xs text-gray-500">
                                            {{ $bulanList[$tagihan->bulan] ?? '-' }} {{ $tagihan->tahun }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-right">
                                    Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-right text-emerald-700 font-medium">
                                    Rp{{ number_format($tagihan->dibayar, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3 text-right text-red-700 font-bold">
                                    Rp{{ number_format($tagihan->nominal - $tagihan->dibayar, 0, ',', '.') }}
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

                                <td class="px-4 py-3">
                                    {{ $tagihan->tanggal_jatuh_tempo ? $tagihan->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    Belum ada tagihan sesuai filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Riwayat Pembayaran</h2>

            <div class="border rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Kode</th>
                            <th class="px-4 py-3 text-left">Pembayaran</th>
                            <th class="px-4 py-3 text-left">Metode</th>
                            <th class="px-4 py-3 text-right">Jumlah</th>
                            <th class="px-4 py-3 text-left">Kasir</th>
                            <th class="px-4 py-3 text-right">Struk</th>
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
                                    {{ $pembayaran->tagihan->jenisPembayaran->nama ?? '-' }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ ucfirst($pembayaran->metode) }}
                                </td>

                                <td class="px-4 py-3 text-right font-bold text-emerald-700">
                                    Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                                </td>

                                <td class="px-4 py-3">
                                    {{ $pembayaran->user->name ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.pembayaran.struk', $pembayaran) }}"
                                       target="_blank"
                                       class="px-3 py-2 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200">
                                        Struk
                                    </a>
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
        </div>

    @endif

</div>
@endsection
