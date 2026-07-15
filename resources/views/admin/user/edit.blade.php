@extends('layouts.admin.app')

@section('title', 'Edit User')

@section('content')
@php
    $inputClass = 'block w-full rounded-xl border border-gray-300 px-4 py-3 text-sm focus:border-emerald-500 focus:ring-emerald-500';
    $labelClass = 'block text-sm font-medium text-gray-700 mb-2';
    $currentRole = old('role', $user->getRoleNames()->first());
@endphp

<div class="bg-white rounded-2xl shadow-sm border p-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit User</h1>
        <p class="text-sm text-gray-500 mt-1">Perbarui data user dan relasi wali santri.</p>
    </div>

    <form action="{{ route('admin.user.update', $user) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <label class="{{ $labelClass }}">Nama</label>
                <input type="text"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       class="{{ $inputClass }}">
                @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Email</label>
                <input type="email"
                       name="email"
                       value="{{ old('email', $user->email) }}"
                       class="{{ $inputClass }}">
                @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Password Baru</label>
                <input type="password"
                       name="password"
                       placeholder="Kosongkan jika tidak ingin mengganti"
                       class="{{ $inputClass }}">
                @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $labelClass }}">Role</label>
                <select name="role" class="{{ $inputClass }}">
                    <option value="">Pilih Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" @selected($currentRole == $role->name)>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="border-t pt-6">
            <h2 class="font-semibold text-gray-800 mb-2">Hubungkan ke Santri</h2>
            <p class="text-sm text-gray-500 mb-4">
                Dipakai khusus untuk role Wali Santri. Centang santri yang menjadi anak dari wali ini.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @forelse ($santris as $santri)
                    <label class="flex items-center gap-3 border rounded-xl px-4 py-3 hover:bg-gray-50">
                        <input type="checkbox"
                               name="santri_ids[]"
                               value="{{ $santri->id }}"
                               class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500"
                               @checked(in_array($santri->id, old('santri_ids', $selectedSantris)))>

                        <span>
                            <span class="font-medium text-gray-800">{{ $santri->nama }}</span>
                            <span class="block text-xs text-gray-500">
                                NIS: {{ $santri->nis }} — {{ $santri->kelas->nama_kelas ?? 'Tanpa kelas' }}
                            </span>
                        </span>
                    </label>
                @empty
                    <div class="md:col-span-2 text-sm text-gray-500 bg-gray-50 rounded-xl p-4">
                        Tidak ada santri tersedia.
                    </div>
                @endforelse
            </div>

            @error('santri_ids') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex justify-end gap-3 pt-4">
            <a href="{{ route('admin.user.index') }}"
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
