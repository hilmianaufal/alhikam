@extends('layouts.admin.app')

@section('title', 'Edit Santri')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Santri</h1>

    <form action="{{ route('admin.santri.update', $santri) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium">NIS</label>
                <input type="text" name="nis" value="{{ old('nis', $santri->nis) }}" class="mt-1 w-full rounded-xl border-gray-300">
                @error('nis') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium">NISN</label>
                <input type="text" name="nisn" value="{{ old('nisn', $santri->nisn) }}" class="mt-1 w-full rounded-xl border-gray-300">
            </div>

            <div>
                <label class="text-sm font-medium">Nama Lengkap</label>
                <input type="text" name="nama" value="{{ old('nama', $santri->nama) }}" class="mt-1 w-full rounded-xl border-gray-300">
                @error('nama') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm font-medium">Nama Panggilan</label>
                <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan', $santri->nama_panggilan) }}" class="mt-1 w-full rounded-xl border-gray-300">
            </div>

            <div>
                <label class="text-sm font-medium">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="mt-1 w-full rounded-xl border-gray-300">
                    <option value="L" @selected(old('jenis_kelamin', $santri->jenis_kelamin) == 'L')>Laki-laki</option>
                    <option value="P" @selected(old('jenis_kelamin', $santri->jenis_kelamin) == 'P')>Perempuan</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Agama</label>
                <input type="text" name="agama" value="{{ old('agama', $santri->agama) }}" class="mt-1 w-full rounded-xl border-gray-300">
            </div>

            <div>
                <label class="text-sm font-medium">Tempat Lahir</label>
                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $santri->tempat_lahir) }}" class="mt-1 w-full rounded-xl border-gray-300">
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $santri->tanggal_lahir) }}" class="mt-1 w-full rounded-xl border-gray-300">
            </div>

            <div>
                <label class="text-sm font-medium">Tanggal Masuk</label>
                <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk', $santri->tanggal_masuk) }}" class="mt-1 w-full rounded-xl border-gray-300">
            </div>

            <div>
                <label class="text-sm font-medium">Status Mukim</label>
                <select name="status_mukim" class="mt-1 w-full rounded-xl border-gray-300">
                    <option value="mukim" @selected(old('status_mukim', $santri->status_mukim) == 'mukim')>Mukim</option>
                    <option value="non_mukim" @selected(old('status_mukim', $santri->status_mukim) == 'non_mukim')>Non Mukim</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Status Santri</label>
                <select name="status" class="mt-1 w-full rounded-xl border-gray-300">
                    <option value="aktif" @selected(old('status', $santri->status) == 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(old('status', $santri->status) == 'nonaktif')>Nonaktif</option>
                </select>
            </div>
        </div>

        <hr>

        <h2 class="font-semibold text-gray-800">Data Orang Tua / Wali</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="text-sm font-medium">Nama Ayah</label>
                <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $santri->nama_ayah) }}" class="mt-1 w-full rounded-xl border-gray-300">
            </div>

            <div>
                <label class="text-sm font-medium">No HP Ayah</label>
                <input type="text" name="no_hp_ayah" value="{{ old('no_hp_ayah', $santri->no_hp_ayah) }}" class="mt-1 w-full rounded-xl border-gray-300">
            </div>

            <div>
                <label class="text-sm font-medium">Nama Ibu</label>
                <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $santri->nama_ibu) }}" class="mt-1 w-full rounded-xl border-gray-300">
            </div>

            <div>
                <label class="text-sm font-medium">No HP Ibu</label>
                <input type="text" name="no_hp_ibu" value="{{ old('no_hp_ibu', $santri->no_hp_ibu) }}" class="mt-1 w-full rounded-xl border-gray-300">
            </div>

            <div>
                <label class="text-sm font-medium">Nama Wali</label>
                <input type="text" name="nama_wali" value="{{ old('nama_wali', $santri->nama_wali) }}" class="mt-1 w-full rounded-xl border-gray-300">
            </div>

            <div>
                <label class="text-sm font-medium">No HP Wali</label>
                <input type="text" name="no_hp_wali" value="{{ old('no_hp_wali', $santri->no_hp_wali) }}" class="mt-1 w-full rounded-xl border-gray-300">
            </div>
        </div>

        <div>
            <label class="text-sm font-medium">Alamat</label>
            <textarea name="alamat" rows="3" class="mt-1 w-full rounded-xl border-gray-300">{{ old('alamat', $santri->alamat) }}</textarea>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.santri.index') }}" class="px-4 py-2 rounded-xl bg-gray-100 hover:bg-gray-200">Batal</a>
            <button class="px-4 py-2 rounded-xl bg-emerald-700 text-white hover:bg-emerald-800">Update</button>
        </div>
    </form>
</div>
@endsection
