@extends('layouts.admin.app')

@section('title', 'Detail Tagihan')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Tagihan</h1>
                <p class="text-sm text-gray-500">Informasi lengkap tagihan santri.</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.tagihan.edit', $tagihan) }}"
                   class="px-4 py-2 rounded-xl bg-amber-500 text-white hover:bg-amber-600">
                    Edit
                </a>

                <a href="{{ route('admin.tagihan.index') }}"
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
                    <p class="font-medium">{{ $tagihan->santri->nama ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">NIS</p>
                    <p class="font-medium">{{ $tagihan->santri->nis ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Kelas</p>
                    <p class="font-medium">{{ $tagihan->santri->kelas->nama_kelas ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Asrama</p>
                    <p class="font-medium">{{ $tagihan->santri->asrama->nama_asrama ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
            <h3 class="font-semibold text-lg mb-4">Tagihan</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Jenis Pembayaran</p>
                    <p class="font-medium">{{ $tagihan->jenisPembayaran->nama ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Tahun Ajaran</p>
                    <p class="font-medium">{{ $tagihan->tahunAjaran->nama_tahun ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Nominal</p>
                    <p class="font-medium">Rp{{ number_format($tagihan->nominal, 0, ',', '.') }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Dibayar</p>
                    <p class="font-medium">Rp{{ number_format($tagihan->dibayar, 0, ',', '.') }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Sisa</p>
                    <p class="font-medium">Rp{{ number_format($tagihan->sisa, 0, ',', '.') }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Jatuh Tempo</p>
                    <p class="font-medium">
                        {{ $tagihan->tanggal_jatuh_tempo ? $tagihan->tanggal_jatuh_tempo->format('d/m/Y') : '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Status</p>
                    <p class="font-medium">{{ str_replace('_', ' ', ucfirst($tagihan->status)) }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Keterangan</p>
                    <p class="font-medium">{{ $tagihan->keterangan ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
