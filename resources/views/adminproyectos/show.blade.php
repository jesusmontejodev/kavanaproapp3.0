<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Proyecto: {{ $proyecto->nombre }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">Administración de contenido multimedia</p>
            </div>
            <a href="{{ route('adminproyectos.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white font-medium rounded-lg shadow-sm transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver a Proyectos
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- TARJETA PRINCIPAL --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                {{-- ENCABEZADO CON INFORMACIÓN --}}
                <div class="p-8 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mr-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-md">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900 mb-2">Detalles del Proyecto</h3>
                            <p class="text-gray-700 leading-relaxed">{{ $proyecto->descripcion }}</p>
                        </div>
                    </div>
                </div>

                {{-- GALERÍA DE IMÁGENES --}}
                <div class="p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Galería del Proyecto</h3>
                            <p class="text-gray-600 mt-1">Imágenes asociadas al proyecto</p>
                        </div>
                        <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $mediasProyecto->count() }} imágenes
                        </span>
                    </div>

                    @if ($mediasProyecto->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach ($mediasProyecto as $media)
                                <div class="group relative bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                                    <div class="aspect-w-16 aspect-h-9 bg-gray-100 overflow-hidden relative">
                                        <img src="{{ asset($media->url_imagen) }}"
                                            alt="{{ $media->descripcion }}"
                                            class="w-full h-56 object-cover group-hover:scale-105 transition-transform duration-300">

                                        {{-- Botón de eliminar --}}
                                        <button type="button"
                                                onclick="confirmarEliminarImagen({{ $media->id }}, '{{ addslashes($media->descripcion ?? 'esta imagen') }}')"
                                                class="absolute top-2 right-2 bg-red-600 text-white p-2 rounded-full shadow-lg hover:bg-red-700 transition-colors duration-200 opacity-0 group-hover:opacity-100"
                                                title="Eliminar imagen">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="p-5">
                                        <p class="text-gray-800 line-clamp-2">{{ $media->descripcion ?? 'Sin descripción' }}</p>
                                    </div>

                                    <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <span class="inline-flex items-center px-3 py-1 bg-black/70 text-white text-xs font-medium rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                            </svg>
                                            Imagen
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl">
                            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-gray-200 to-gray-300 rounded-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No hay imágenes aún</h3>
                            <p class="text-gray-600 max-w-md mx-auto">Agrega la primera imagen usando el formulario de abajo para comenzar la galería.</p>
                        </div>
                    @endif
                </div>

                {{-- FORMULARIO PARA SUBIR NUEVA IMAGEN --}}
                <div class="p-8 bg-gradient-to-br from-gray-50 to-white border-t border-gray-200">
                    <div class="max-w-3xl mx-auto">
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mr-4 shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Agregar Nueva Imagen</h2>
                                    <p class="text-gray-600 mt-1">Complete la información para agregar una imagen al proyecto</p>
                                </div>
                            </div>
                        </div>

                        <form method="POST"
                            action="{{ route('adminmediaproyectos.store') }}"
                            enctype="multipart/form-data"
                            class="space-y-8">

                            @csrf
                            <input type="hidden" name="id_proyecto" value="{{ $proyecto->id }}">

                            <!-- Descripción -->
                            <div class="space-y-3">
                                <label for="descripcion" class="block text-sm font-semibold text-gray-800 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Descripción de la Imagen
                                </label>
                                <textarea
                                    id="descripcion"
                                    name="descripcion"
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-3 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all duration-300 placeholder-gray-400 bg-white resize-none shadow-sm"
                                    placeholder="Describa el contenido de la imagen, su propósito o cualquier detalle relevante..."
                                ></textarea>
                            </div>

                            <!-- Upload de Imagen -->
                            <div class="space-y-3">
                                <label class="block text-sm font-semibold text-gray-800 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Seleccionar Imagen
                                    <span class="text-red-500 ml-1">*</span>
                                </label>

                                <div class="relative">
                                    <div class="flex items-center justify-center w-full">
                                        <label for="url_imagen" class="flex flex-col items-center justify-center w-full h-40 border-2 border-dashed border-gray-300 rounded-2xl cursor-pointer bg-gradient-to-br from-gray-50 to-white hover:from-gray-100 hover:to-gray-50 transition-all duration-300 hover:border-emerald-400 group" id="dropzone">
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="dropzone-content">
                                                <div class="w-14 h-14 mb-4 bg-gradient-to-br from-emerald-100 to-green-100 rounded-full flex items-center justify-center group-hover:from-emerald-200 group-hover:to-green-200 transition-all duration-300">
                                                    <svg class="w-7 h-7 text-emerald-600 group-hover:text-emerald-700 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                                    </svg>
                                                </div>
                                                <p class="mb-2 text-sm text-gray-600 font-medium">
                                                    <span class="text-emerald-600">Haga clic para subir</span> o arrastre y suelte
                                                </p>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF, WEBP (Máximo 2MB)</p>
                                            </div>
                                            <input
                                                id="url_imagen"
                                                name="url_imagen"
                                                type="file"
                                                class="hidden"
                                                accept="image/*"
                                                required
                                                onchange="validateImage(this)"
                                            />
                                        </label>
                                    </div>

                                    <!-- Mensajes de estado -->
                                    <div id="image-error" class="mt-3 hidden animate-fade-in">
                                        <div class="flex items-center p-4 bg-red-50 border border-red-200 rounded-xl">
                                            <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span id="error-message" class="text-sm text-red-700"></span>
                                        </div>
                                    </div>

                                    <div id="file-info" class="mt-3 hidden animate-fade-in">
                                        <div class="flex items-center p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                                            <svg class="w-5 h-5 text-emerald-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span id="file-message" class="text-sm text-emerald-800"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Preview de imagen -->
                            <div id="image-preview" class="mt-6 hidden animate-fade-in">
                                <p class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Vista previa
                                </p>
                                <div class="relative inline-block">
                                    <img id="preview" class="max-w-md rounded-xl shadow-lg border-2 border-gray-200">
                                    <div class="absolute top-4 right-4 bg-black/70 text-white text-xs font-medium px-3 py-1 rounded-full">
                                        Preview
                                    </div>
                                </div>
                            </div>

                            <!-- Botón de Submit -->
                            <div class="flex items-center justify-end pt-8 border-t border-gray-200">
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-8 py-3.5 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-emerald-500/30"
                                >
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Subir Imagen al Proyecto
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- FORMULARIO PARA AGREGAR LINKS --}}
                <div class="p-8 bg-gradient-to-br from-blue-50 to-indigo-50 border-t border-gray-200">
                    <div class="max-w-3xl mx-auto">
                        <div class="mb-8">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center mr-4 shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                </div>
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Agregar Nuevo Enlace</h2>
                                    <p class="text-gray-600 mt-1">Añada un enlace externo relacionado con el proyecto</p>
                                </div>
                            </div>
                        </div>

                        <form method="POST"
                              action="{{ route('linkproyecto.store') }}"
                              class="space-y-8">
                            @csrf
                            <input type="hidden" name="id_proyecto" value="{{ $proyecto->id }}">

                            <!-- URL del Archivo/Link -->
                            <div class="space-y-3">
                                <label for="url_archivo" class="block text-sm font-semibold text-gray-800 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    URL del Enlace
                                    <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                        </svg>
                                    </div>
                                    <input
                                        type="url"
                                        id="url_archivo"
                                        name="url_archivo"
                                        placeholder="https://ejemplo.com/recurso"
                                        class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-xl focus:ring-3 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300 placeholder-gray-400 shadow-sm"
                                        required
                                        pattern="https?://.+"
                                        title="Por favor, ingresa una URL válida (http:// o https://)"
                                    />
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Asegúrese de incluir el protocolo (http:// o https://)</p>
                            </div>

                            <!-- Descripción -->
                            <div class="space-y-3">
                                <label for="descripcion" class="block text-sm font-semibold text-gray-800 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Descripción del Enlace
                                </label>
                                <textarea
                                    id="descripcion"
                                    name="descripcion"
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-3 focus:ring-blue-500/30 focus:border-blue-500 transition-all duration-300 placeholder-gray-400 bg-white resize-none shadow-sm"
                                    placeholder="Describa el propósito o contenido de este enlace. Ej: 'Repositorio GitHub', 'Documentación técnica', 'Demo en vivo', etc."
                                ></textarea>
                                <p class="text-sm text-gray-500">Opcional, pero recomendado para mejor organización</p>
                            </div>

                            <!-- Botón de Submit -->
                            <div class="flex items-center justify-end pt-8 border-t border-gray-200">
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-8 py-3.5 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-blue-500/30"
                                >
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Agregar Enlace
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- LISTA DE LINKS EXISTENTES --}}
                @if(isset($linksProyecto) && $linksProyecto->count())
                <div class="p-8 border-t border-gray-200">
                    <div class="max-w-3xl mx-auto">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900">Enlaces del Proyecto</h3>
                                <p class="text-gray-600 mt-1">Recursos externos asociados</p>
                            </div>
                            <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                {{ $linksProyecto->count() }} enlaces
                            </span>
                        </div>

                        <div class="space-y-4">
                            @foreach($linksProyecto as $link)
                            <div class="group bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:border-blue-300">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1 mr-4">
                                        <a href="{{ $link->url_archivo }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="text-blue-600 hover:text-blue-800 font-medium flex items-center group-hover:underline decoration-2">
                                            <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-lg flex items-center justify-center mr-4 group-hover:from-blue-200 group-hover:to-indigo-200 transition-all duration-300">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="break-all">{{ $link->url_archivo }}</span>
                                                @if($link->descripcion)
                                                <p class="text-gray-600 text-sm mt-2">{{ $link->descripcion }}</p>
                                                @endif
                                            </div>
                                        </a>
                                    </div>
                                    <form action="{{ route('linkproyecto.destroy', $link->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('¿Está seguro de eliminar este enlace?')"
                                                class="text-gray-400 hover:text-red-500 p-2 rounded-lg hover:bg-red-50 transition-all duration-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODAL DE CONFIRMACIÓN PARA ELIMINAR IMÁGENES --}}
    <div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 max-w-md mx-4 shadow-2xl">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-red-100 to-pink-100 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">¿Eliminar imagen?</h3>
                    <p class="text-gray-600">Esta acción no se puede deshacer</p>
                </div>
            </div>

            <p id="confirmMessage" class="text-gray-700 mb-8 p-4 bg-gray-50 rounded-lg"></p>

            <div class="flex justify-end gap-4">
                <button type="button" onclick="cerrarModal()" class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-all duration-200">
                    Cancelar
                </button>
                <button type="button" id="confirmDeleteBtn" class="px-6 py-3 bg-gradient-to-r from-red-600 to-pink-600 text-white font-medium rounded-lg hover:from-red-700 hover:to-pink-700 shadow-lg transition-all duration-200">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .shadow-sm {
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .shadow-md {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .shadow-lg {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .shadow-xl {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .shadow-2xl {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
    </style>

    <script>
        let mediaIdToDelete = null;

        function confirmarEliminarImagen(id, descripcion) {
            mediaIdToDelete = id;

            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmMessage').textContent =
                `¿Estás seguro de eliminar "${descripcion || 'esta imagen'}"? Esta acción eliminará permanentemente la imagen del proyecto.`;
        }

        function cerrarModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            mediaIdToDelete = null;
        }

        // Configurar botón de confirmación
        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            if (mediaIdToDelete) {
                // Crear formulario dinámicamente
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/adminmediaproyectos/${mediaIdToDelete}`;
                form.style.display = 'none';

                // Agregar CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(csrfToken);

                // Agregar método DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Agregar formulario al documento y enviar
                document.body.appendChild(form);
                form.submit();
            }

            cerrarModal();
        });

        // Cerrar modal con Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                cerrarModal();
            }
        });

        // Cerrar modal al hacer clic fuera
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                cerrarModal();
            }
        });

        // ========== Funciones para subir imágenes ==========
        function validateImage(input) {
            const file = input.files[0];
            const errorDiv = document.getElementById('image-error');
            const fileInfoDiv = document.getElementById('file-info');
            const errorMessage = document.getElementById('error-message');
            const fileMessage = document.getElementById('file-message');
            const dropzone = document.getElementById('dropzone');
            const dropzoneContent = document.getElementById('dropzone-content');

            errorDiv.classList.add('hidden');
            fileInfoDiv.classList.add('hidden');

            if (file) {
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    showError('Formato no válido. Use JPG, PNG, GIF o WebP.');
                    input.value = '';
                    return false;
                }

                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    showError('La imagen es muy grande. Máximo 2MB.');
                    input.value = '';
                    return false;
                }

                const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                fileMessage.textContent = `✓ Archivo listo: ${file.name} (${fileSizeMB} MB)`;
                fileInfoDiv.classList.remove('hidden');

                dropzone.classList.remove('border-gray-300', 'border-red-300');
                dropzone.classList.add('border-emerald-300', 'bg-gradient-to-br', 'from-emerald-50', 'to-green-50');

                showPreview(file);

            } else {
                resetDropzone();
            }

            return true;
        }

        function showError(message) {
            const errorDiv = document.getElementById('image-error');
            const errorMessage = document.getElementById('error-message');
            const dropzone = document.getElementById('dropzone');

            errorMessage.textContent = message;
            errorDiv.classList.remove('hidden');

            dropzone.classList.remove('border-gray-300', 'border-emerald-300', 'from-emerald-50', 'to-green-50');
            dropzone.classList.add('border-red-300', 'bg-gradient-to-br', 'from-red-50', 'to-pink-50');
        }

        function resetDropzone() {
            const dropzone = document.getElementById('dropzone');
            dropzone.classList.remove('border-red-300', 'bg-red-50', 'border-emerald-300', 'from-emerald-50', 'to-green-50', 'from-red-50', 'to-pink-50');
            dropzone.classList.add('border-gray-300', 'bg-gradient-to-br', 'from-gray-50', 'to-white');
        }

        function showPreview(file) {
            const reader = new FileReader();
            const previewContainer = document.getElementById('image-preview');
            const preview = document.getElementById('preview');

            reader.onload = function(e) {
                preview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            };

            reader.readAsDataURL(file);
        }

        // Drag & Drop functionality
        const dropzone = document.getElementById('dropzone');

        dropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.remove('border-gray-300');
            this.classList.add('border-blue-400', 'bg-gradient-to-br', 'from-blue-50', 'to-indigo-50');
        });

        dropzone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            if (!this.classList.contains('border-emerald-300') && !this.classList.contains('border-red-300')) {
                this.classList.remove('border-blue-400', 'from-blue-50', 'to-indigo-50');
                this.classList.add('border-gray-300', 'bg-gradient-to-br', 'from-gray-50', 'to-white');
            }
        });

        dropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('border-blue-400', 'from-blue-50', 'to-indigo-50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                document.getElementById('url_imagen').files = files;
                validateImage(document.getElementById('url_imagen'));
            }
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            const fileInput = document.getElementById('url_imagen');
            const file = fileInput.files[0];

            if (file) {
                const maxSize = 2 * 1024 * 1024;
                if (file.size > maxSize) {
                    e.preventDefault();
                    showError('La imagen es muy grande. Máximo 2MB.');
                    return false;
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('url_imagen');
            if (fileInput.files.length > 0) {
                validateImage(fileInput);
            }
        });
    </script>
</x-app-layout>
