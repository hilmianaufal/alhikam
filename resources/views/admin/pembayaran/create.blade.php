@extends('layouts.admin.app')

@section('title', 'Input Pembayaran')

@section('content')
    @php
        $inputClass =
            'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
        $labelClass = 'block text-sm font-medium text-gray-700 mb-2';
    @endphp

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Input Pembayaran</h1>
            <p class="text-sm text-gray-500 mt-1">Masukkan pembayaran tagihan santri.</p>
        </div>

        <form action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="{{ $labelClass }}">Pilih Tagihan</label>
                <select name="tagihan_id" class="{{ $inputClass }}">
                    <option value="">Pilih Tagihan Belum Lunas</option>
                    @foreach ($tagihans as $tagihan)
                        <option value="{{ $tagihan->id }}" @selected(old('tagihan_id') == $tagihan->id)>
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
                    <label class="{{ $labelClass }}">Tanggal Bayar</label>
                    <input type="date" name="tanggal_bayar" value="{{ old('tanggal_bayar', now()->format('Y-m-d')) }}"
                        class="{{ $inputClass }}">
                    @error('tanggal_bayar')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Jumlah Bayar</label>
                    <input type="number" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}" min="1"
                        class="{{ $inputClass }}">
                    @error('jumlah_bayar')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Metode</label>
                    <select name="metode" class="{{ $inputClass }}">
                        <option value="tunai" @selected(old('metode', 'tunai') == 'tunai')>Tunai</option>
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
                <label class="{{ $labelClass }}">Bukti Pembayaran</label>
                <input type="file" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.webp,.pdf"
                    class="{{ $inputClass }}">
                <p class="text-xs text-gray-500 mt-2">
                    Upload bukti transfer / QRIS. Format: JPG, PNG, WEBP, atau PDF. Maksimal 2MB.
                </p>
                @error('bukti_pembayaran')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>


            <div>
                <label class="{{ $labelClass }}">Keterangan</label>
                <textarea name="keterangan" rows="4" class="{{ $inputClass }}" placeholder="Catatan pembayaran">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.pembayaran.index') }}"
                    class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                    Batal
                </a>

                <button type="submit" class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                    Simpan Pembayaran
                </button>
            </div>
        </form>
    </div>
@endsection
