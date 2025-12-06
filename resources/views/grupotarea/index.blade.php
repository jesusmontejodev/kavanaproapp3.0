<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Encabezado con mensajes flash -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-md">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span class="text-green-700 font-medium">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Gestor de Grupos de Tareas</h1>
                <p class="text-gray-600 mt-2">Organiza tus tareas en grupos personalizados</p>
            </div>

            <!-- Botón para abrir modal de creación (si quieres usar modal) -->
            <button
                onclick="openCreateModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg flex items-center transition duration-200 shadow-md"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nuevo Grupo
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Formulario de creación -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100 sticky top-6">
                    <h2 class="text-xl font-semibold text-gray-700 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Crear Nuevo
                    </h2>

                    <form method="POST" action="{{ route('grupotareas.store') }}" id="createForm">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre del Grupo
                                </label>
                                <input
                                    type="text"
                                    id="nombre"
                                    name="nombre"
                                    required
                                    minlength="3"
                                    maxlength="100"
                                    placeholder="Ej: Proyecto Marketing"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                    value="{{ old('nombre') }}"
                                >
                                @error('nombre')
                                    <span class="text-sm text-red-600 mt-2 block">
                                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>

                            <button
                                type="submit"
                                class="w-full bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium py-3 px-4 rounded-lg hover:from-blue-600 hover:to-blue-700 transition duration-200 shadow-md hover:shadow-lg flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Crear Grupo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de grupos -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-xl font-semibold text-gray-700 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Mis Grupos ({{ $grupoTareas->count() }})
                        </h2>

                        @if($grupoTareas->count() > 0)
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                            {{ $grupoTareas->count() }} grupo(s)
                        </span>
                        @endif
                    </div>

                    @if($grupoTareas->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($grupoTareas as $grupoTarea)
                            <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-100 rounded-xl p-5 hover:shadow-lg transition duration-200 group relative">
                                <!-- Menú de acciones (3 puntos) -->
                                <div class="absolute top-4 right-4">
                                    <button
                                        onclick="toggleActionsMenu('actions-{{ $grupoTarea->id }}')"
                                        class="text-gray-400 hover:text-gray-600 p-1 rounded-full hover:bg-gray-100"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                        </svg>
                                    </button>

                                    <!-- Menú desplegable de acciones -->
                                    <div id="actions-{{ $grupoTarea->id }}" class="hidden absolute right-0 mt-1 w-48 bg-white rounded-md shadow-lg border border-gray-200 z-10">
                                        <div class="py-1">
                                            <!-- Editar -->
                                            <button
                                                onclick="openEditModal({{ $grupoTarea->id }}, '{{ $grupoTarea->nombre }}')"
                                                class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                            >
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Editar nombre
                                            </button>

                                            <!-- Eliminar -->
                                            <form method="POST" action="{{ route('grupotareas.destroy', $grupoTarea) }}" class="border-t border-gray-100">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    onclick="return confirm('¿Estás seguro de eliminar este grupo?')"
                                                    class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                                >
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Eliminar grupo
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Contenido del grupo -->
                                <div class="mb-3">
                                    <h3 class="font-bold text-lg text-gray-800 group-hover:text-blue-600 transition duration-200 mb-2">
                                        {{ $grupoTarea->nombre }}
                                    </h3>
                                    <div class="flex items-center text-sm text-gray-500 mb-1">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        Creado: {{ $grupoTarea->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $grupoTarea->tareas->count() }} tarea(s)
                                    </div>
                                </div>

                                <!-- Acciones principales -->
                                <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                                    <a
                                        href="{{ route('grupotareas.show', $grupoTarea) }}"
                                        class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition duration-200"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Ver tareas
                                    </a>
                                    <span class="text-xs font-medium px-3 py-1 rounded-full
                                        {{ $grupoTarea->tareas->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ $grupoTarea->tareas->count() > 0 ? 'Con tareas' : 'Vacío' }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Estado vacío -->
                        <div class="text-center py-16">
                            <div class="mx-auto w-32 h-32 text-gray-200 mb-6">
                                <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-medium text-gray-700 mb-3">¡No hay grupos aún!</h3>
                            <p class="text-gray-500 max-w-md mx-auto mb-8">
                                Crea tu primer grupo de tareas para comenzar a organizar tu trabajo de manera eficiente.
                            </p>
                            <button
                                onclick="document.getElementById('nombre').focus()"
                                class="inline-flex items-center text-blue-600 hover:text-blue-800 font-medium"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Crear mi primer grupo
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Editar Grupo</h3>
                <button
                    onclick="closeEditModal()"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_nombre" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre del Grupo
                        </label>
                        <input
                            type="text"
                            id="edit_nombre"
                            name="nombre"
                            required
                            minlength="3"
                            maxlength="100"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                        >
                    </div>

                    <div class="flex space-x-3 pt-4">
                        <button
                            type="button"
                            onclick="closeEditModal()"
                            class="flex-1 py-3 px-4 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200"
                        >
                            Cancelar
                        </button>
                        <button
                            type="submit"
                            class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-medium py-3 px-4 rounded-lg hover:from-blue-600 hover:to-blue-700 transition duration-200"
                        >
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Función para alternar menú de acciones
        function toggleActionsMenu(menuId) {
            const menu = document.getElementById(menuId);
            menu.classList.toggle('hidden');

            // Cerrar otros menús abiertos
            document.querySelectorAll('[id^="actions-"]').forEach(otherMenu => {
                if (otherMenu.id !== menuId && !otherMenu.classList.contains('hidden')) {
                    otherMenu.classList.add('hidden');
                }
            });
        }

        // Cerrar menús al hacer clic fuera
        document.addEventListener('click', function(event) {
            if (!event.target.closest('[id^="actions-"]') && !event.target.closest('button[onclick*="toggleActionsMenu"]')) {
                document.querySelectorAll('[id^="actions-"]').forEach(menu => {
                    menu.classList.add('hidden');
                });
            }
        });

        // Funciones para el modal de edición
        let currentGroupId = null;

        function openEditModal(id, nombre) {
            currentGroupId = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('editForm').action = `/grupotareas/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            currentGroupId = null;
        }

        // Cerrar modal con ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeEditModal();
            }
        });

        // Enfocar en el campo de creación cuando se hace clic en el botón
        function openCreateModal() {
            document.getElementById('nombre').focus();
            document.getElementById('nombre').scrollIntoView({ behavior: 'smooth' });
        }
    </script>

    <style>
        /* Animaciones suaves */
        [id^="actions-"] {
            animation: fadeIn 0.2s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Estilos para el modal */
        #editModal {
            animation: modalFadeIn 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</x-app-layout>
