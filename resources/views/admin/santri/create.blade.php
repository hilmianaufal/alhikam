@extends('layouts.admin.app')

@section('title', 'Tambah Santri')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
    $labelClass = 'block text-sm font-medium text-gray-700 mb-2';
@endphp

<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Santri</h1>
        <p class="text-sm text-gray-500 mt-1">Masukkan data santri baru.</p>
    </div>

    <form action="{{ route('admin.santri.store') }}" method="POST" class="space-y-6">
        @csrf

        <div>
            <h2 class="font-semibold text-gray-800 mb-4">Data Identitas</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="{{ $labelClass }}">NIS</label>
                    <input type="text" name="nis" value="{{ old('nis') }}" placeholder="Contoh: S001" class="{{ $inputClass }}">
                    @error('nis') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">NISN</label>
                    <input type="text" name="nisn" value="{{ old('nisn') }}" placeholder="Masukkan NISN" class="{{ $inputClass }}">
                    @error('nisn') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Nama lengkap santri" class="{{ $inputClass }}">
                    @error('nama') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Nama Panggilan</label>
                    <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan') }}" placeholder="Nama panggilan" class="{{ $inputClass }}">
                    @error('nama_panggilan') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="{{ $inputClass }}">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" @selected(old('jenis_kelamin') == 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin') == 'P')>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Agama</label>
                    <input type="text" name="agama" value="{{ old('agama', 'Islam') }}" class="{{ $inputClass }}">
                    @error('agama') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Contoh: Subang" class="{{ $inputClass }}">
                    @error('tempat_lahir') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="{{ $inputClass }}">
                    @error('tanggal_lahir') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" value="{{ old('tanggal_masuk') }}" class="{{ $inputClass }}">
                    @error('tanggal_masuk') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
    <label class="{{ $labelClass }}">Kelas</label>
    <select name="kelas_id" class="{{ $inputClass }}">
        <option value="">Pilih Kelas</option>
        @foreach ($kelas as $item)
            <option value="{{ $item->id }}" @selected(old('kelas_id') == $item->id)>
                {{ $item->nama_kelas }}
            </option>
        @endforeach
    </select>
    @error('kelas_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

<div>
    <label class="{{ $labelClass }}">Asrama</label>
    <select name="asrama_id" class="{{ $inputClass }}">
        <option value="">Pilih Asrama</option>
        @foreach ($asramas as $item)
            <option value="{{ $item->id }}" @selected(old('asrama_id') == $item->id)>
                {{ $item->nama_asrama }}
            </option>
        @endforeach
    </select>
    @error('asrama_id') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
</div>

                <div>
                    <label class="{{ $labelClass }}">Status Mukim</label>
                    <select name="status_mukim" class="{{ $inputClass }}">
                        <option value="mukim" @selected(old('status_mukim', 'mukim') == 'mukim')>Mukim</option>
                        <option value="non_mukim" @selected(old('status_mukim') == 'non_mukim')>Non Mukim</option>
                    </select>
                    @error('status_mukim') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Status Santri</label>
                    <select name="status" class="{{ $inputClass }}">
                        <option value="aktif" @selected(old('status', 'aktif') == 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status') == 'nonaktif')>Nonaktif</option>
                    </select>
                    @error('status') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="border-t pt-6">
            <h2 class="font-semibold text-gray-800 mb-4">Data Orang Tua / Wali</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="{{ $labelClass }}">Nama Ayah</label>
                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" placeholder="Nama ayah" class="{{ $inputClass }}">
                    @error('nama_ayah') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">No HP Ayah</label>
                    <input type="text" name="no_hp_ayah" value="{{ old('no_hp_ayah') }}" placeholder="08xxxxxxxxxx" class="{{ $inputClass }}">
                    @error('no_hp_ayah') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Nama Ibu</label>
                    <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" placeholder="Nama ibu" class="{{ $inputClass }}">
                    @error('nama_ibu') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">No HP Ibu</label>
                    <input type="text" name="no_hp_ibu" value="{{ old('no_hp_ibu') }}" placeholder="08xxxxxxxxxx" class="{{ $inputClass }}">
                    @error('no_hp_ibu') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">Nama Wali</label>
                    <input type="text" name="nama_wali" value="{{ old('nama_wali') }}" placeholder="Nama wali jika berbeda" class="{{ $inputClass }}">
                    @error('nama_wali') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="{{ $labelClass }}">No HP Wali</label>
                    <input type="text" name="no_hp_wali" value="{{ old('no_hp_wali') }}" placeholder="08xxxxxxxxxx" class="{{ $inputClass }}">
                    @error('no_hp_wali') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div>
            <label class="{{ $labelClass }}">Alamat</label>
            <textarea name="alamat" rows="4" placeholder="Alamat lengkap santri" class="{{ $inputClass }}">{{ old('alamat') }}</textarea>
            @error('alamat') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.santri.index') }}"
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
