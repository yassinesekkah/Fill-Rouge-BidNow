<div>
    <!-- Life is available only in the present moment. - Thich Nhat Hanh -->
    <nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-xl border-b border-slate-200/60">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

            <a href="{{ route('home') }}" class="text-xl font-black tracking-tighter flex items-center gap-2">
                <span
                    class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center italic text-white text-xs">B</span>
                BID<span class="text-indigo-600">NOW</span>
            </a>

            <div class="flex items-center gap-5">

                @auth
                <span class="text-sm font-semibold">
                    {{ auth()->user()->name }}
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm font-bold text-red-600">
                        Logout
                    </button> =>
                </form>
                @else
                <a href="{{ route('login') }}" class="text-sm font-bold">
                    Login
                </a>

                <a href="{{ route('register') }}"
                    class="bg-indigo-600 text-white px-5 py-2 rounded-full text-sm font-bold">
                    Register
                </a>
                @endauth

            </div>
        </div>
    </nav>

</div>