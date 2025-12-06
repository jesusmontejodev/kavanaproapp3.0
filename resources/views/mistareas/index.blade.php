<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Tareas
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tarjeta de estadísticas -->
            {{-- <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold text-indigo-600 mb-2">Tareas por completar</h3>
                            <div class="flex items-baseline">
                                <span class="text-3xl font-bold text-indigo-600">{{ $totalTareas }}</span>
                                <span class="ml-2 text-gray-500">tareas pendientes</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3">
                                <div class="bg-indigo-600 h-2.5 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-indigo-600 mb-2">Grupos de tareas</h3>
                            <div class="flex items-baseline">
                                <span class="text-3xl font-bold text-indigo-600">{{ count($grupoTareasWithTareas) }}</span>
                                <span class="ml-2 text-gray-500">grupos activos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <!-- Lista de tareas -->
            @if(count($grupoTareasWithTareas) > 0 && $totalTareas > 0)
                @foreach($grupoTareasWithTareas as $grupoTarea)
                    @if($grupoTarea->tareas->count() > 0)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 transition-all duration-300 hover:shadow-md">
                            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-semibold text-white flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                        </svg>
                                        {{ $grupoTarea->nombre }}
                                    </h3>
                                    <span class="bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $grupoTarea->tareas->count() }} {{ $grupoTarea->tareas->count() === 1 ? 'tarea' : 'tareas' }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                <ul class="divide-y divide-gray-200">
                                    @foreach($grupoTarea->tareas as $tarea)
                                        @php
                                            $tareaCompletada = in_array($tarea->id, $tareasCompletadas);
                                        @endphp
                                        <li class="py-4 transition-colors duration-200 hover:bg-gray-50 px-3 rounded-lg">
                                            <div class="flex items-start">
                                                <div class="flex items-center h-5 mt-1">
                                                    <input type="checkbox"
                                                        {{ $tareaCompletada ? 'checked' : '' }}
                                                        disabled
                                                        class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded task-checkbox">
                                                </div>
                                                <div class="ml-3 flex-1">
                                                    <p class="text-sm font-medium text-gray-900 task-name {{ $tareaCompletada ? 'line-through text-gray-400' : '' }}">
                                                        {{ $tarea->nombre }}
                                                    </p>
                                                    <p class="text-sm text-gray-500 mt-1 {{ !$tarea->descripcion ? 'italic text-gray-400' : '' }}">
                                                        {{ $tarea->descripcion ?? 'Sin descripción' }}
                                                    </p>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <span class="text-xs px-2 py-1 rounded-full {{ $tareaCompletada ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ $tareaCompletada ? 'Completada' : 'Pendiente' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <!-- Estado vacío -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">No hay tareas pendientes</h3>
                        <p class="mt-2 text-gray-500">¡Excelente trabajo! Has completado todas tus tareas.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
