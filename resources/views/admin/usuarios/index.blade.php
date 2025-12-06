<x-app-layout>
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">👥 Gestión de Usuarios</h1>
                    <p class="text-gray-600 mt-2">Administra todos los usuarios del sistema</p>
                </div>
                <div class="flex space-x-3">
                    @if(request('search'))
                        <a href="{{ route('adminusuariosmaestro.index') }}"
                            class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            🔄 Limpiar búsqueda
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mensajes de éxito/error -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Tarjeta de estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-100 p-3 rounded-lg">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Usuarios</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $users->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 p-3 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Activos</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            {{ \App\Models\User::whereNull('deleted_at')->count() }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 p-3 rounded-lg">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Administradores</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            @php
                                $adminRole = \App\Models\Role::where('name', 'administrador')->first();
                                $adminCount = $adminRole ? \DB::table('role_user')->where('role_id', $adminRole->id)->count() : 0;
                            @endphp
                            {{ $adminCount }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 p-3 rounded-lg">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Coordinadores</p>
                        <p class="text-2xl font-semibold text-gray-900">
                            @php
                                $coordRole = \App\Models\Role::where('name', 'coordinador')->first();
                                $coordCount = $coordRole ? \DB::table('role_user')->where('role_id', $coordRole->id)->count() : 0;
                            @endphp
                            {{ $coordCount }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla de usuarios -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <!-- Barra de búsqueda -->
            <div class="p-6 border-b">
                <form action="{{ route('adminusuariosmaestro.index') }}" method="GET" class="flex gap-3">
                    <div class="flex-1">
                        <input type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Buscar por nombre, email, teléfono o empresa..."
                            class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        🔍 Buscar
                    </button>
                </form>
            </div>

            <!-- Tabla -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Usuario
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contacto
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Rol
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                #{{ $user->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 flex-shrink-0 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <span class="text-indigo-800 font-medium">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <a href="{{ route('adminusuariosmaestro.show', $user->id) }}" class="hover:text-indigo-600">
                                                {{ $user->name }}
                                            </a>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $user->empresa ?? 'Sin empresa' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                @if($user->phone)
                                    <div class="text-sm text-gray-500">{{ $user->phone }}</div>
                                @endif
                                @if($user->ciudad || $user->estado)
                                    <div class="text-xs text-gray-400">
                                        {{ $user->ciudad }}{{ $user->ciudad && $user->estado ? ', ' : '' }}{{ $user->estado }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @foreach($user->roles as $role)
                                    <span class="px-2 py-1 text-xs rounded-full
                                        {{ $role->name == 'administrador' ? 'bg-purple-100 text-purple-800' :
                                            ($role->name == 'coordinador' ? 'bg-blue-100 text-blue-800' :
                                            'bg-gray-100 text-gray-800') }}">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                                @if($user->roles->isEmpty())
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">
                                        Usuario
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->trashed())
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                        ❌ Inactivo
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        ✅ Activo
                                    </span>
                                @endif
                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <!-- Ver -->
                                    <a href="{{ route('adminusuariosmaestro.show', $user->id) }}"
                                        class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50"
                                        title="Ver detalles">
                                        👁️
                                    </a>

                                    <!-- Editar -->
                                    <a href="{{ route('adminusuariosmaestro.edit', $user->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50"
                                        title="Editar">
                                        ✏️
                                    </a>

                                    <!-- Cambiar contraseña -->
                                    <a href="{{ route('adminusuariosmaestro.cambiarPasswordForm', $user->id) }}"
                                        class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50"
                                        title="Cambiar contraseña">
                                        🔑
                                    </a>

                                    <!-- Activar/Desactivar -->
                                    <form action="{{ route('adminusuariosmaestro.toggleEstado', $user->id) }}"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm('¿{{ $user->trashed() ? 'Activar' : 'Desactivar' }} este usuario?')">
                                        @csrf
                                        <button type="submit"
                                                class="{{ $user->trashed() ? 'text-green-600 hover:text-green-900' : 'text-yellow-600 hover:text-yellow-900' }} p-1 rounded {{ $user->trashed() ? 'hover:bg-green-50' : 'hover:bg-yellow-50' }}"
                                                title="{{ $user->trashed() ? 'Activar usuario' : 'Desactivar usuario' }}">
                                            {{ $user->trashed() ? '✅' : '⏸️' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                    <p class="mt-2 text-lg font-medium">No se encontraron usuarios</p>
                                    <p class="mt-1 text-sm">
                                        @if(request('search'))
                                            No hay resultados para "{{ request('search') }}"
                                        @else
                                            No hay usuarios registrados en el sistema
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($users->hasPages())
                <div class="px-6 py-4 border-t bg-gray-50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        <!-- Información -->
        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    <p class="text-sm text-blue-700">
                        <strong>Funciones disponibles:</strong> Ver detalles, editar información, cambiar contraseñas, activar/desactivar usuarios y asignar roles.
                        Los cambios de contraseña pueden notificarse por email al usuario.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
