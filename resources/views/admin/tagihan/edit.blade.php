@extends('layouts.admin.app')

@section('title', 'Edit Tagihan')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
    $labelClass = 'block text-sm font-medium text-gray-700 mb-2';
@endphp

<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Tagihan</h1>
        <p class="text-sm text-gray-500 mt-1">
            {{ $tagihan->santri->nama ?? '-' }} - {{ $tagihan->jenisPembayaran->nama ?? '-' }}
        </p>
    </div>

    <form action="{{ route('admin.tagihan.update', $tagihan) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="{{ $labelClass }}">Nominal</label>
                <input type="number"
                       name="nominal"
                       value="{{ old('nominal', $tagihan->nominal) }}"
                       min="0"
                       class="{{ $inputClass }}">
                @error('nominal') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Dibayar</label>
                <input type="number"
                       name="dibayar"
                       value="{{ old('dibayar', $tagihan->dibayar) }}"
                       min="0"
                       class="{{ $inputClass }}">
                @error('dibayar') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Tanggal Jatuh Tempo</label>
                <input type="date"
                       name="tanggal_jatuh_tempo"
                       value="{{ old('tanggal_jatuh_tempo', $tagihan->tanggal_jatuh_tempo?->format('Y-m-d')) }}"
                       class="{{ $inputClass }}">
                @error('tanggal_jatuh_tempo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Status</label>
                <select name="status" class="{{ $inputClass }}">
                    <option value="belum_lunas" @selected(old('status', $tagihan->status) == 'belum_lunas')>Belum Lunas</option>
                    <option value="sebagian" @selected(old('status', $tagihan->status) == 'sebagian')>Sebagian</option>
                    <option value="lunas" @selected(old('status', $tagihan->status) == 'lunas')>Lunas</option>
                </select>
                @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="{{ $labelClass }}">Keterangan</label>
            <textarea name="keterangan" rows="4" class="{{ $inputClass }}">{{ old('keterangan', $tagihan->keterangan) }}</textarea>
            @error('keterangan') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.tagihan.index') }}"
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
