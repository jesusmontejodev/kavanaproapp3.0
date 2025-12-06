<x-app-layout>
<div class="py-8">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">👤 {{ $user->name }}</h1>
                <p class="text-gray-600 mt-2">Detalles completos del usuario</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('adminusuariosmaestro.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                    ← Volver a lista
                </a>
                <div class="flex gap-2">
                    <a href="{{ route('adminusuariosmaestro.edit', $user->id) }}"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        ✏️ Editar
                    </a>
                    <a href="{{ route('adminusuariosmaestro.cambiarPasswordForm', $user->id) }}"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        🔑 Cambiar Pass
                    </a>
                </div>
            </div>
        </div>

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

        <!-- Grid principal -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Columna izquierda: Información básica -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tarjeta de información personal -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">📋 Información Personal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Nombre completo</label>
                            <p class="mt-1 text-lg font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email</label>
                            <p class="mt-1">
                                <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $user->email }}
                                </a>
                                @if($user->email_verified_at)
                                    <span class="ml-2 text-xs text-green-600">✓ Verificado</span>
                                @else
                                    <span class="ml-2 text-xs text-yellow-600">⚠ No verificado</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Teléfono</label>
                            <p class="mt-1">
                                @if($user->phone)
                                    <a href="tel:{{ $user->phone }}" class="text-gray-900">
                                        {{ $user->phone }}
                                    </a>
                                @else
                                    <span class="text-gray-400">No especificado</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Empresa</label>
                            <p class="mt-1">{{ $user->empresa ?? 'No especificada' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Ubicación</label>
                            <p class="mt-1">
                                @if($user->ciudad || $user->estado)
                                    {{ $user->ciudad }}{{ $user->ciudad && $user->estado ? ', ' : '' }}{{ $user->estado }}
                                @else
                                    <span class="text-gray-400">No especificada</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">ID de usuario</label>
                            <p class="mt-1 font-mono text-sm">#{{ $user->id }}</p>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de actividad -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">📊 Actividad</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-indigo-600">
                                {{ $user->leads->count() }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Leads</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">
                                {{ $user->usuarioTareas->where('completado', true)->count() }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Tareas completadas</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">
                                {{ $user->usuarioTareas->count() }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Tareas asignadas</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">
                                {{ $user->referidos->count() }}
                            </div>
                            <div class="text-sm text-gray-500 mt-1">Referidos</div>
                        </div>
                    </div>
                </div>

                <!-- Si hay leads, mostrar algunos -->
                @if($user->leads->count() > 0)
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">🎯 Leads Recientes</h3>
                        <a href="{{ route('lead.index') }}?user={{ $user->id }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                            Ver todos →
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Lead</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Estado</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($user->leads->take(5) as $lead)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3">
                                        <div class="font-medium">{{ $lead->nombre }}</div>
                                        <div class="text-sm text-gray-500">{{ $lead->email }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 text-xs rounded-full
                                            {{ $lead->estado == 'activo' ? 'bg-green-100 text-green-800' :
                                               ($lead->estado == 'prospecto' ? 'bg-yellow-100 text-yellow-800' :
                                               'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($lead->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        {{ $lead->created_at->format('d/m/Y') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <!-- Columna derecha: Estado y acciones -->
            <div class="space-y-6">
                <!-- Tarjeta de estado -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">⚙️ Estado de la Cuenta</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Estado actual</label>
                            <div class="mt-2">
                                @if($user->trashed())
                                    <span class="px-3 py-2 inline-flex items-center rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        ❌ Cuenta inactiva
                                    </span>
                                    <form action="{{ route('adminusuariosmaestro.toggleEstado', $user->id) }}" method="POST" class="mt-3">
                                        @csrf
                                        <button type="submit"
                                                class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                            ✅ Activar cuenta
                                        </button>
                                    </form>
                                @else
                                    <span class="px-3 py-2 inline-flex items-center rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        ✅ Cuenta activa
                                    </span>
                                    <form action="{{ route('adminusuariosmaestro.toggleEstado', $user->id) }}" method="POST" class="mt-3">
                                        @csrf
                                        <button type="submit"
                                                onclick="return confirm('¿Desactivar la cuenta de {{ $user->name }}?')"
                                                class="w-full px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                                            ⏸️ Desactivar cuenta
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-500 mb-2">Roles asignados</label>
                            <div class="flex flex-wrap gap-2 mb-4">
                                @foreach($user->roles as $role)
                                    <div class="flex items-center px-3 py-1 rounded-full text-sm bg-blue-100 text-blue-800">
                                        {{ ucfirst($role->name) }}
                                        <form action="{{ route('adminusuariosmaestro.removerRol', [$user->id, $role->id]) }}"
                                            method="POST" class="ml-2 inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('¿Remover rol {{ $role->name }} de {{ $user->name }}?')"
                                                    class="text-red-500 hover:text-red-700 text-xs">
                                                ×
                                            </button>
                                        </form>
                                    </div>
                                @endforeach

                                @if($user->roles->isEmpty())
                                    <span class="px-3 py-1 rounded-full text-sm bg-gray-100 text-gray-800">
                                        Usuario básico
                                    </span>
                                @endif
                            </div>

                            <!-- Formulario para agregar nuevo rol -->
                            <form action="{{ route('adminusuariosmaestro.asignarRol', $user->id) }}" method="POST">
                                @csrf
                                <label class="block text-sm font-medium text-gray-500 mb-2">Agregar nuevo rol</label>
                                <div class="flex gap-2">
                                    <select name="role" class="flex-1 border border-gray-300 rounded-lg px-3 py-2">
                                        <option value="">Seleccionar rol para agregar</option>
                                        @php
                                            $rolesDisponibles = ['usuario', 'coordinador', 'administrador'];
                                            $rolesActuales = $user->roles->pluck('name')->toArray();
                                            $rolesFaltantes = array_diff($rolesDisponibles, $rolesActuales);
                                        @endphp

                                        @foreach($rolesFaltantes as $role)
                                            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                        @endforeach

                                        @if(empty($rolesFaltantes))
                                            <option value="" disabled>Usuario ya tiene todos los roles disponibles</option>
                                        @endif
                                    </select>
                                    <button type="submit"
                                            {{ empty($rolesFaltantes) ? 'disabled' : '' }}
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 {{ empty($rolesFaltantes) ? 'opacity-50 cursor-not-allowed' : '' }}">
                                        Agregar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta de información de cuenta -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">📅 Información de la Cuenta</h3>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Miembro desde</label>
                            <p class="mt-1">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Última actualización</label>
                            <p class="mt-1">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @if($user->email_verified_at)
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Email verificado</label>
                            <p class="mt-1 text-green-600">
                                {{ $user->email_verified_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">🚀 Acciones Rápidas</h3>
                    <div class="space-y-3">
                        <a href="{{ route('adminusuariosmaestro.cambiarPasswordForm', $user->id) }}"
                            class="flex items-center w-full p-3 border border-red-200 rounded-lg hover:bg-red-50">
                            <span class="text-red-600 mr-3">🔑</span>
                            <span>Cambiar contraseña</span>
                        </a>
                        <a href="{{ route('adminusuariosmaestro.edit', $user->id) }}"
                            class="flex items-center w-full p-3 border border-indigo-200 rounded-lg hover:bg-indigo-50">
                            <span class="text-indigo-600 mr-3">✏️</span>
                            <span>Editar información</span>
                        </a>
                        @if($user->leads->count() > 0)
                        <a href="{{ route('lead.index') }}?user={{ $user->id }}"
                            class="flex items-center w-full p-3 border border-blue-200 rounded-lg hover:bg-blue-50">
                            <span class="text-blue-600 mr-3">🎯</span>
                            <span>Ver leads ({{ $user->leads->count() }})</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
