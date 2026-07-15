@extends('layouts.admin.app')

@section('title', 'Detail Konfirmasi Pembayaran')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Konfirmasi Pembayaran</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Cek bukti pembayaran sebelum diterima.
                </p>
            </div>

            <a href="{{ route('admin.konfirmasi-pembayaran.index') }}"
               class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 text-center">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Data Santri</h2>

            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-gray-500">Nama</p>
                    <p class="font-semibold">{{ $konfirmasiPembayaran->santri->nama ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">NIS</p>
                    <p class="font-semibold">{{ $konfirmasiPembayaran->santri->nis ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Kelas</p>
                    <p class="font-semibold">{{ $konfirmasiPembayaran->santri->kelas->nama_kelas ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Asrama</p>
                    <p class="font-semibold">{{ $konfirmasiPembayaran->santri->asrama->nama_asrama ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Detail Pembayaran</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-gray-500">Jenis Pembayaran</p>
                    <p class="font-semibold">{{ $konfirmasiPembayaran->tagihan->jenisPembayaran->nama ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Tahun Ajaran</p>
                    <p class="font-semibold">{{ $konfirmasiPembayaran->tagihan->tahunAjaran->nama_tahun ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Tanggal Bayar</p>
                    <p class="font-semibold">{{ $konfirmasiPembayaran->tanggal_bayar?->format('d/m/Y') }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Metode</p>
                    <p class="font-semibold">{{ strtoupper($konfirmasiPembayaran->metode) }}</p>
                </div>

                <div>
                    <p class="text-gray-500">Jumlah Bayar</p>
                    <p class="font-semibold text-emerald-700">
                        Rp{{ number_format($konfirmasiPembayaran->jumlah_bayar, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-500">Status</p>
                    <p class="font-semibold">{{ ucfirst($konfirmasiPembayaran->status) }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500">Keterangan Wali</p>
                    <p class="font-semibold">{{ $konfirmasiPembayaran->keterangan ?? '-' }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-gray-500 mb-2">Bukti Pembayaran</p>

                    <a href="{{ asset('storage/' . $konfirmasiPembayaran->bukti_pembayaran) }}"
                       target="_blank"
                       class="inline-flex px-4 py-2 rounded-xl bg-blue-100 text-blue-700 hover:bg-blue-200">
                        Lihat Bukti
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($konfirmasiPembayaran->status === 'menunggu')
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Terima Pembayaran</h2>
                <p class="text-sm text-gray-500 mb-5">
                    Jika bukti valid, pembayaran akan otomatis masuk ke data pembayaran dan kas pondok.
                </p>

                <form action="{{ route('admin.konfirmasi-pembayaran.approve', $konfirmasiPembayaran) }}"
                      method="POST">
                    @csrf
                    @method('PATCH')

                    <button class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                        Terima Pembayaran
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Tolak Pembayaran</h2>

                <form action="{{ route('admin.konfirmasi-pembayaran.reject', $konfirmasiPembayaran) }}"
                      method="POST"
                      class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan
                        </label>

                        <textarea name="catatan_admin"
                                  rows="4"
                                  required
                                  class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-red-500 focus:ring-red-500"
                                  placeholder="Contoh: Nominal tidak sesuai / bukti tidak terbaca"></textarea>

                        @error('catatan_admin')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button class="px-5 py-3 rounded-xl bg-red-600 text-white hover:bg-red-700">
                        Tolak Pembayaran
                    </button>
                </form>
            </div>
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Hasil Verifikasi</h2>

            <div class="text-sm space-y-3">
                <p>
                    <span class="text-gray-500">Status:</span>
                    <span class="font-semibold">{{ ucfirst($konfirmasiPembayaran->status) }}</span>
                </p>

                <p>
                    <span class="text-gray-500">Diverifikasi Oleh:</span>
                    <span class="font-semibold">{{ $konfirmasiPembayaran->verifier->name ?? '-' }}</span>
                </p>

                <p>
                    <span class="text-gray-500">Waktu Verifikasi:</span>
                    <span class="font-semibold">{{ $konfirmasiPembayaran->verified_at?->format('d/m/Y H:i') ?? '-' }}</span>
                </p>

                <p>
                    <span class="text-gray-500">Catatan:</span>
                    <span class="font-semibold">{{ $konfirmasiPembayaran->catatan_admin ?? '-' }}</span>
                </p>
            </div>
        </div>
    @endif

</div>
@endsection
