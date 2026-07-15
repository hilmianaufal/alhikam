@extends('layouts.admin.app')

@section('title', 'Tambah Tahun Ajaran')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
    $labelClass = 'block text-sm font-medium text-gray-700 mb-2';
@endphp

<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Tahun Ajaran</h1>
        <p class="text-sm text-gray-500 mt-1">Masukkan periode tahun ajaran baru.</p>
    </div>

    <form action="{{ route('admin.tahun-ajaran.store') }}" method="POST" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="{{ $labelClass }}">Nama Tahun Ajaran</label>
                <input type="text"
                       name="nama_tahun"
                       value="{{ old('nama_tahun') }}"
                       placeholder="Contoh: 2026/2027"
                       class="{{ $inputClass }}">
                @error('nama_tahun') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Semester</label>
                <select name="semester" class="{{ $inputClass }}">
                    <option value="ganjil" @selected(old('semester', 'ganjil') == 'ganjil')>Ganjil</option>
                    <option value="genap" @selected(old('semester') == 'genap')>Genap</option>
                </select>
                @error('semester') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Tanggal Mulai</label>
                <input type="date"
                       name="tanggal_mulai"
                       value="{{ old('tanggal_mulai') }}"
                       class="{{ $inputClass }}">
                @error('tanggal_mulai') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Tanggal Selesai</label>
                <input type="date"
                       name="tanggal_selesai"
                       value="{{ old('tanggal_selesai') }}"
                       class="{{ $inputClass }}">
                @error('tanggal_selesai') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Status</label>
                <select name="status" class="{{ $inputClass }}">
                    <option value="aktif" @selected(old('status', 'aktif') == 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status') == 'nonaktif')>Nonaktif</option>
                </select>
                @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center pt-7">
                <label class="inline-flex items-center gap-3">
                    <input type="checkbox"
                           name="is_active"
                           value="1"
                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                           @checked(old('is_active'))>
                    <span class="text-sm font-medium text-gray-700">
                        Jadikan tahun ajaran aktif
                    </span>
                </label>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.tahun-ajaran.index') }}"
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
