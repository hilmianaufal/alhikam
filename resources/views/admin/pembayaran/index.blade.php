@extends('layouts.admin.app')

@section('title', 'Pembayaran')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Pembayaran</h1>
            <p class="text-sm text-gray-500">Kelola transaksi pembayaran santri.</p>
        </div>
        @can('laporan.export')
            <a href="{{ route('admin.export.pembayaran', request()->query()) }}"
            class="px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700">
                Export Excel
            </a>
        @endcan
        <a href="{{ route('admin.pembayaran.create') }}"
           class="px-4 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
            + Input Pembayaran
        </a>
    </div>

    <form method="GET" action="{{ route('admin.pembayaran.index') }}" class="mb-5">
        <div class="flex gap-3">
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Cari kode transaksi, santri, NIS, atau jenis pembayaran..."
                   class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">

            <button class="px-5 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                Cari
            </button>

            @if ($search)
                <a href="{{ route('admin.pembayaran.index') }}"
                   class="px-5 py-2 rounded-xl bg-gray-100 hover:bg-gray-200">
                    Reset
                </a>
            @endif
        </div>
    </form>

    <div class="border rounded-xl overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Kode</th>
                    <th class="px-4 py-3 text-left">Santri</th>
                    <th class="px-4 py-3 text-left">Jenis Pembayaran</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Metode</th>
                    <th class="px-4 py-3 text-left">Bukti</th>
                    <th class="px-4 py-3 text-left">Jumlah</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($pembayarans as $pembayaran)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium">
                            {{ $pembayaran->kode_transaksi }}
                        </td>

                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $pembayaran->santri->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $pembayaran->santri->nis ?? '-' }}
                                —
                                {{ $pembayaran->santri->kelas->nama_kelas ?? 'Tanpa kelas' }}
                            </p>
                        </td>

                        <td class="px-4 py-3">
                            {{ $pembayaran->tagihan->jenisPembayaran->nama ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $pembayaran->tanggal_bayar?->format('d/m/Y') }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                                {{ ucfirst($pembayaran->metode) }}
                            </span>
                        </td>
                            <td class="px-4 py-3">
                                @if ($pembayaran->bukti_pembayaran)
                                    <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                                    target="_blank"
                                    class="px-3 py-1 rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200">
                                        Lihat
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                        <td class="px-4 py-3">
                            Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.pembayaran.struk', $pembayaran) }}"
                                    target="_blank"
                                    class="px-3 py-1 rounded-lg bg-emerald-100 text-emerald-700 hover:bg-emerald-200">
                                        Struk
                                    </a>
                                <a href="{{ route('admin.pembayaran.show', $pembayaran) }}"
                                   class="px-3 py-1 rounded-lg bg-sky-100 text-sky-700 hover:bg-sky-200">
                                    Detail
                                </a>

                                <a href="{{ route('admin.pembayaran.edit', $pembayaran) }}"
                                   class="px-3 py-1 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200">
                                    Edit
                                </a>

                                <form id="delete-pembayaran-{{ $pembayaran->id }}"
                                      action="{{ route('admin.pembayaran.destroy', $pembayaran) }}"
                                      method="POST"
                                      onsubmit="confirmDelete(event, 'delete-pembayaran-{{ $pembayaran->id }}')">
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
                        <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                            Data pembayaran belum tersedia.
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
@endsection
