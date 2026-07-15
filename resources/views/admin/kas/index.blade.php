@extends('layouts.admin.app')

@section('title', 'Kas Pondok')

@section('content')
<div class="space-y-6">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Saldo Kas</p>
            <h2 class="text-2xl font-bold text-gray-800 mt-1">
                Rp{{ number_format($saldo, 0, ',', '.') }}
            </h2>
        </div>

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
    </div>

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Kas Pondok</h1>
                <p class="text-sm text-gray-500">Kelola pemasukan dan pengeluaran kas pondok.</p>
            </div>
            @can('laporan.export')
                <a href="{{ route('admin.export.kas', request()->query()) }}"
                class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                    Export Excel
                </a>
            @endcan
            <a href="{{ route('admin.kas.create') }}"
               class="px-4 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                + Tambah Transaksi
            </a>
        </div>

        <form method="GET" action="{{ route('admin.kas.index') }}" class="mb-5">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Cari kode, kategori, atau keterangan..."
                       class="md:col-span-2 block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">

                <select name="tipe"
                        class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="">Semua Tipe</option>
                    <option value="pemasukan" @selected(request('tipe') == 'pemasukan')>Pemasukan</option>
                    <option value="pengeluaran" @selected(request('tipe') == 'pengeluaran')>Pengeluaran</option>
                </select>

                <div class="flex gap-2">
                    <button class="px-5 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                        Cari
                    </button>

                    <a href="{{ route('admin.kas.index') }}"
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
                        <th class="px-4 py-3 text-left">Kode</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Tipe</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Nominal</th>
                        <th class="px-4 py-3 text-left">Sumber</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y">
                    @forelse ($kasTransactions as $kas)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium">
                                {{ $kas->kode }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $kas->tanggal?->format('d/m/Y') }}
                            </td>

                            <td class="px-4 py-3">
                                @if ($kas->tipe === 'pemasukan')
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
                                <p class="font-medium">{{ $kas->kategori }}</p>
                                @if ($kas->keterangan)
                                    <p class="text-xs text-gray-500 mt-1">{{ $kas->keterangan }}</p>
                                @endif
                            </td>

                            <td class="px-4 py-3 font-medium">
                                Rp{{ number_format($kas->nominal, 0, ',', '.') }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700">
                                    {{ ucfirst($kas->sumber) }}
                                </span>
                            </td>

                            <td class="px-4 py-3 text-right">
                                @if ($kas->sumber === 'manual')
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.kas.edit', $kas) }}"
                                           class="px-3 py-1 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200">
                                            Edit
                                        </a>

                                        <form id="delete-kas-{{ $kas->id }}"
                                              action="{{ route('admin.kas.destroy', $kas) }}"
                                              method="POST"
                                              onsubmit="confirmDelete(event, 'delete-kas-{{ $kas->id }}')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="px-3 py-1 rounded-lg bg-red-100 text-red-700 hover:bg-red-200">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">Otomatis</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                                Data kas belum tersedia.
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
</div>
@endsection
