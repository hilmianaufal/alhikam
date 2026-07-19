<header class="bg-white border-b px-6 py-4 flex justify-between items-center">
    <div>
        <h2 class="font-semibold text-lg">@yield('title', 'Dashboard')</h2>
        <p class="text-sm text-gray-500">Ponpes Al Hikam</p>
    </div>

    <div class="flex items-center gap-4">
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <div class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center">
                <x-heroicon-o-user class="w-5 h-5 text-emerald-700" />
            </div>

            <div>
                <p class="font-medium leading-tight">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400 leading-tight">
                    {{ Auth::user()->getRoleNames()->first() ?? 'User' }}
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-2 text-sm bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5" />
                <span>Logout</span>
            </button>
        </form>
    </div>
</header>
