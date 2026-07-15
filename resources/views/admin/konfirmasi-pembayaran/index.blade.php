@extends('layouts.admin.app')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Konfirmasi Pembayaran</h1>
            <p class="text-sm text-gray-500 mt-1">
                Verifikasi bukti pembayaran yang dikirim oleh wali santri.
            </p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.konfirmasi-pembayaran.index') }}" class="mb-5">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <input type="text"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Cari santri / jenis pembayaran..."
                   class="md:col-span-2 block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">

            <select name="status"
                    class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">Semua Status</option>
                <option value="menunggu" @selected(request('status') == 'menunggu')>Menunggu</option>
                <option value="diterima" @selected(request('status') == 'diterima')>Diterima</option>
                <option value="ditolak" @selected(request('status') == 'ditolak')>Ditolak</option>
            </select>

            <div class="flex gap-2">
                <button class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                    Cari
                </button>

                <a href="{{ route('admin.konfirmasi-pembayaran.index') }}"
                   class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
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
                    <th class="px-4 py-3 text-left">Tagihan</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-right">Jumlah</th>
                    <th class="px-4 py-3 text-left">Metode</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($konfirmasis as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $item->santri->nama ?? '-' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $item->santri->nis ?? '-' }}
                                —
                                {{ $item->santri->kelas->nama_kelas ?? 'Tanpa kelas' }}
                            </p>
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->tagihan->jenisPembayaran->nama ?? '-' }}
                        </td>

                        <td class="px-4 py-3">
                            {{ $item->tanggal_bayar?->format('d/m/Y') }}
                        </td>

                        <td class="px-4 py-3 text-right font-medium">
                            Rp{{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                        </td>

                        <td class="px-4 py-3">
                            {{ strtoupper($item->metode) }}
                        </td>

                        <td class="px-4 py-3">
                            @if ($item->status === 'diterima')
                                <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">Diterima</span>
                            @elseif ($item->status === 'ditolak')
                                <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">Ditolak</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs bg-amber-100 text-amber-700">Menunggu</span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.konfirmasi-pembayaran.show', $item) }}"
                               class="px-3 py-2 rounded-lg bg-sky-100 text-sky-700 hover:bg-sky-200">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">
                            Belum ada konfirmasi pembayaran.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $konfirmasis->links() }}
    </div>
</div>
@endsection
