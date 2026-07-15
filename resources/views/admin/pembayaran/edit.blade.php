@extends('layouts.admin.app')

@section('title', 'Edit Pembayaran')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
    $labelClass = 'block text-sm font-medium text-gray-700 mb-2';
@endphp

<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ $pembayaran->kode_transaksi }}
            -
            {{ $pembayaran->santri->nama ?? '-' }}
        </p>
    </div>

    <form action="{{ route('admin.pembayaran.update', $pembayaran) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="{{ $labelClass }}">Tanggal Bayar</label>
                <input type="date"
                       name="tanggal_bayar"
                       value="{{ old('tanggal_bayar', $pembayaran->tanggal_bayar?->format('Y-m-d')) }}"
                       class="{{ $inputClass }}">
                @error('tanggal_bayar') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Jumlah Bayar</label>
                <input type="number"
                       name="jumlah_bayar"
                       value="{{ old('jumlah_bayar', $pembayaran->jumlah_bayar) }}"
                       min="1"
                       class="{{ $inputClass }}">
                @error('jumlah_bayar') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Metode</label>
                <select name="metode" class="{{ $inputClass }}">
                    <option value="tunai" @selected(old('metode', $pembayaran->metode) == 'tunai')>Tunai</option>
                    <option value="transfer" @selected(old('metode', $pembayaran->metode) == 'transfer')>Transfer</option>
                    <option value="qris" @selected(old('metode', $pembayaran->metode) == 'qris')>QRIS</option>
                    <option value="lainnya" @selected(old('metode', $pembayaran->metode) == 'lainnya')>Lainnya</option>
                </select>
                @error('metode') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>
        <div>
            <label class="{{ $labelClass }}">Bukti Pembayaran</label>

            @if ($pembayaran->bukti_pembayaran)
                <div class="mb-3">
                    <a href="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}"
                    target="_blank"
                    class="inline-flex px-4 py-2 rounded-xl bg-blue-100 text-blue-700 hover:bg-blue-200 text-sm">
                        Lihat Bukti Saat Ini
                    </a>
                </div>
            @endif

            <input type="file"
                name="bukti_pembayaran"
                accept=".jpg,.jpeg,.png,.webp,.pdf"
                class="{{ $inputClass }}">

            <p class="text-xs text-gray-500 mt-2">
                Kosongkan jika tidak ingin mengganti bukti pembayaran.
            </p>

            @error('bukti_pembayaran') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="{{ $labelClass }}">Keterangan</label>
            <textarea name="keterangan" rows="4" class="{{ $inputClass }}">{{ old('keterangan', $pembayaran->keterangan) }}</textarea>
            @error('keterangan') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.pembayaran.index') }}"
               class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                Batal
            </a>

            <button type="submit"
                    class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
