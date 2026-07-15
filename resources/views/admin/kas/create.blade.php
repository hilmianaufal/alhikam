@extends('layouts.admin.app')

@section('title', 'Tambah Transaksi Kas')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
    $labelClass = 'block text-sm font-medium text-gray-700 mb-2';
@endphp

<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Transaksi Kas</h1>
        <p class="text-sm text-gray-500 mt-1">Masukkan transaksi pemasukan atau pengeluaran manual.</p>
    </div>

    <form action="{{ route('admin.kas.store') }}" method="POST" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="{{ $labelClass }}">Tanggal</label>
                <input type="date"
                       name="tanggal"
                       value="{{ old('tanggal', now()->format('Y-m-d')) }}"
                       class="{{ $inputClass }}">
                @error('tanggal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Tipe</label>
                <select name="tipe" class="{{ $inputClass }}">
                    <option value="pemasukan" @selected(old('tipe') == 'pemasukan')>Pemasukan</option>
                    <option value="pengeluaran" @selected(old('tipe') == 'pengeluaran')>Pengeluaran</option>
                </select>
                @error('tipe') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Kategori</label>
                <input type="text"
                       name="kategori"
                       value="{{ old('kategori') }}"
                       placeholder="Contoh: Donasi / Operasional / Konsumsi"
                       class="{{ $inputClass }}">
                @error('kategori') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Nominal</label>
                <input type="number"
                       name="nominal"
                       value="{{ old('nominal') }}"
                       min="1"
                       class="{{ $inputClass }}">
                @error('nominal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Metode</label>
                <select name="metode" class="{{ $inputClass }}">
                    <option value="tunai" @selected(old('metode', 'tunai') == 'tunai')>Tunai</option>
                    <option value="transfer" @selected(old('metode') == 'transfer')>Transfer</option>
                    <option value="qris" @selected(old('metode') == 'qris')>QRIS</option>
                    <option value="lainnya" @selected(old('metode') == 'lainnya')>Lainnya</option>
                </select>
                @error('metode') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="{{ $labelClass }}">Keterangan</label>
            <textarea name="keterangan"
                      rows="4"
                      class="{{ $inputClass }}"
                      placeholder="Catatan transaksi">{{ old('keterangan') }}</textarea>
            @error('keterangan') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.kas.index') }}"
               class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                Batal
            </a>

            <button type="submit"
                    class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
