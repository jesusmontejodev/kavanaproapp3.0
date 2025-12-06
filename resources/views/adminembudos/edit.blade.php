<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Administrar Embudo') }}: {{ $embudo->nombre }}
            </h2>
            <a href="{{ route('adminembudos.index') }}" class="text-sm text-blue-600 hover:text-blue-800">
                ← Volver a embudos
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Formulario para crear etapa -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear Nueva Etapa
                    </h3>

                    <form method="POST" action="{{ route('adminetapas.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" id="id_embudo" name="id_embudo" value="{{ $embudo->id }}">
                        <input type="hidden" id="orden" name="orden" value="{{ $nuevoOrden }}">

                        <!-- Información del orden -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-medium text-blue-800">Nueva etapa #{{ $nuevoOrden }}</p>
                                    <p class="text-sm text-blue-600 mt-1">
                                        Esta será la etapa número {{ $nuevoOrden }} en el flujo del embudo
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Campo Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                                Nombre de la etapa *
                            </label>
                            <input type="text"
                                id="nombre"
                                name="nombre"
                                value="{{ old('nombre') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                placeholder="Ej: Calificación, Propuesta, Cierre..."
                                required />
                            @error('nombre')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Campo Descripción -->
                        <div>
                            <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">
                                Descripción de la etapa
                            </label>
                            <textarea id="descripcion"
                                    name="descripcion"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150"
                                    placeholder="Describe el propósito y objetivos de esta etapa...">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Botón de envío -->
                        <div class="flex justify-end pt-2">
                            <button type="submit"
                                    class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-300 transition duration-150 ease-in-out">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Crear Etapa #{{ $nuevoOrden }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Lista de etapas existentes -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Etapas del Embudo
                        </h3>
                        <span class="bg-gray-100 text-gray-800 text-sm font-medium px-3 py-1 rounded-full">
                            {{ $tareasembudo->count() }} {{ Str::plural('etapa', $tareasembudo->count()) }}
                        </span>
                    </div>

                    @if($tareasembudo->count() > 0)
                        <div class="space-y-4">
                            @foreach($tareasembudo->sortBy('orden') as $tareaembudo)
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 hover:bg-white hover:shadow-sm transition-all duration-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-4 flex-1">
                                            <!-- Número de orden -->
                                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center border-2 border-blue-200">
                                                <span class="text-blue-700 font-bold text-sm">#{{ $tareaembudo->orden }}</span>
                                            </div>

                                            <!-- Información de la etapa -->
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-semibold text-gray-900 text-lg truncate">{{ $tareaembudo->nombre }}</h4>
                                                @if($tareaembudo->descripcion)
                                                    <p class="text-gray-600 text-sm mt-1 line-clamp-2">{{ $tareaembudo->descripcion }}</p>
                                                @else
                                                    <p class="text-gray-400 text-sm mt-1 italic">Sin descripción</p>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Acciones -->
                                        <div class="flex items-center space-x-2 ml-4">
                                            <!-- Botón Editar -->
                                            <a href="{{ route('adminetapas.edit', $tareaembudo->id) }}"
                                               class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-yellow-600 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring-2 focus:ring-yellow-300 transition duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Editar
                                            </a>

                                            <!-- Botón Eliminar -->
                                            <form method="POST" action="{{ route('adminetapas.destroy', $tareaembudo->id) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('¿Estás seguro de que quieres eliminar la etapa \"{{ $tareaembudo->nombre }}\"?')"
                                                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-medium text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-800 focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-300 transition duration-150">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Estado vacío -->
                        <div class="text-center py-12">
                            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">No hay etapas creadas</h3>
                            <p class="mt-2 text-gray-500 max-w-md mx-auto">
                                Comienza creando la primera etapa para organizar el flujo de trabajo de tu embudo.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Mejoras de hover y transiciones */
    .transition-all {
        transition: all 0.2s ease-in-out;
    }

    .hover\:shadow-sm:hover {
        box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    }
</style>
