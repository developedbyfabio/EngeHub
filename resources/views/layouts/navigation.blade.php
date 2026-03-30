<nav x-data="{ open: false }" class="bg-black border-b border-gray-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center">
                    <img src="/media/logo.png" alt="EngeHub" class="h-6 w-auto max-h-6" style="max-width: 120px;">
                </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @if(auth()->check() || auth()->guard('system')->check() || !request()->routeIs('home'))
                        @php
                            $webNavUser = auth()->guard('web')->user();
                            $showMainNavGuest = !auth()->check() && !auth()->guard('system')->check();
                        @endphp
                        @if($showMainNavGuest || auth()->guard('system')->check() || ($webNavUser && $webNavUser->canAccessNav(\App\Support\NavPermission::HOME)))
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Início') }}
                        </x-nav-link>
                        @endif
                        @if($showMainNavGuest || auth()->guard('system')->check() || ($webNavUser && $webNavUser->canAccessNav(\App\Support\NavPermission::SERVERS)))
                        <x-nav-link :href="route('servers.index')" :active="request()->routeIs('servers.*')">
                            {{ __('Servidores') }}
                        </x-nav-link>
                        @endif
                        @if($showMainNavGuest || auth()->guard('system')->check() || ($webNavUser && $webNavUser->canAccessNav(\App\Support\NavPermission::CAMERAS)))
                        <x-nav-link :href="route('cameras.index')" :active="request()->routeIs('cameras.*')">
                            {{ __('Câmeras') }}
                        </x-nav-link>
                        @endif
                        @if($showMainNavGuest || auth()->guard('system')->check() || ($webNavUser && $webNavUser->canAccessNav(\App\Support\NavPermission::FILIAIS)))
                        <x-nav-link :href="route('filiais.index')" :active="request()->routeIs('filiais.*')">
                            {{ __('Filiais') }}
                        </x-nav-link>
                        @endif
                    @endif
                    
                    @if(auth()->check() && auth()->user()->canSeeGerenciarMenu())
                        @php
                            $gerenciarActive = request()->routeIs('admin.cards.*') || request()->routeIs('admin.system-users.*') || request()->routeIs('admin.user-groups.*') || request()->routeIs('admin.sectors.*') || request()->routeIs('admin.network-maps.*') || request()->routeIs('admin.servers.*') || request()->routeIs('admin.server-groups.*') || request()->routeIs('admin.cameras.*') || request()->routeIs('admin.forms.*') || request()->routeIs('admin.branches.*') || request()->routeIs('admin.extension-list.*');
                        @endphp
                        <div class="relative inline-flex" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false">
                            <button type="button" @click="open = ! open" :class="{ 'gerenciar-dropdown-open': open }" class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out cursor-pointer gerenciar-dropdown-trigger {{ $gerenciarActive ? 'border-primary-400 text-primary-400' : 'border-transparent text-gray-500' }}">
                                {{ __('Gerenciar') }}
                                <svg class="ml-1 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute left-0 top-full z-50 mt-0.5 w-56 origin-top-left rounded-md bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5"
                                    style="display: none;">
                                <div class="py-1">
                                    @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_CAMERAS))
                                    <a href="{{ route('admin.cameras.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">Gerenciar Câmeras</a>
                                    @endif
                                    @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_CARDS))
                                    <a href="{{ route('admin.cards.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">Gerenciar Cards</a>
                                    @endif
                                    @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_FORMS))
                                    <a href="{{ route('admin.forms.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">Gerenciar Formulários e Checklists</a>
                                    @endif
                                    @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_EXTENSION_LIST))
                                    <a href="{{ route('admin.extension-list.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">Gerenciar Lista de Ramais</a>
                                    @endif
                                    @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_NETWORK_MAPS))
                                    <a href="{{ route('admin.network-maps.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">Gerenciar Mapas de Rede</a>
                                    @endif
                                    @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_SERVERS))
                                    <a href="{{ route('admin.servers.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">Gerenciar Servidores</a>
                                    @endif
                                    @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_SECTORS))
                                    <a href="{{ route('admin.sectors.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">Gerenciar Setores</a>
                                    @endif
                                    @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_SYSTEM_USERS))
                                    <a href="{{ route('admin.system-users.index') }}" class="block px-4 py-2 text-sm text-gray-200 hover:bg-gray-700 hover:text-white transition duration-150 ease-in-out">Gerenciar Grupos e Usuários</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @if(auth()->check() || auth()->guard('system')->check())
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <i class="fas fa-user mr-2 text-black"></i>
                                <span class="text-black">
                                    @if(auth()->check())
                                        {{ Auth::user()->name }}
                                    @elseif(auth()->guard('system')->check())
                                        {{ Auth::guard('system')->user()->name }}
                                    @endif
                                </span>

                                <div class="ml-2">
                                    <svg class="fill-current h-4 w-4 text-black" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                @csrf

                                <x-dropdown-link :href="route('logout')" id="logout-link">
                                    Sair do Sistema
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                        <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <i class="fas fa-sign-in-alt mr-2 text-black"></i>
                            <span class="text-black">Log in</span>
                        </a>
                @endif
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->check() || auth()->guard('system')->check() || !request()->routeIs('home'))
                @php
                    $webNavUserM = auth()->guard('web')->user();
                    $showMainNavGuestM = !auth()->check() && !auth()->guard('system')->check();
                @endphp
                @if($showMainNavGuestM || auth()->guard('system')->check() || ($webNavUserM && $webNavUserM->canAccessNav(\App\Support\NavPermission::HOME)))
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Início') }}
                </x-responsive-nav-link>
                @endif
                @if($showMainNavGuestM || auth()->guard('system')->check() || ($webNavUserM && $webNavUserM->canAccessNav(\App\Support\NavPermission::SERVERS)))
                <x-responsive-nav-link :href="route('servers.index')" :active="request()->routeIs('servers.*')">
                    {{ __('Servidores') }}
                </x-responsive-nav-link>
                @endif
                @if($showMainNavGuestM || auth()->guard('system')->check() || ($webNavUserM && $webNavUserM->canAccessNav(\App\Support\NavPermission::CAMERAS)))
                <x-responsive-nav-link :href="route('cameras.index')" :active="request()->routeIs('cameras.*')">
                    {{ __('Câmeras') }}
                </x-responsive-nav-link>
                @endif
                @if($showMainNavGuestM || auth()->guard('system')->check() || ($webNavUserM && $webNavUserM->canAccessNav(\App\Support\NavPermission::FILIAIS)))
                <x-responsive-nav-link :href="route('filiais.index')" :active="request()->routeIs('filiais.*')">
                    {{ __('Filiais') }}
                </x-responsive-nav-link>
                @endif
            @endif
            
            @if(auth()->check() && auth()->user()->canSeeGerenciarMenu())
                <div x-data="{ gerenciarOpen: false }" class="border-b border-gray-200">
                    <button @click="gerenciarOpen = ! gerenciarOpen" class="flex w-full items-center justify-between pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none">
                        {{ __('Gerenciar') }}
                        <svg class="h-4 w-4 transition-transform" :class="{ 'rotate-180': gerenciarOpen }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="gerenciarOpen"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="pl-4 pb-2 space-y-1">
                        @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_CAMERAS))
                        <x-responsive-nav-link :href="route('admin.cameras.index')" :active="request()->routeIs('admin.cameras.*')">
                            {{ __('Gerenciar Câmeras') }}
                        </x-responsive-nav-link>
                        @endif
                        @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_CARDS))
                        <x-responsive-nav-link :href="route('admin.cards.index')" :active="request()->routeIs('admin.cards.*')">
                            {{ __('Gerenciar Cards') }}
                        </x-responsive-nav-link>
                        @endif
                        @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_FORMS))
                        <x-responsive-nav-link :href="route('admin.forms.index')" :active="request()->routeIs('admin.forms.*') || request()->routeIs('admin.branches.*')">
                            {{ __('Gerenciar Formulários e Checklists') }}
                        </x-responsive-nav-link>
                        @endif
                        @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_EXTENSION_LIST))
                        <x-responsive-nav-link :href="route('admin.extension-list.index')" :active="request()->routeIs('admin.extension-list.*')">
                            {{ __('Gerenciar Lista de Ramais') }}
                        </x-responsive-nav-link>
                        @endif
                        @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_NETWORK_MAPS))
                        <x-responsive-nav-link :href="route('admin.network-maps.index')" :active="request()->routeIs('admin.network-maps.*')">
                            {{ __('Gerenciar Mapas de Rede') }}
                        </x-responsive-nav-link>
                        @endif
                        @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_SERVERS))
                        <x-responsive-nav-link :href="route('admin.servers.index')" :active="request()->routeIs('admin.servers.*')">
                            {{ __('Gerenciar Servidores') }}
                        </x-responsive-nav-link>
                        @endif
                        @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_SECTORS))
                        <x-responsive-nav-link :href="route('admin.sectors.index')" :active="request()->routeIs('admin.sectors.*')">
                            {{ __('Gerenciar Setores') }}
                        </x-responsive-nav-link>
                        @endif
                        @if(auth()->user()->canAccessNav(\App\Support\NavPermission::ADMIN_SYSTEM_USERS))
                        <x-responsive-nav-link :href="route('admin.system-users.index')" :active="request()->routeIs('admin.system-users.*') || request()->routeIs('admin.user-groups.*')">
                            {{ __('Gerenciar Grupos e Usuários') }}
                        </x-responsive-nav-link>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        @if(auth()->check() || auth()->guard('system')->check())
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4 mb-3">
                    <div class="inline-flex items-center w-full px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest">
                        <i class="fas fa-user mr-2 text-black"></i>
                        <span class="text-black">
                            @if(auth()->check())
                                {{ Auth::user()->name }}
                            @elseif(auth()->guard('system')->check())
                                {{ Auth::guard('system')->user()->name }}
                            @endif
                        </span>
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}" id="logout-form-mobile">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')" id="logout-link-mobile">
                            Sair do Sistema
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center w-full px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:bg-yellow-600 focus:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-sign-in-alt mr-2 text-black"></i>
                        <span class="text-black">Log in</span>
                    </a>
                </div>
            </div>
        @endif
    </div>
</nav> 