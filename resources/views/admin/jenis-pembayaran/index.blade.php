@extends('layouts.admin.app')

@section('title', 'Jenis Pembayaran')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Jenis Pembayaran</h1>
            <p class="text-sm text-gray-500">Kelola jenis biaya dan pembayaran santri.</p>
        </div>

        <a href="{{ route('admin.jenis-pembayaran.create') }}"
           class="px-4 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
            + Tambah Jenis
        </a>
    </div>

    <form method="GET" action="{{ route('admin.jenis-pembayaran.index') }}" class="mb-5">
        <div class="flex gap-3">
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Cari kode, nama, atau tipe pembayaran..."
                   class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">

            <button class="px-5 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                Cari
            </button>

            @if ($search)
                <a href="{{ route('admin.jenis-pembayaran.index') }}"
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
                    <th class="px-4 py-3 text-left">Nama Pembayaran</th>
                    <th class="px-4 py-3 text-left">Nominal</th>
                    <th class="px-4 py-3 text-left">Tipe</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($jenisPembayarans as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            {{ $item->kode ?? '-' }}
                        </td>

                        <td class="px-4 py-3 font-medium">
                            {{ $item->nama }}
                            @if ($item->deskripsi)
                                <p class="text-xs text-gray-500 mt-1">{{ $item->deskripsi }}</p>
                            @endif
                        </td>

                        <td class="px-4 py-3">
                            Rp{{ number_format($item->nominal, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700">
                                {{ ucfirst($item->tipe) }}
                            </span>
                        </td>

                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs
                                {{ $item->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        <td class="px-4 py-3 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.jenis-pembayaran.edit', $item) }}"
                                   class="px-3 py-1 rounded-lg bg-amber-100 text-amber-700 hover:bg-amber-200">
                                    Edit
                                </a>

                                <form id="delete-jenis-pembayaran-{{ $item->id }}"
                                      action="{{ route('admin.jenis-pembayaran.destroy', $item) }}"
                                      method="POST"
                                      onsubmit="confirmDelete(event, 'delete-jenis-pembayaran-{{ $item->id }}')">
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
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            Data jenis pembayaran belum tersedia.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $jenisPembayarans->links() }}
    </div>
</div>
@endsection
