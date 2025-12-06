<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Proyectos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Título principal -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Gestión de Proyectos</h1>
                <p class="text-gray-600 mt-2">Crea y administra tus proyectos desde esta plataforma</p>
            </div>

            <!-- Formulario de creación -->
            <form action="{{ route('adminproyectos.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                @csrf

                <!-- Header del Formulario -->
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Crear Nuevo Proyecto</h2>
                    <p class="text-sm text-gray-600 mt-1">Complete la información del proyecto</p>
                </div>

                <!-- Grid de Campos -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                    <!-- Nombre del Proyecto -->
                    <div class="space-y-2">
                        <label for="nombre" class="block text-sm font-semibold text-gray-800">
                            Nombre del Proyecto <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="nombre"
                            name="nombre"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 placeholder-gray-400 bg-white"
                            placeholder="Ingrese el nombre del proyecto"
                        >
                    </div>

                    <!-- Link del Proyecto -->
                    <div class="space-y-2">
                        <label for="link_proyecto" class="block text-sm font-semibold text-gray-800">
                            Enlace del Proyecto
                        </label>
                        <input
                            type="url"
                            id="link_proyecto"
                            name="link_proyecto"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 placeholder-gray-400 bg-white"
                            placeholder="https://ejemplo.com"
                        >
                    </div>

                </div>

                <!-- Descripción (Full Width) -->
                <div class="mb-6">
                    <label for="descripcion" class="block text-sm font-semibold text-gray-800 mb-2">
                        Descripción del Proyecto
                    </label>
                    <textarea
                        id="descripcion"
                        name="descripcion"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 placeholder-gray-400 bg-white resize-none"
                        placeholder="Describa los detalles y objetivos del proyecto..."
                    ></textarea>
                </div>

                <!-- Upload de Imagen -->
                <div class="mb-8">
                    <label for="url_imagen" class="block text-sm font-semibold text-gray-800 mb-2">
                        Imagen de Portada <span class="text-red-500">*</span>
                        <span class="text-sm text-gray-500 font-normal">(Máximo 2MB)</span>
                    </label>

                    <div class="flex items-center justify-center w-full">
                        <label for="url_imagen" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all duration-200 group" id="dropzone">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="dropzone-content">
                                <svg class="w-8 h-8 mb-3 text-gray-400 group-hover:text-gray-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500">
                                    <span class="font-semibold">Haga clic para subir</span> o arrastre y suelte
                                </p>
                                <p class="text-xs text-gray-400">PNG, JPG, GIF (MAX. 2MB)</p>
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

                    <!-- Mensaje de error -->
                    <div id="image-error" class="mt-2 hidden">
                        <p class="text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span id="error-message"></span>
                        </p>
                    </div>

                    <!-- Información del archivo -->
                    <div id="file-info" class="mt-2 hidden">
                        <p class="text-sm text-green-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span id="file-message"></span>
                        </p>
                    </div>
                </div>

                    <!-- Preview de imagen (se agregará con JavaScript) -->
                    <div id="image-preview" class="mt-4 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-2">Vista previa:</p>
                        <img id="preview" class="max-w-xs rounded-lg shadow-md border border-gray-200">
                    </div>
                </div>

                <!-- Botón de Submit -->
                <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                    <button
                        type="submit"
                        class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-lg shadow-md transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Crear Proyecto
                    </button>
                </div>
            </form>

            <!-- Sección de proyectos existentes -->
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Proyectos Existentes</h3>
                <p class="text-gray-600">Gestiona todos tus proyectos desde esta sección</p>
            </div>

            <!-- Grid de Cards de Proyectos -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($proyectos as $proyecto)
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Imagen del proyecto -->
                    <div class="h-48 overflow-hidden">
                        @if($proyecto->url_imagen)
                        <img
                            src="{{ asset($proyecto->url_imagen) }}"
                            alt="{{ $proyecto->nombre }}"
                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
                        >
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center">
                            <svg class="w-16 h-16 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        @endif
                    </div>

                    <!-- Contenido de la card -->
                    <div class="p-5">
                        <!-- Header con título y acciones -->
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-bold text-gray-900 line-clamp-1 flex-1 mr-2">{{ $proyecto->nombre }}</h3>
                            <div class="flex space-x-1">
                                <!-- Botón Editar -->
                                <a href="{{ route('adminproyectos.show', $proyecto->id) }}"
                                   class="inline-flex items-center p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition-colors duration-200"
                                   title="Editar proyecto">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                <!-- Botón Eliminar -->
                                <form method="POST"
                                      action="{{ route('adminproyectos.destroy', $proyecto->id) }}"
                                      onsubmit="return confirm('¿Está seguro de eliminar el proyecto \"{{ $proyecto->nombre }}\"?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center p-2 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition-colors duration-200"
                                            title="Eliminar proyecto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Descripción -->
                        @if($proyecto->descripcion)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $proyecto->descripcion }}</p>
                        @endif

                        <!-- Enlace del proyecto -->
                        @if($proyecto->link_proyecto)
                        <div class="mb-4">
                            <a href="{{ $proyecto->link_proyecto }}"
                               target="_blank"
                               class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                <span class="truncate max-w-[180px]">{{ $proyecto->link_proyecto }}</span>
                            </a>
                        </div>
                        @endif

                        <!-- Footer de la card -->
                        <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                            <span class="text-xs text-gray-500">
                                Creado {{ $proyecto->created_at->diffForHumans() }}
                            </span>
                            <a href="{{ route('adminproyectos.show', $proyecto->id) }}"
                               class="text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                Ver detalles →
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Mensaje cuando no hay proyectos -->
            @if($proyectos->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No hay proyectos creados</h3>
                <p class="text-gray-600">Comienza creando tu primer proyecto usando el formulario superior.</p>
            </div>
            @endif
        </div>
    </div>

    <script>
        document.getElementById('url_imagen').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('image-preview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }

                reader.readAsDataURL(file);
            } else {
                previewContainer.classList.add('hidden');
            }
        });
    </script>

    <script>
    function validateImage(input) {
        const file = input.files[0];
        const errorDiv = document.getElementById('image-error');
        const fileInfoDiv = document.getElementById('file-info');
        const errorMessage = document.getElementById('error-message');
        const fileMessage = document.getElementById('file-message');
        const dropzone = document.getElementById('dropzone');
        const dropzoneContent = document.getElementById('dropzone-content');

        // Ocultar mensajes anteriores
        errorDiv.classList.add('hidden');
        fileInfoDiv.classList.add('hidden');

        if (file) {
            // Validar tipo de archivo
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                showError('Formato no válido. Use JPG, PNG, GIF o WebP.');
                input.value = '';
                return false;
            }

            // Validar tamaño (2MB = 2 * 1024 * 1024 bytes)
            const maxSize = 2 * 1024 * 1024;
            if (file.size > maxSize) {
                showError('La imagen es muy grande. Máximo 2MB.');
                input.value = '';
                return false;
            }

            // Mostrar información del archivo
            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
            fileMessage.textContent = `Archivo válido: ${file.name} (${fileSizeMB} MB)`;
            fileInfoDiv.classList.remove('hidden');

            // Cambiar estilo del dropzone a éxito
            dropzone.classList.remove('border-gray-300', 'border-red-300');
            dropzone.classList.add('border-green-300', 'bg-green-50');

            // Mostrar vista previa
            showPreview(file);

        } else {
            // Restaurar estilo original
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

        // Cambiar estilo del dropzone a error
        dropzone.classList.remove('border-gray-300', 'border-green-300');
        dropzone.classList.add('border-red-300', 'bg-red-50');
    }

    function resetDropzone() {
        const dropzone = document.getElementById('dropzone');
        dropzone.classList.remove('border-red-300', 'bg-red-50', 'border-green-300', 'bg-green-50');
        dropzone.classList.add('border-gray-300', 'bg-gray-50');
    }

    function showPreview(file) {
        const reader = new FileReader();
        const dropzoneContent = document.getElementById('dropzone-content');

        reader.onload = function(e) {
            // Crear elemento de preview si no existe
            let preview = document.getElementById('image-preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.id = 'image-preview';
                preview.className = 'mt-4 text-center';
                dropzoneContent.parentNode.appendChild(preview);
            }

            preview.innerHTML = `
                <p class="text-sm font-medium text-gray-700 mb-2">Vista previa:</p>
                <img src="${e.target.result}" class="max-w-xs rounded-lg shadow-md border border-gray-200 mx-auto">
            `;
        };

        reader.readAsDataURL(file);
    }

    // Drag & Drop functionality
    const dropzone = document.getElementById('dropzone');

    dropzone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('border-blue-300', 'bg-blue-50');
    });

    dropzone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-300', 'bg-blue-50');
    });

    dropzone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('border-blue-300', 'bg-blue-50');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('url_imagen').files = files;
            validateImage(document.getElementById('url_imagen'));
        }
    });

    // Validar al enviar el formulario
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
    </script>

</x-app-layout>
