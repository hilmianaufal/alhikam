@extends('layouts.admin.app')

@section('title', 'Tagihan Santri')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tagihan Santri</h1>
            <p class="text-sm text-gray-500">Kelola tagihan pembayaran santri.</p>
        </div>
    @can('laporan.export')
        <a href="{{ route('admin.export.tagihan', request()->query()) }}"
        class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
            Export Excel
        </a>
    @endcan
        <a href="{{ route('admin.tagihan.create') }}"
           class="px-4 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
            + Generate Tagihan
        </a>
    </div>

    <form method="GET" action="{{ route('admin.tagihan.index') }}" class="mb-5">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari santri / jenis pembayaran..."
                   class="md:col-span-2 block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">

            <select name="status"
                    class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Semua Status</option>
                <option value="belum_lunas" @selected(request('status') == 'belum_lunas')>Belum Lunas</option>
                <option value="sebagian" @selected(request('status') == 'sebagian')>Sebagian</option>
                <option value="lunas" @selected(request('status') == 'lunas')>Lunas</option>
            </select>

            <input type="number"
                   name="tahun"
                   value="{{ request('tahun') }}"
                   placeholder="Tahun"
                   class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">

            <div class="flex gap-2">
                <button class="px-5 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                    Cari
                </button>

                <a href="{{ route('admin.tagihan.index') }}"
                   class="px-5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200">
                    Reset
                </a>
            </div>
        </div>
    </form>

    <div class="border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Jenis</th>
                    <th class="px-4 py-3 text-left">Periode</th>
                    <th class="px-4 py-3 text-left">Nominal</th>
                    <th class="px-4 py-3 text-left">Dibayar</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($tagihans as $tagihan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $tagihan->santri->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $tagihan->santri->nis ?? '-' }}
                                —
                                {{ $tagihan->santri->kelas->nama_kelas ?? 'Tanpa kelas' }}
                            </p>
                        </td>

                        <td class="px-4 py-3">
                            {{ $tagihan->jenisPembayaran->nama ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            @php
                                $bulan = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
                                    4 => 'April', 5 => 'Mei', 6 => 'Juni',
                                    7 => 'Juli', 8 => 'Agustus', 9 => 'September',
                                    10 => 'Oktober', 11 => 'November', 12 => 'Desember',
                                ];
                            @endphp

                            {{ $tagihan->bulan ? $bulan[$tagihan->bulan] : '-' }}
                            {{ $tagihan->tahun }}
                        </td>

                        <td class="px-4 py-3">
                            Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-3">
                            Rp{{ number_format($tagihan->dibayar, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-3">
                            @if ($tagihan->status == 'lunas')
                                <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">Lunas</span>
                            @elseif ($tagihan->status == 'sebagian')
                                <span class="px-3 py-1 rounded-full text-xs bg-amber-100 text-amber-700">Sebagian</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">Belum Lunas</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.tagihan.show', $tagihan) }}"
                                   class="px-3 py-1 rounded-lg bg-sky-100 text-sky-700 hover:bg-sky-200">
                                    Detail
                                </a>

                                <a href="{{ route('admin.tagihan.edit', $tagihan) }}"
                                   class="px-3 py-1 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200">
                                    Edit
                                </a>

                                <form id="delete-tagihan-{{ $tagihan->id }}"
                                      action="{{ route('admin.tagihan.destroy', $tagihan) }}"
                                      method="POST"
                                      onsubmit="confirmDelete(event, 'delete-tagihan-{{ $tagihan->id }}')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="px-3 py-1 rounded-lg bg-red-100 text-red-700 hover:bg-red-200">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            Data tagihan belum tersedia.
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
@endsection
