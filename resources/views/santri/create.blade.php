<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Santri Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('santri.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="nis" class="block text-sm font-medium text-gray-700 mb-1">NIS (Nomor Induk Santri)</label>
                        <input type="text" name="nis" id="nis" value="{{ old('nis') }}" class="w-full rounded-md shadow-sm border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" required>
                        @error('nis') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label for="nama_lengkap" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap') }}" class="w-full rounded-md shadow-sm border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" required>
                        @error('nama_lengkap') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="kelas" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                            <input type="text" name="kelas" id="kelas" value="{{ old('kelas') }}" placeholder="Contoh: 7-A, 10-Ula" class="w-full rounded-md shadow-sm border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50" required>
                            @error('kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="asrama" class="block text-sm font-medium text-gray-700 mb-1">Kamar / Asrama</label>
                            <input type="text" name="asrama" id="asrama" value="{{ old('asrama') }}" placeholder="Contoh: Al-Kautsar" class="w-full rounded-md shadow-sm border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="no_hp_wali" class="block text-sm font-medium text-gray-700 mb-1">No. HP Wali Santri (WhatsApp)</label>
                        <input type="text" name="no_hp_wali" id="no_hp_wali" value="{{ old('no_hp_wali') }}" placeholder="Contoh: 08123456789" class="w-full rounded-md shadow-sm border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50">
                        <p class="text-gray-400 text-xs mt-1">Gunakan format angka langsung (misal: 08xx atau 62xx).</p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('santri.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                            Batal
                        </a>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg text-sm transition duration-150">
                            Simpan Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
