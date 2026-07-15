@extends('layouts.admin.app')

@section('title', 'Edit Kelas')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Kelas</h1>

    <form action="{{ route('admin.kelas.update', $kelas) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div>
            <label class="text-sm font-medium">Nama Kelas</label>
            <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" class="mt-1 w-full rounded-xl border-gray-300">
            @error('nama_kelas') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm font-medium">Tingkat</label>
            <input type="text" name="tingkat" value="{{ old('tingkat', $kelas->tingkat) }}" class="mt-1 w-full rounded-xl border-gray-300">
            @error('tingkat') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm font-medium">Wali Kelas</label>
            <input type="text" name="wali_kelas" value="{{ old('wali_kelas', $kelas->wali_kelas) }}" class="mt-1 w-full rounded-xl border-gray-300">
            @error('wali_kelas') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="text-sm font-medium">Status</label>
            <select name="status" class="mt-1 w-full rounded-xl border-gray-300">
                <option value="aktif" @selected(old('status', $kelas->status) == 'aktif')>Aktif</option>
                <option value="nonaktif" @selected(old('status', $kelas->status) == 'nonaktif')>Nonaktif</option>
            </select>
            @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.kelas.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200">
                Batal
            </a>

            <button class="px-4 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">
                Update
            </button>
        </div>
    </form>
</div>
@endsection
