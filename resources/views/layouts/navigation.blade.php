<nav x-data="{ mobileOpen: false, profileOpen: false }" class="w-full relative z-50 bg-[#0D0D0D] border-b border-gray-800 text-white">
    <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-3">
            <div class="flex min-w-0 items-center gap-3">
                <a href="{{ route('home') }}" class="inline-flex min-w-0 flex-shrink-0 items-center gap-2 text-[#FFB703] font-semibold text-base sm:text-lg">
                    <span class="text-2xl">🍕</span>
                    <span class="truncate">Al-Shamas Pizza Hut</span>
                </a>

                <div class="hidden lg:flex items-center gap-2 overflow-x-auto whitespace-nowrap">
                    <a href="{{ route('home') }}#menu-section" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Menu</a>
                    <a href="{{ route('home') }}#deals-section" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Deals</a>
                    <a href="{{ route('home') }}#location-section" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Location</a>
                    <a href="{{ route('complaints.create') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Complaints</a>
                    @auth
                        @if(in_array(Auth::user()->role, ['owner', 'super_admin', 'admin']))
                            <a href="{{ route('dashboard') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Dashboard</a>
                            @if(Auth::user()->role === 'owner')
                                <a href="{{ url('/owner/manage-owners') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Manage Owners</a>
                            @endif
                            <a href="{{ url('/owner/orders') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Orders</a>
                            <a href="{{ url('/owner/items') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Inventory</a>
                            <a href="{{ url('/owner/deals') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Deals</a>
                            <a href="{{ url('/owner/categories') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Categories</a>
                            @if(Auth::user()->role === 'owner')
                                <a href="{{ route('owner.expenses.index') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Expenses</a>
                            @endif
                        @endif
                        @if(Auth::user()->role === 'customer')
                            <a href="{{ route('customer.orders.index') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">My Orders</a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="ml-auto flex shrink-0 items-center gap-2 sm:gap-3">
                <a href="tel:03428544110" class="hidden sm:inline-flex items-center gap-2 rounded-full border border-slate-800 bg-[#181818] px-3 py-2 text-xs text-slate-300 hover:bg-slate-900 sm:text-sm">
                    <span>📞</span>
                    <span>0342-8544110</span>
                </a>

                <div class="hidden sm:flex items-center gap-2">
                    <form method="POST" action="{{ route('locale.switch', 'en') }}">
                        @csrf
                        <button type="submit" class="rounded-full bg-slate-900 px-3 py-2 text-xs hover:bg-slate-800 sm:text-sm">EN</button>
                    </form>
                    <form method="POST" action="{{ route('locale.switch', 'ur') }}">
                        @csrf
                        <button type="submit" class="rounded-full bg-slate-900 px-3 py-2 text-xs hover:bg-slate-800 sm:text-sm">اردو</button>
                    </form>
                </div>

                @auth
                    <div class="relative hidden sm:block">
                        <button type="button" @click="profileOpen = !profileOpen" class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-3 py-2 text-sm text-slate-300">
                            @php $user = Auth::user(); @endphp
                            @if($user->profile_image)
                                <img src="{{ asset('storage/' . $user->profile_image) }}" alt="Profile" class="h-8 w-8 rounded-full object-cover">
                            @else
                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-slate-800 text-xs font-semibold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            @endif
                            <span class="hidden md:inline">{{ $user->name }}</span>
                        </button>

                        <div x-show="profileOpen" x-transition x-cloak @click.outside="profileOpen = false" class="absolute right-0 z-[100] mt-2 w-44 rounded-3xl border border-slate-800 bg-[#181818] p-3 shadow-xl">
                            <a href="{{ route('profile.edit') }}" class="block rounded-2xl px-3 py-2 text-sm text-slate-300 hover:bg-slate-900 hover:text-white">Profile</a>
                            @if($user->role === 'customer')
                                <a href="{{ route('customer.orders.index') }}" class="block rounded-2xl px-3 py-2 text-sm text-slate-300 hover:bg-slate-900 hover:text-white">My Orders</a>
                            @endif
                            @if(in_array($user->role, ['owner','super_admin','admin']))
                                <a href="{{ url('/owner/orders') }}" class="block rounded-2xl px-3 py-2 text-sm text-slate-300 hover:bg-slate-900 hover:text-white">Owner Orders</a>
                                @if($user->role === 'owner')
                                    <a href="{{ url('/owner/manage-owners') }}" class="block rounded-2xl px-3 py-2 text-sm text-slate-300 hover:bg-slate-900 hover:text-white">Manage Owners</a>
                                @endif
                                <a href="{{ url('/owner/items') }}" class="block rounded-2xl px-3 py-2 text-sm text-slate-300 hover:bg-slate-900 hover:text-white">Inventory</a>
                                <a href="{{ url('/owner/deals') }}" class="block rounded-2xl px-3 py-2 text-sm text-slate-300 hover:bg-slate-900 hover:text-white">Deals</a>
                                <a href="{{ url('/owner/categories') }}" class="block rounded-2xl px-3 py-2 text-sm text-slate-300 hover:bg-slate-900 hover:text-white">Categories</a>
                                @if($user->role === 'owner')
                                    <a href="{{ route('owner.expenses.index') }}" class="block rounded-2xl px-3 py-2 text-sm text-slate-300 hover:bg-slate-900 hover:text-white">Expenses</a>
                                @endif
                            @endif
                            <a href="{{ route('complaints.create') }}" class="block rounded-2xl px-3 py-2 text-sm text-slate-300 hover:bg-slate-900 hover:text-white">Complaints</a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                @csrf
                                <button type="submit" class="w-full rounded-2xl px-3 py-2 text-left text-sm text-slate-300 hover:bg-slate-900 hover:text-white">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Login</a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-flex rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Register</a>
                @endauth

                <button type="button" @click="mobileOpen = !mobileOpen" class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-700 bg-slate-900 text-lg lg:hidden" aria-label="Toggle menu">
                    ☰
                </button>

                <a href="{{ route('checkout.show') }}" class="inline-flex shrink-0 items-center gap-2 rounded-full bg-[#FFB703] px-3 py-2 text-xs font-semibold text-[#0D0D0D] sm:px-4 sm:text-sm">
                    Cart [<span id="cart-count">{{ collect(session('cart', []))->sum('quantity') }}</span>]
                </a>
            </div>
        </div>

        <div x-show="mobileOpen" x-transition x-cloak class="border-t border-slate-800 pb-3 pt-3 lg:hidden">
            <div class="flex flex-col gap-2">
                <a href="{{ route('home') }}#menu-section" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Menu</a>
                <a href="{{ route('home') }}#deals-section" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Deals</a>
                <a href="{{ route('home') }}#location-section" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Location</a>
                <a href="{{ route('complaints.create') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Complaints</a>
                @auth
                    @if(in_array(Auth::user()->role, ['owner', 'super_admin', 'admin']))
                        @if(Auth::user()->role === 'owner')
                            <a href="{{ url('/owner/manage-owners') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Manage Owners</a>
                        @endif
                        <a href="{{ url('/owner/orders') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Orders</a>
                        <a href="{{ url('/owner/items') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Inventory</a>
                        <a href="{{ url('/owner/deals') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Deals</a>
                        <a href="{{ url('/owner/categories') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Categories</a>
                        @if(Auth::user()->role === 'owner')
                            <a href="{{ route('owner.expenses.index') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">Expenses</a>
                        @endif
                    @endif
                    @if(Auth::user()->role === 'customer')
                        <a href="{{ route('customer.orders.index') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-sm hover:bg-slate-800">My Orders</a>
                    @endif
                @endauth
                @guest
                    <div class="mt-1 grid grid-cols-2 gap-2">
                        <a href="{{ route('login') }}" class="rounded-xl bg-slate-900 px-3 py-2 text-center text-sm font-semibold hover:bg-slate-800">Login</a>
                        <a href="{{ route('register') }}" class="rounded-xl bg-[#FFB703] px-3 py-2 text-center text-sm font-semibold text-[#0D0D0D] hover:bg-yellow-400">Register</a>
                    </div>
                @else
                    <a href="{{ route('profile.edit') }}" class="mt-1 block w-full rounded-xl bg-slate-900 px-3 py-2 text-left text-sm font-semibold hover:bg-slate-800">Profile</a>
                    @if(in_array(Auth::user()->role, ['owner', 'super_admin', 'admin']))
                        <a href="{{ route('dashboard') }}" class="mt-1 block w-full rounded-xl bg-slate-900 px-3 py-2 text-left text-sm font-semibold hover:bg-slate-800">Dashboard</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit" class="w-full rounded-xl border border-slate-700 px-3 py-2 text-left text-sm font-semibold text-slate-200 hover:bg-slate-900">Logout</button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</nav>
