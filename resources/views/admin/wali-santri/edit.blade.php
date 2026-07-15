@extends('layouts.admin.app')

@section('title', 'Edit Wali Santri')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
    $labelClass = 'block text-sm font-medium text-gray-700 mb-2';
@endphp

<div class="space-y-6">

    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Wali Santri</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Santri: <span class="font-semibold">{{ $santri->nama }}</span>
                </p>
            </div>

            <a href="{{ route('admin.wali-santri.index') }}"
               class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 text-center">
                Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('admin.wali-santri.update', $santri) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Data Santri</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-sm">
                <div class="border rounded-xl p-4">
                    <p class="text-gray-500">Nama Santri</p>
                    <p class="font-semibold text-gray-800 mt-1">{{ $santri->nama }}</p>
                </div>

                <div class="border rounded-xl p-4">
                    <p class="text-gray-500">NIS</p>
                    <p class="font-semibold text-gray-800 mt-1">{{ $santri->nis }}</p>
                </div>

                <div class="border rounded-xl p-4">
                    <p class="text-gray-500">Kelas</p>
                    <p class="font-semibold text-gray-800 mt-1">{{ $santri->kelas->nama_kelas ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-5">Data Orang Tua / Wali</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="{{ $labelClass }}">Nama Ayah</label>
                    <input type="text"
                           name="nama_ayah"
                           value="{{ old('nama_ayah', $santri->nama_ayah) }}"
                           class="{{ $inputClass }}">
                    @error('nama_ayah') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">No HP Ayah</label>
                    <input type="text"
                           name="no_hp_ayah"
                           value="{{ old('no_hp_ayah', $santri->no_hp_ayah) }}"
                           class="{{ $inputClass }}">
                    @error('no_hp_ayah') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Nama Ibu</label>
                    <input type="text"
                           name="nama_ibu"
                           value="{{ old('nama_ibu', $santri->nama_ibu) }}"
                           class="{{ $inputClass }}">
                    @error('nama_ibu') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">No HP Ibu</label>
                    <input type="text"
                           name="no_hp_ibu"
                           value="{{ old('no_hp_ibu', $santri->no_hp_ibu) }}"
                           class="{{ $inputClass }}">
                    @error('no_hp_ibu') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Nama Wali</label>
                    <input type="text"
                           name="nama_wali"
                           value="{{ old('nama_wali', $santri->nama_wali) }}"
                           class="{{ $inputClass }}">
                    @error('nama_wali') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">No HP Wali</label>
                    <input type="text"
                           name="no_hp_wali"
                           value="{{ old('no_hp_wali', $santri->no_hp_wali) }}"
                           class="{{ $inputClass }}">
                    @error('no_hp_wali') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-2">Akun Wali Santri</h2>
            <p class="text-sm text-gray-500 mb-5">
                Pilih akun wali agar wali bisa login dan melihat tagihan santri ini.
            </p>

            <div>
                <label class="{{ $labelClass }}">Akun Wali</label>

                <select name="user_id" class="{{ $inputClass }}">
                    <option value="">Belum dihubungkan</option>

                    @foreach ($waliUsers as $user)
                        <option value="{{ $user->id }}" @selected(old('user_id', $santri->user_id) == $user->id)>
                            {{ $user->name }} — {{ $user->email }}
                        </option>
                    @endforeach
                </select>

                @error('user_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mt-4">
                <a href="{{ route('admin.user.create') }}"
                   class="inline-flex px-4 py-2 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm">
                    + Buat Akun Wali Baru
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border p-6">
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.wali-santri.index') }}"
                   class="px-5 py-3 rounded-xl bg-gray-100 text-gray-700 hover:bg-gray-200">
                    Batal
                </a>

                <button type="submit"
                        class="px-5 py-3 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

</div>
@endsection
