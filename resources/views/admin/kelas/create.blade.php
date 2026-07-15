@extends('layouts.admin.app')

@section('title', 'Tambah Kelas')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Kelas</h1>
        <p class="text-sm text-gray-500 mt-1">Masukkan data kelas baru.</p>
    </div>

    <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kelas
                </label>
                <input type="text"
                       name="nama_kelas"
                       value="{{ old('nama_kelas') }}"
                       placeholder="Contoh: Kelas 7A"
                       class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('nama_kelas')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tingkat
                </label>
                <input type="text"
                       name="tingkat"
                       value="{{ old('tingkat') }}"
                       placeholder="Contoh: 7 / 8 / 9"
                       class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('tingkat')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Wali Kelas
                </label>
                <input type="text"
                       name="wali_kelas"
                       value="{{ old('wali_kelas') }}"
                       placeholder="Nama wali kelas"
                       class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                @error('wali_kelas')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <select name="status"
                        class="block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                    <option value="aktif" @selected(old('status') == 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status') == 'nonaktif')>Nonaktif</option>
                </select>
                @error('status')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.kelas.index') }}"
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
