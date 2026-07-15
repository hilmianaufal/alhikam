<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Wali Santri') - {{ \App\Helpers\AppSetting::appName() }}</title>
    @if (\App\Helpers\AppSetting::favicon())
        <link rel="icon" href="{{ \App\Helpers\AppSetting::favicon() }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex">

        <aside class="w-72 bg-emerald-800 text-white hidden md:flex md:flex-col shadow-xl">
            <div class="h-20 flex items-center px-6 border-b border-emerald-700">
                <div class="w-12 h-12 rounded-xl bg-white/15 flex items-center justify-center overflow-hidden">
                    @if (\App\Helpers\AppSetting::logo())
                        <img src="{{ \App\Helpers\AppSetting::logo() }}" alt="Logo"
                            class="w-full h-full object-contain p-1">
                    @else
                        <x-heroicon-o-building-library class="w-7 h-7 text-white" />
                    @endif
                </div>

                <div class="ml-3">
                    <h1 class="font-bold text-lg">
                        {{ \App\Helpers\AppSetting::appName() }}
                    </h1>
                    <p class="text-xs text-emerald-200">
                        Portal Wali Santri
                    </p>
                </div>
            </div>

            <div class="flex-1 py-5">
                <p class="px-6 mb-3 text-xs uppercase tracking-widest text-emerald-300">
                    Menu Wali
                </p>

                <a href="{{ route('wali.dashboard') }}"
                    class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('wali.dashboard') ? 'bg-emerald-700 text-white shadow-sm' : 'text-emerald-50 hover:bg-emerald-700/70' }}">
                    <x-heroicon-o-home class="w-5 h-5" />
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('wali.tagihan.index') }}"
                    class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('wali.tagihan.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-emerald-50 hover:bg-emerald-700/70' }}">
                    <x-heroicon-o-document-text class="w-5 h-5" />
                    <span>Tagihan Saya</span>
                </a>

                <a href="{{ route('wali.pembayaran.index') }}"
                    class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('wali.pembayaran.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-emerald-50 hover:bg-emerald-700/70' }}">
                    <x-heroicon-o-credit-card class="w-5 h-5" />
                    <span>Riwayat Pembayaran</span>
                </a>

                <a href="{{ route('wali.konfirmasi-pembayaran.create') }}"
                    class="mx-3 mb-2 flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('wali.konfirmasi-pembayaran.*') ? 'bg-emerald-700 text-white shadow-sm' : 'text-emerald-50 hover:bg-emerald-700/70' }}">
                    <x-heroicon-o-cloud-arrow-up class="w-5 h-5" />
                    <span>Upload Bukti Bayar</span>
                </a>
            </div>

            <div class="border-t border-emerald-700 p-5">
                <div class="flex items-center">
                    <div class="w-11 h-11 rounded-full bg-emerald-600 flex items-center justify-center">
                        <x-heroicon-o-user class="w-5 h-5" />
                    </div>

                    <div class="ml-3">
                        <p class="font-semibold text-sm">
                            {{ Auth::user()->name }}
                        </p>

                        <p class="text-xs text-emerald-200">
                            {{ Auth::user()->getRoleNames()->first() }}
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <main class="flex-1">
            <header class="h-20 bg-white border-b flex items-center justify-between px-6">
                <div>
                    <h2 class="font-bold text-gray-800">
                        @yield('title', 'Dashboard Wali')
                    </h2>
                    <p class="text-sm text-gray-500">
                        Selamat datang, {{ Auth::user()->name }}
                    </p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button class="px-4 py-2 rounded-xl bg-red-100 text-red-700 hover:bg-red-200">
                        Logout
                    </button>
                </form>
            </header>

            <div class="p-6">
                @yield('content')
            </div>
        </main>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    timer: 2000,
                    showConfirmButton: false
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ session('error') }}',
                });
            });
        </script>
    @endif
</body>

</html>
