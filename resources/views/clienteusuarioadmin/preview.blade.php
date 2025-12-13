<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vista previa: {{ $archivo->nombre_archivo }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Controles -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">
                                {{ $archivo->nombre_archivo }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                Cliente: {{ $cliente->nombre_completo }}
                            </p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('cliente.descargar-archivo', $archivo->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 active:bg-green-600 transition">
                                <i class="fas fa-download mr-2"></i> Descargar
                            </a>
                            <a href="{{ route('cliente.show', $cliente->id) }}"
                                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring focus:ring-gray-200 active:bg-gray-600 transition">
                                <i class="fas fa-arrow-left mr-2"></i> Volver
                            </a>
                        </div>
                    </div>

                    <!-- Visor de archivo -->
                    <div class="border border-gray-300 rounded-lg overflow-hidden">
                        @if(in_array($archivo->extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']))
                            <img src="{{ Storage::url($archivo->url_archivo) }}"
                                alt="{{ $archivo->nombre_archivo }}"
                                class="w-full h-auto max-h-[70vh] object-contain">
                        @elseif($archivo->extension === 'pdf')
                            <iframe src="{{ Storage::url($archivo->url_archivo) }}#toolbar=0"
                                    class="w-full h-[70vh] border-0"
                                    frameborder="0">
                                Tu navegador no soporta la visualización de PDFs.
                            </iframe>
                        @else
                            <div class="text-center py-12">
                                <i class="fas fa-file text-6xl text-gray-400 mb-4"></i>
                                <p class="text-gray-500">Este archivo no se puede previsualizar en el navegador.</p>
                                <p class="text-gray-400 text-sm mt-2">Descarga el archivo para verlo.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Información del archivo -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dt class="font-medium text-gray-500">Tamaño</dt>
                            <dd class="mt-1 text-gray-900">{{ $archivo->tamano_formateado }}</dd>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dt class="font-medium text-gray-500">Tipo</dt>
                            <dd class="mt-1 text-gray-900">{{ strtoupper($archivo->extension) }}</dd>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <dt class="font-medium text-gray-500">Subido el</dt>
                            <dd class="mt-1 text-gray-900">{{ $archivo->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
