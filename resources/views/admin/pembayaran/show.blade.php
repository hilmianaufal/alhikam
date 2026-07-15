@extends('layouts.admin.app')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Pembayaran</h1>
                <p class="text-sm text-gray-500">{{ $pembayaran->kode_transaksi }}</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.pembayaran.struk', $pembayaran) }}"
                    target="_blank"
                    class="px-4 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700">
                        Cetak Struk
                    </a>
                <a href="{{ route('admin.pembayaran.edit', $pembayaran) }}"
                   class="px-4 py-2 rounded-xl bg-amber-500 text-white hover:bg-amber-600">
                    Edit
                </a>

                <a href="{{ route('admin.pembayaran.index') }}"
                   class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h3 class="font-semibold text-lg mb-4">Santri</h3>

            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-500">Nama</p>
                    <p class="font-medium">{{ $pembayaran->santri->nama ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">NIS</p>
                    <p class="font-medium">{{ $pembayaran->santri->nis ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Kelas</p>
                    <p class="font-medium">{{ $pembayaran->santri->kelas->nama_kelas ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Asrama</p>
                    <p class="font-medium">{{ $pembayaran->santri->asrama->nama_asrama ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
            <h3 class="font-semibold text-lg mb-4">Transaksi</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Kode Transaksi</p>
                    <p class="font-medium">{{ $pembayaran->kode_transaksi }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Jenis Pembayaran</p>
                    <p class="font-medium">{{ $pembayaran->tagihan->jenisPembayaran->nama ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Tanggal Bayar</p>
                    <p class="font-medium">{{ $pembayaran->tanggal_bayar?->format('d/m/Y') }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Metode</p>
                    <p class="font-medium">{{ ucfirst($pembayaran->metode) }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Jumlah Bayar</p>
                    <p class="font-medium">Rp{{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Diinput Oleh</p>
                    <p class="font-medium">{{ $pembayaran->user->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Bukti Pembayaran</p>

                    @if ($pembayaran->bukti_pembayaran)
                        <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                        target="_blank"
                        class="inline-flex mt-1 px-4 py-2 rounded-xl bg-blue-100 text-blue-700 hover:bg-blue-200">
                            Lihat Bukti
                        </a>
                    @else
                        <p class="font-medium">-</p>
                    @endif
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-500">Keterangan</p>
                    <p class="font-medium">{{ $pembayaran->keterangan ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
