<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Proyectos disponibles
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if ($proyectos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                    @foreach ($proyectos as $proyecto)
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group">

                            <!-- Imagen del proyecto -->
                            <div class="h-48 overflow-hidden relative">
                                <img
                                    src="{{ asset($proyecto->url_imagen) }}"
                                    alt="{{ $proyecto->nombre }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                >
                                <!-- Overlay en hover -->
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300"></div>
                            </div>

                            <!-- Contenido de la card -->
                            <div class="p-6">
                                <!-- Título -->
                                <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                    {{ $proyecto->nombre }}
                                </h3>

                                <!-- Descripción -->
                                <p class="text-gray-600 mb-4 line-clamp-3 leading-relaxed">
                                    {{ $proyecto->descripcion ?? 'Sin descripción disponible' }}
                                </p>

                                <!-- Información adicional -->
                                <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span>{{ $proyecto->mediaProyectos->count() }} imágenes</span>
                                    </div>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                        {{ $proyecto->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <div class="border-t border-gray-100 pt-4">
                                    <a href="{{ route('proyectos.show', $proyecto) }}"
                                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 flex items-center justify-center group/btn shadow-md">
                                        <span>Ver Detalles</span>
                                        <svg class="w-4 h-4 ml-2 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>

            @else
                <!-- Estado vacío mejorado -->
                <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-200">
                    <span>No hay proyectos</span>
                </div>
            @endif

        </div>
    </div>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>
