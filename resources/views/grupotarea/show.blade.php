<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Grupo: {{ $grupotarea->nombre }}
            </h2>

        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensajes de éxito -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Panel de creación de tareas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">Crear Nueva Tarea</h3>
                    <form method="POST" action="{{ route('tareas.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="id_grupo_tarea" value="{{ $grupotarea->id }}">
                        <input type="hidden" name="orden" value="0">

                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre de la tarea *
                            </label>
                            <input type="text"
                                id="nombre"
                                name="nombre"
                                placeholder="¿Qué necesita hacerse?"
                                required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                                Descripción
                            </label>
                            <textarea
                                id="descripcion"
                                name="descripcion"
                                placeholder="Detalles adicionales..."
                                rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-md transition duration-200">
                            ➕ Crear Tarea
                        </button>
                    </form>
                </div>
            </div>

            <!-- Panel de tareas -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">
                            Tareas del Grupo
                            <span class="bg-blue-100 text-blue-800 text-sm font-medium ml-2 px-2.5 py-0.5 rounded">
                                {{ $grupotarea->tareas->count() }}
                            </span>
                        </h3>

                        <!-- Filtros y búsqueda (puedes implementar después) -->
                        <div class="flex space-x-2">
                            <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-1 px-3 rounded text-sm">
                                Ordenar
                            </button>
                        </div>
                    </div>

                    <div class="kanban">
                        <div class="kanban-col">
                            <div class="kanban-list" id="tareas-list">
                                @forelse($grupotarea->tareas as $tarea)
                                    <div class="card group" data-id="{{ $tarea->id }}">
                                        <div class="card-header">
                                            <h4 class="card-title">{{ $tarea->nombre }}</h4>
                                            @if($tarea->descripcion)
                                                <p class="card-description">{{ $tarea->descripcion }}</p>
                                            @endif
                                        </div>

                                        <div class="card-actions">
                                            <div class="action-buttons">
                                                {{-- <a href="{{ route('tareas.edit', $tarea->id) }}"
                                                    class="btn-edit"
                                                    title="Editar tarea">
                                                    ✏️ Editar
                                                </a> --}}
                                                <form method="POST" action="{{ route('tareas.destroy', $tarea->id) }}"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar la tarea: {{ $tarea->nombre }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-delete" title="Eliminar tarea">
                                                        🗑️ Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                            <div class="card-meta">
                                                <span class="text-xs text-gray-500">
                                                    Creado: {{ $tarea->created_at->format('d/m/Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <div class="empty-icon">📝</div>
                                        <h3>No hay tareas aún</h3>
                                        <p>Comienza creando la primera tarea de este grupo.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const tareasList = document.getElementById('tareas-list');

        if (tareasList) {
            new Sortable(tareasList, {
                animation: 150,
                ghostClass: 'sortable-ghost',
                dragClass: 'sortable-drag',
                onEnd: async (evt) => {
                    const tareaId = evt.item.dataset.id;
                    const newPosition = evt.newIndex;

                    try {
                        const response = await fetch(`/tareas/${tareaId}/reorder`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                position: newPosition,
                                grupo_tarea_id: {{ $grupotarea->id }}
                            })
                        });

                        if (!response.ok) {
                            throw new Error('Error al reordenar');
                        }

                        const result = await response.json();
                        if (result.success) {
                            console.log('Tarea reordenada correctamente');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        // Podrías mostrar un mensaje de error al usuario
                    }
                }
            });
        }
    });
    </script>

    <style>
        .kanban {
            display: flex;
            gap: 1.5rem;
        }

        .kanban-col {
            flex: 1;
            background: #f8fafc;
            border-radius: 12px;
            padding: 1rem;
            border: 2px solid #e2e8f0;
        }

        .kanban-list {
            min-height: 200px;
            padding: 0.5rem;
        }

        .card {
            background: white;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #3b82f6;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border-radius: 8px;
            cursor: grab;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .card:hover {
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .card:active {
            cursor: grabbing;
        }

        .card-header {
            margin-bottom: 0.75rem;
        }

        .card-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .card-description {
            color: #6b7280;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        .card-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid #f3f4f6;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn-edit {
            color: #2563eb;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: background-color 0.2s;
            text-decoration: none;
        }

        .btn-edit:hover {
            background-color: #dbeafe;
        }

        .btn-delete {
            color: #dc2626;
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            transition: background-color 0.2s;
            border: none;
            background: none;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: #fecaca;
        }

        .card-meta {
            text-align: right;
        }

        .sortable-ghost {
            opacity: 0.4;
            background: #c7d2fe;
        }

        .sortable-drag {
            transform: rotate(5deg);
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 2px dashed #cbd5e1;
        }

        .empty-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: #4b5563;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .empty-state p {
            color: #6b7280;
            margin-bottom: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .kanban {
                flex-direction: column;
            }

            .card-actions {
                flex-direction: column;
                gap: 0.5rem;
                align-items: flex-start;
            }

            .action-buttons {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</x-app-layout>
