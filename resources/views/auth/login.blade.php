@php
    $appName = \App\Helpers\AppSetting::appName();
    $pondokName = \App\Helpers\AppSetting::pondokName();
    $address = \App\Helpers\AppSetting::address();
    $logo = \App\Helpers\AppSetting::logo();
    $favicon = \App\Helpers\AppSetting::favicon();
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ $appName }}</title>

    @if ($favicon)
        <link rel="icon" href="{{ $favicon }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-950">
    <div class="min-h-screen relative overflow-hidden">

        <div class="absolute inset-0 bg-gradient-to-br from-emerald-950 via-slate-950 to-slate-900"></div>

        <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/3 -right-32 w-96 h-96 bg-teal-400/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-lime-400/10 rounded-full blur-3xl"></div>

        <div class="relative min-h-screen grid grid-cols-1 lg:grid-cols-2">

            <div class="hidden lg:flex flex-col justify-between p-12 text-white">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center overflow-hidden">
                        @if ($logo)
                            <img src="{{ $logo }}"
                                 alt="Logo"
                                 class="w-full h-full object-contain p-2">
                        @else
                            <x-heroicon-o-building-library class="w-8 h-8 text-white" />
                        @endif
                    </div>

                    <div>
                        <h1 class="text-xl font-bold">{{ $appName }}</h1>
                        <p class="text-sm text-emerald-100">{{ $pondokName }}</p>
                    </div>
                </div>

                <div class="max-w-xl">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/15 backdrop-blur text-sm text-emerald-100 mb-6">
                        <x-heroicon-o-shield-check class="w-5 h-5" />
                        Sistem Keuangan Pesantren
                    </div>

                    <h2 class="text-5xl font-extrabold leading-tight">
                        Kelola keuangan pondok lebih rapi, aman, dan modern.
                    </h2>

                    <p class="mt-6 text-lg text-slate-300 leading-relaxed">
                        Pantau tagihan santri, pembayaran, kas pondok, laporan keuangan,
                        dan portal wali santri dalam satu sistem terpadu.
                    </p>

                    <div class="grid grid-cols-3 gap-4 mt-10">
                        <div class="rounded-2xl bg-white/10 border border-white/15 backdrop-blur p-5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-400/20 flex items-center justify-center mb-3">
                                <x-heroicon-o-credit-card class="w-6 h-6 text-emerald-300" />
                            </div>
                            <p class="font-semibold">Pembayaran</p>
                            <p class="text-xs text-slate-300 mt-1">Cepat & tercatat</p>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/15 backdrop-blur p-5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-400/20 flex items-center justify-center mb-3">
                                <x-heroicon-o-banknotes class="w-6 h-6 text-emerald-300" />
                            </div>
                            <p class="font-semibold">Kas Pondok</p>
                            <p class="text-xs text-slate-300 mt-1">Masuk & keluar</p>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/15 backdrop-blur p-5">
                            <div class="w-10 h-10 rounded-xl bg-emerald-400/20 flex items-center justify-center mb-3">
                                <x-heroicon-o-chart-bar-square class="w-6 h-6 text-emerald-300" />
                            </div>
                            <p class="font-semibold">Laporan</p>
                            <p class="text-xs text-slate-300 mt-1">Siap cetak</p>
                        </div>
                    </div>
                </div>

                <div class="text-sm text-slate-400">
                    @if ($address)
                        <p>{{ $address }}</p>
                    @endif
                    <p class="mt-1">© {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
                </div>
            </div>

            <div class="flex items-center justify-center px-6 py-10">
                <div class="w-full max-w-md">

                    <div class="lg:hidden text-center mb-8">
                        <div class="w-20 h-20 mx-auto rounded-3xl bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center overflow-hidden">
                            @if ($logo)
                                <img src="{{ $logo }}"
                                     alt="Logo"
                                     class="w-full h-full object-contain p-3">
                            @else
                                <x-heroicon-o-building-library class="w-10 h-10 text-white" />
                            @endif
                        </div>

                        <h1 class="text-2xl font-bold text-white mt-4">{{ $appName }}</h1>
                        <p class="text-sm text-emerald-100">{{ $pondokName }}</p>
                    </div>

                    <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
                        <div class="hidden lg:flex justify-center mb-6">
                            <div class="w-20 h-20 rounded-3xl bg-emerald-50 border border-emerald-100 flex items-center justify-center overflow-hidden shadow-sm">
                                @if ($logo)
                                    <img src="{{ $logo }}"
                                         alt="Logo"
                                         class="w-full h-full object-contain p-3">
                                @else
                                    <x-heroicon-o-building-library class="w-10 h-10 text-emerald-700" />
                                @endif
                            </div>
                        </div>

                        <div class="text-center mb-8">
                            <h2 class="text-2xl font-extrabold text-gray-900">
                                Selamat Datang
                            </h2>
                            <p class="text-sm text-gray-500 mt-2">
                                Masuk ke akun {{ $appName }}
                            </p>
                        </div>

                        @if (session('status'))
                            <div class="mb-5 rounded-2xl bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-700">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                                Email atau password tidak sesuai.
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf

                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email
                                </label>

                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <x-heroicon-o-envelope class="w-5 h-5 text-gray-400" />
                                    </div>

                                    <input id="email"
                                           type="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           required
                                           autofocus
                                           autocomplete="username"
                                           placeholder="Masukkan email"
                                           class="block w-full rounded-2xl border border-gray-200 bg-gray-50 pl-12 pr-4 py-3.5 text-sm text-gray-800 placeholder:text-gray-400 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500">
                                </div>

                                @error('email')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Password
                                </label>

                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <x-heroicon-o-lock-closed class="w-5 h-5 text-gray-400" />
                                    </div>

                                    <input id="password"
                                           type="password"
                                           name="password"
                                           required
                                           autocomplete="current-password"
                                           placeholder="Masukkan password"
                                           class="block w-full rounded-2xl border border-gray-200 bg-gray-50 pl-12 pr-4 py-3.5 text-sm text-gray-800 placeholder:text-gray-400 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500">
                                </div>

                                @error('password')
                                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox"
                                           name="remember"
                                           class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">

                                    <span class="text-sm text-gray-600">
                                        Ingat saya
                                    </span>
                                </label>

                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                       class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
                                        Lupa password?
                                    </a>
                                @endif
                            </div>

                            <button type="submit"
                                    class="w-full rounded-2xl bg-gradient-to-r from-emerald-700 to-emerald-600 px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-700/25 hover:from-emerald-800 hover:to-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 transition">
                                Masuk Dashboard
                            </button>
                        </form>

                        <div class="mt-8 rounded-2xl bg-gray-50 border border-gray-100 p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                                    <x-heroicon-o-information-circle class="w-6 h-6 text-emerald-700" />
                                </div>

                                <div>
                                    <p class="text-sm font-semibold text-gray-800">
                                        Akses pengguna
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1 leading-relaxed">
                                        Login digunakan untuk Super Admin, Pengurus, dan Wali Santri.
                                        Sistem akan otomatis mengarahkan sesuai role akun.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="text-center text-xs text-slate-400 mt-6">
                        Powered by {{ $appName }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
