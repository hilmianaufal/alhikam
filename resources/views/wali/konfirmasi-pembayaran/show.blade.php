@extends('layouts.wali.app')

@section('title', 'Status Bukti Bayar')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Status Bukti Pembayaran</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Detail verifikasi bukti pembayaran yang dikirim.
                </p>
            </div>

            <a href="{{ route('wali.konfirmasi-pembayaran.create') }}"
               class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Status</p>
            <h2 class="text-2xl font-bold mt-1
                {{ $konfirmasiPembayaran->status === 'diterima' ? 'text-green-700' : '' }}
                {{ $konfirmasiPembayaran->status === 'ditolak' ? 'text-red-700' : '' }}
                {{ $konfirmasiPembayaran->status === 'menunggu' ? 'text-amber-700' : '' }}">
                {{ ucfirst($konfirmasiPembayaran->status) }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Jumlah Bayar</p>
            <h2 class="text-2xl font-bold text-emerald-700 mt-1">
                Rp{{ number_format($konfirmasiPembayaran->jumlah_bayar, 0, ',', '.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-5">
            <p class="text-sm text-gray-500">Tanggal Bayar</p>
            <h2 class="text-2xl font-bold text-gray-800 mt-1">
                {{ $konfirmasiPembayaran->tanggal_bayar?->format('d/m/Y') }}
            </h2>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Data Santri</h2>

            <div class="space-y-3 text-sm">
                <p><span class="text-gray-500">Nama:</span> <strong>{{ $konfirmasiPembayaran->santri->nama ?? '-' }}</strong></p>
                <p><span class="text-gray-500">NIS:</span> <strong>{{ $konfirmasiPembayaran->santri->nis ?? '-' }}</strong></p>
                <p><span class="text-gray-500">Kelas:</span> <strong>{{ $konfirmasiPembayaran->santri->kelas->nama_kelas ?? '-' }}</strong></p>
                <p><span class="text-gray-500">Asrama:</span> <strong>{{ $konfirmasiPembayaran->santri->asrama->nama_asrama ?? '-' }}</strong></p>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Detail Konfirmasi</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-gray-500">Tagihan</p>
                    <p class="font-semibold">{{ $konfirmasiPembayaran->tagihan->jenisPembayaran->nama ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Metode</p>
                    <p class="font-semibold">{{ strtoupper($konfirmasiPembayaran->metode) }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Keterangan</p>
                    <p class="font-semibold">{{ $konfirmasiPembayaran->keterangan ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Diverifikasi Oleh</p>
                    <p class="font-semibold">{{ $konfirmasiPembayaran->verifier->name ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Waktu Verifikasi</p>
                    <p class="font-semibold">
                        {{ $konfirmasiPembayaran->verified_at?->format('d/m/Y H:i') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Bukti Pembayaran</p>
                    <a href="{{ asset('storage/' . $konfirmasiPembayaran->bukti_pembayaran) }}"
                       target="_blank"
                       class="inline-flex mt-1 px-4 py-2 rounded-xl bg-blue-100 text-blue-700 hover:bg-blue-200">
                        Lihat Bukti
                    </a>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500">Catatan Admin</p>
                    <p class="font-semibold">{{ $konfirmasiPembayaran->catatan_admin ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    @if ($konfirmasiPembayaran->status === 'diterima' && $konfirmasiPembayaran->pembayaran)
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6">
            <h2 class="text-lg font-bold text-green-800">Pembayaran Sudah Diterima</h2>
            <p class="text-sm text-green-700 mt-1">
                Bukti pembayaran sudah disetujui admin dan masuk ke riwayat pembayaran.
            </p>

            <div class="mt-4">
                <a href="{{ route('wali.pembayaran.index') }}"
                   class="px-5 py-3 rounded-xl bg-green-700 text-white hover:bg-green-800">
                    Lihat Riwayat Pembayaran
                </a>
            </div>
        </div>
    @endif

</div>
@endsection
