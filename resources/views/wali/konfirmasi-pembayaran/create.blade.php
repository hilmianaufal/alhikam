@extends('layouts.wali.app')

@section('title', 'Upload Bukti Pembayaran')

@section('content')
    @php
        $inputClass =
            'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
    @endphp

    <div class="space-y-6">

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h1 class="text-2xl font-bold text-gray-800">Upload Bukti Pembayaran</h1>
            <p class="text-sm text-gray-500 mt-1">
                Kirim bukti transfer atau QRIS untuk diverifikasi oleh admin pondok.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-5">Form Konfirmasi Pembayaran</h2>

                <form action="{{ route('wali.konfirmasi-pembayaran.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Tagihan</label>
                        <select name="tagihan_id" class="{{ $inputClass }}">
                            <option value="">Pilih Tagihan Belum Lunas</option>
                            @foreach ($tagihans as $tagihan)
                                <option value="{{ $tagihan->id }}" @selected(old('tagihan_id', request('tagihan_id')) == $tagihan->id)>
                                    {{ $tagihan->santri->nama ?? '-' }}
                                    -
                                    {{ $tagihan->jenisPembayaran->nama ?? '-' }}
                                    |
                                    Sisa: Rp{{ number_format($tagihan->nominal - $tagihan->dibayar, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('tagihan_id')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bayar</label>
                            <input type="date" name="tanggal_bayar"
                                value="{{ old('tanggal_bayar', now()->format('Y-m-d')) }}" class="{{ $inputClass }}">
                            @error('tanggal_bayar')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Bayar</label>
                            <input type="number" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}" min="1"
                                class="{{ $inputClass }}">
                            @error('jumlah_bayar')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Metode</label>
                            <select name="metode" class="{{ $inputClass }}">
                                <option value="transfer" @selected(old('metode') == 'transfer')>Transfer</option>
                                <option value="qris" @selected(old('metode') == 'qris')>QRIS</option>
                                <option value="lainnya" @selected(old('metode') == 'lainnya')>Lainnya</option>
                            </select>
                            @error('metode')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.webp,.pdf"
                            class="{{ $inputClass }}">
                        <p class="text-xs text-gray-500 mt-2">
                            Format: JPG, PNG, WEBP, atau PDF. Maksimal 2MB.
                        </p>
                        @error('bukti_pembayaran')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="4" class="{{ $inputClass }}"
                            placeholder="Contoh: Transfer dari rekening BCA atas nama ...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                            Kirim Bukti Pembayaran
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Petunjuk</h2>

                <div class="space-y-4 text-sm text-gray-600">
                    <div class="border rounded-xl p-4">
                        <p class="font-semibold text-gray-800">1. Pilih tagihan</p>
                        <p>Pilih tagihan anak yang ingin dibayar.</p>
                    </div>

                    <div class="border rounded-xl p-4">
                        <p class="font-semibold text-gray-800">2. Upload bukti</p>
                        <p>Upload foto/screenshot transfer atau bukti QRIS.</p>
                    </div>

                    <div class="border rounded-xl p-4">
                        <p class="font-semibold text-gray-800">3. Tunggu verifikasi</p>
                        <p>Pembayaran akan masuk setelah disetujui admin.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Riwayat Konfirmasi</h2>

            <div class="border rounded-xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="px-4 py-3 text-left">Tanggal</th>
                            <th class="px-4 py-3 text-left">Santri</th>
                            <th class="px-4 py-3 text-left">Tagihan</th>
                            <th class="px-4 py-3 text-right">Jumlah</th>
                            <th class="px-4 py-3 text-left">Status</th>
                            <th class="px-4 py-3 text-left">Catatan</th>
                            <th class="px-4 py-3 text-right">Detail</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        @forelse ($riwayatKonfirmasi as $item)
                            <tr>
                                <td class="px-4 py-3">{{ $item->tanggal_bayar?->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">{{ $item->santri->nama ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $item->tagihan->jenisPembayaran->nama ?? '-' }}</td>
                                <td class="px-4 py-3 text-right font-medium">
                                    Rp{{ number_format($item->jumlah_bayar, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3">
                                    @if ($item->status === 'diterima')
                                        <span
                                            class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">Diterima</span>
                                    @elseif ($item->status === 'ditolak')
                                        <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">Ditolak</span>
                                    @else
                                        <span
                                            class="px-3 py-1 rounded-full text-xs bg-amber-100 text-amber-700">Menunggu</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{{ $item->catatan_admin ?? '-' }}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('wali.konfirmasi-pembayaran.show', $item) }}"
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
        </div>
    </div>
@endsection
