<nav x-data="{ open: false }" class="bg-[#060606] h-full flex flex-col">
    <!-- Logo -->
    <div class="flex-shrink-0 p-4 border-b border-gray-700">
        <a href="{{ route('home') }}" class="flex items-center">
            <x-application-logo class="block h-8 w-auto fill-current text-white" />
            <span class="ms-3 text-lg font-semibold text-white">{{ config('app.name') }} App</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-4">
        <div class="space-y-1 px-3">

            <!-- Section Separator: Herramientas -->
            <div class="px-3 pt-6 pb-2">
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Herramientas</div>
            </div>

            <!-- Home (siempre visible para todos los usuarios autenticados) -->
            <x-nav-link :href="route('home')" :active="request()->routeIs('home')" class="justify-start">
                <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                {{ __('Home') }}
            </x-nav-link>

            @auth
                @php
                    // Obtener todos los roles del usuario
                    $userRoles = auth()->user()->roles->pluck('name')->toArray();

                    // Verificar si tiene roles específicos
                    $tieneRolUsuario = in_array('usuario', $userRoles) || empty($userRoles);
                    $tieneRolCoordinador = in_array('coordinador', $userRoles);
                    $tieneRolAdministrador = in_array('administrador', $userRoles);

                    // Determinar qué mostrar
                    $mostrarHerramientasBasicas = $tieneRolUsuario || $tieneRolCoordinador || $tieneRolAdministrador;
                    $mostrarCoordinacion = $tieneRolCoordinador || $tieneRolAdministrador;
                    $mostrarAdministracion = $tieneRolAdministrador;
                    $mostrarAnalisis = $tieneRolAdministrador; // Solo administradores
                @endphp

                <!-- HERRAMIENTAS BÁSICAS PARA TODOS -->
                @if($mostrarHerramientasBasicas)
                    <!-- MIS TAREAS -->
                    <x-nav-link :href="route('mistareas.index')" :active="request()->routeIs('mistareas.index')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        {{ __('Mis tareas') }}
                    </x-nav-link>

                    <!-- MIS LEADS -->
                    <x-nav-link :href="route('lead.index')" :active="request()->routeIs('lead.index')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ __('Mis leads') }}
                    </x-nav-link>

                    <!-- MIS CLIENTES -->
                    <x-nav-link :href="route('misclientes.index')" :active="request()->routeIs('misclientes.index')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ __('Mis clientes') }}
                    </x-nav-link>

                    <!-- MIS PAGINAS -->
                    <x-nav-link :href="route('paginasusuario.index')" :active="request()->routeIs('paginasusuario.index')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        {{ __('Mis paginas') }}
                    </x-nav-link>
                @endif

                <!-- SECCIÓN ANÁLISIS & REPORTES (solo para administradores) -->
                @if($mostrarAnalisis)
                    <div class="px-3 pt-6 pb-2">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Análisis & Reportes</div>
                    </div>

                    <!-- RANKINGS DE LEADS -->
                    <x-nav-link :href="route('analista.lead-rankings')" :active="request()->routeIs('analista.lead-rankings')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        {{ __('Rankings de Leads') }}
                    </x-nav-link>

                    <!-- RANKINGS DE CLIENTES -->
                    <x-nav-link :href="route('analista.cliente-rankings')" :active="request()->routeIs('analista.cliente-rankings')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('Rankings de Clientes') }}
                    </x-nav-link>

                    <!-- RANKING GLOBAL -->
                    <x-nav-link :href="route('analista.global-rankings')" :active="request()->routeIs('analista.global-rankings')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('Ranking Global') }}
                    </x-nav-link>
                @endif

                <!-- SECCIÓN COORDINACIÓN (para coordinadores y administradores) -->
                @if($mostrarCoordinacion)
                    <div class="px-3 pt-6 pb-2">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Coordinación</div>
                    </div>

                    <!-- REFERIDOS -->
                    <x-nav-link :href="route('referidos.index')" :active="request()->routeIs('referidos.index')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        {{ __('Referidos') }}
                    </x-nav-link>

                    <!-- USUARIOS -->
                    <x-nav-link :href="route('adminusuarios.index')" :active="request()->routeIs('adminusuarios.index')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        {{ __('Tareas de usuarios') }}
                    </x-nav-link>
                @endif

                <!-- SECCIÓN ADMINISTRACIÓN (solo para administradores) -->
                @if($mostrarAdministracion)
                    <div class="px-3 pt-6 pb-2">
                        <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Administración</div>
                    </div>

                    <!-- PROYECTOS ADMIN -->
                    <x-nav-link :href="route('adminproyectos.index')" :active="request()->routeIs('adminproyectos.index')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        {{ __('Proyectos') }}
                    </x-nav-link>

                    <!-- LEADS A CLIENTES -->
                    <x-nav-link :href="route('adminleadtocliente.index')" :active="request()->routeIs('adminleadtocliente.index')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('Leads a Clientes') }}
                    </x-nav-link>

                    <!-- EMBUDOS DE VENTA (Admin) -->
                    <x-nav-link :href="route('adminembudos.index')" :active="request()->routeIs('adminembudos.index')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ __('Embudos de Venta') }}
                    </x-nav-link>

                    <!-- GRUPOS DE TAREAS -->
                    <x-nav-link :href="route('grupotareas.index')" :active="request()->routeIs('grupotareas.index')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ __('Grupos de Tareas') }}
                    </x-nav-link>

                    <!-- ADMIN USUARIOS MAESTRO -->
                    <x-nav-link :href="route('adminusuariosmaestro.index')" :active="request()->routeIs('adminusuariosmaestro.index')" class="justify-start">
                        <svg class="w-5 h-5 me-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        {{ __('Admin usuarios maestro') }}
                    </x-nav-link>
                @endif
            @endauth
        </div>
    </div>

    <!-- User Menu -->
    <div class="flex-shrink-0 border-t border-gray-700 p-4">
        <x-dropdown align="left" width="48">
            <x-slot name="trigger">
                <button class="flex items-center w-full text-left rounded-md hover:bg-gray-800 p-2 transition duration-150 ease-in-out">
                    <div class="flex items-center min-w-0">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full overflow-hidden bg-gray-700 flex items-center justify-center">
                            @if (Auth::user()->foto_perfil && file_exists(public_path(Auth::user()->foto_perfil)))
                                <img src="{{ asset(Auth::user()->foto_perfil) }}"
                                    alt="Foto de perfil"
                                    class="w-full h-full object-cover">
                            @else
                                <span class="text-sm font-semibold text-white">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </span>
                            @endif
                        </div>

                        <div class="ms-3 min-w-0 flex-1">
                            <div class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ ucfirst(implode(', ', $userRoles) ?: 'Usuario') }}
                            </div>
                        </div>
                    </div>
                    <svg class="ms-2 w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                    <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    {{ __('Profile') }}
                </x-dropdown-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="flex items-center text-red-400 hover:text-red-300">
                        <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        {{ __('Log Out') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</nav>
