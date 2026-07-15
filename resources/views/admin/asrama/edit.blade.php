@extends('layouts.admin.app')

@section('title', 'Edit Asrama')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
    $labelClass = 'block text-sm font-medium text-gray-700 mb-2';
@endphp

<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Asrama</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui data asrama.</p>
    </div>

    <form action="{{ route('admin.asrama.update', $asrama) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="{{ $labelClass }}">Nama Asrama</label>
                <input type="text" name="nama_asrama" value="{{ old('nama_asrama', $asrama->nama_asrama) }}" class="{{ $inputClass }}">
                @error('nama_asrama') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Kode Asrama</label>
                <input type="text" name="kode_asrama" value="{{ old('kode_asrama', $asrama->kode_asrama) }}" class="{{ $inputClass }}">
                @error('kode_asrama') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Musyrif</label>
                <input type="text" name="musyrif" value="{{ old('musyrif', $asrama->musyrif) }}" class="{{ $inputClass }}">
                @error('musyrif') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Kapasitas</label>
                <input type="text" name="kapasitas" value="{{ old('kapasitas', $asrama->kapasitas) }}" class="{{ $inputClass }}">
                @error('kapasitas') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Status</label>
                <select name="status" class="{{ $inputClass }}">
                    <option value="aktif" @selected(old('status', $asrama->status) == 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status', $asrama->status) == 'nonaktif')>Nonaktif</option>
                </select>
                @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.asrama.index') }}"
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
