<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Administrador Masivo de Usuarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-lg font-semibold mb-6">Administrador masivo de usuarios</h3>

            <!-- BUSCADOR -->
            <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                <form method="GET" action="{{ route('adminusuarios.index') }}" class="flex gap-4 items-end">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                            Buscar usuario por email o nombre
                        </label>
                        <input type="text"
                            name="search"
                            id="search"
                            value="{{ $search ?? '' }}"
                            placeholder="Ej: usuario@correo.com o nombre del usuario..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            Buscar
                        </button>
                        @if($search ?? false)
                            <a href="{{ route('adminusuarios.index') }}"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                                Limpiar
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- CONTADOR DE RESULTADOS -->
            @if($search ?? false)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-blue-800">
                        @if($allUsers->count() > 0)
                            Se encontraron <strong>{{ $allUsers->count() }}</strong> usuario(s) para "<strong>{{ $search }}</strong>"
                        @else
                            No se encontraron usuarios para "<strong>{{ $search }}</strong>"
                        @endif
                    </p>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <p class="text-gray-600">
                        Mostrando <strong>{{ $allUsers->count() }}</strong> usuario(s) en total
                    </p>
                </div>
            @endif

            <!-- LISTA DE USUARIOS -->
            @if($allUsers->count() > 0)
                @foreach($allUsers as $user)
                    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-lg font-semibold">Usuario: {{ $user->name }}</h4>
                                <p class="text-gray-600">Email: {{ $user->email }}</p>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                ID: {{ $user->id }}
                            </span>
                        </div>

                        <div class="space-y-4">
                            @foreach($GrupoTareasWithTareas as $grupotarea)
                                @if($grupotarea->tareas->count() > 0)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h5 class="font-semibold text-gray-700 mb-3">
                                            Grupo: {{ $grupotarea->nombre }}
                                        </h5>

                                        <div class="space-y-2">
                                            @foreach($grupotarea->tareas as $tarea)
                                                @php
                                                    $registroExistente = app('App\Http\Controllers\UsuarioTareaController')->existeTareaUsuario($user->id, $tarea->id);
                                                    $tareaCompletada = $registroExistente ? $registroExistente->completado : false;
                                                @endphp

                                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                                                    <div class="flex items-center space-x-3">
                                                        @if($registroExistente)
                                                            {{-- FORMULARIO UPDATE --}}
                                                            <form method="POST"
                                                                action="{{ route('usuariotarea.update', $registroExistente->id) }}"
                                                                class="flex items-center space-x-3">
                                                                @csrf
                                                                @method('PUT')
                                                                <input type="checkbox"
                                                                    name="completado"
                                                                    value="1"
                                                                    {{ $tareaCompletada ? 'checked' : '' }}
                                                                    onchange="this.form.submit()"
                                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                                <label class="text-sm font-medium text-gray-700 {{ $tareaCompletada ? 'line-through text-gray-400' : '' }}">
                                                                    {{ $tarea->nombre }}
                                                                </label>
                                                            </form>
                                                        @else
                                                            {{-- FORMULARIO STORE --}}
                                                            <form method="POST"
                                                                action="{{ route('usuariotarea.store') }}"
                                                                class="flex items-center space-x-3">
                                                                @csrf
                                                                <input type="hidden" name="id_user" value="{{ $user->id }}">
                                                                <input type="hidden" name="id_tarea" value="{{ $tarea->id }}">
                                                                <input type="hidden" name="completado" value="0" id="hidden_completado_{{ $tarea->id }}_{{ $user->id }}">

                                                                <input type="checkbox"
                                                                    onchange="toggleCheckbox(this, {{ $tarea->id }}, {{ $user->id }})"
                                                                    {{ $tareaCompletada ? 'checked' : '' }}
                                                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                                                <label class="text-sm font-medium text-gray-700 {{ $tareaCompletada ? 'line-through text-gray-400' : '' }}">
                                                                    {{ $tarea->nombre }}
                                                                </label>
                                                            </form>
                                                        @endif
                                                    </div>

                                                    <div class="flex items-center space-x-3">
                                                        <span class="text-xs px-2 py-1 rounded-full
                                                            {{ $tareaCompletada
                                                                ? 'bg-green-100 text-green-800'
                                                                : 'bg-yellow-100 text-yellow-800' }}">
                                                            {{ $tareaCompletada ? 'Completada' : 'Pendiente' }}
                                                        </span>

                                                        @if($registroExistente)
                                                            <span class="text-xs text-gray-500">
                                                                ID Reg: {{ $registroExistente->id }}
                                                            </span>
                                                        @else
                                                            <span class="text-xs text-gray-400">
                                                                Sin registro
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <!-- ESTADO VACÍO -->
                <div class="bg-white shadow-sm rounded-lg p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">
                        @if($search ?? false)
                            No se encontraron usuarios
                        @else
                            No hay usuarios registrados
                        @endif
                    </h3>
                    <p class="mt-2 text-gray-500">
                        @if($search ?? false)
                            Intenta con otro término de búsqueda
                        @else
                            No hay usuarios registrados en el sistema
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleCheckbox(checkbox, tareaId, userId) {
            const form = checkbox.closest('form');
            const hiddenInput = document.getElementById('hidden_completado_' + tareaId + '_' + userId);

            // Actualizar el valor del campo hidden
            hiddenInput.value = checkbox.checked ? '1' : '0';

            // Enviar el formulario
            form.submit();
        }

        // Auto-focus en el buscador al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            if (searchInput) {
                searchInput.focus();

                // Limpiar búsqueda con Escape key
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        window.location.href = "{{ route('adminusuarios.index') }}";
                    }
                });
            }
        });
    </script>
</x-app-layout>
