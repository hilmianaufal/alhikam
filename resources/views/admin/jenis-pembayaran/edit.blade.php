@extends('layouts.admin.app')

@section('title', 'Edit Jenis Pembayaran')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
    $labelClass = 'block text-sm font-medium text-gray-700 mb-2';
@endphp

<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Jenis Pembayaran</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui jenis pembayaran.</p>
    </div>

    <form action="{{ route('admin.jenis-pembayaran.update', $jenisPembayaran) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="{{ $labelClass }}">Kode</label>
                <input type="text"
                       name="kode"
                       value="{{ old('kode', $jenisPembayaran->kode) }}"
                       class="{{ $inputClass }}">
                @error('kode') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Nama Pembayaran</label>
                <input type="text"
                       name="nama"
                       value="{{ old('nama', $jenisPembayaran->nama) }}"
                       class="{{ $inputClass }}">
                @error('nama') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Nominal</label>
                <input type="number"
                       name="nominal"
                       value="{{ old('nominal', $jenisPembayaran->nominal) }}"
                       min="0"
                       class="{{ $inputClass }}">
                @error('nominal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Tipe Pembayaran</label>
                <select name="tipe" class="{{ $inputClass }}">
                    <option value="bulanan" @selected(old('tipe', $jenisPembayaran->tipe) == 'bulanan')>Bulanan</option>
                    <option value="tahunan" @selected(old('tipe', $jenisPembayaran->tipe) == 'tahunan')>Tahunan</option>
                    <option value="sekali" @selected(old('tipe', $jenisPembayaran->tipe) == 'sekali')>Sekali Bayar</option>
                    <option value="bebas" @selected(old('tipe', $jenisPembayaran->tipe) == 'bebas')>Nominal Bebas</option>
                </select>
                @error('tipe') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Status</label>
                <select name="status" class="{{ $inputClass }}">
                    <option value="aktif" @selected(old('status', $jenisPembayaran->status) == 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status', $jenisPembayaran->status) == 'nonaktif')>Nonaktif</option>
                </select>
                @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="{{ $labelClass }}">Deskripsi</label>
            <textarea name="deskripsi"
                      rows="4"
                      class="{{ $inputClass }}">{{ old('deskripsi', $jenisPembayaran->deskripsi) }}</textarea>
            @error('deskripsi') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.jenis-pembayaran.index') }}"
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
