<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">Archivos del Cliente</h1>
                <p class="text-gray-600">{{ $cliente->nombre_completo }}</p>
                <p class="text-gray-500 text-sm">{{ $cliente->email }}</p>
            </div>
            <a href="{{ route('cliente.show', $cliente->id_user) }}"
               class="text-blue-600 hover:text-blue-800">
                ← Volver a clientes
            </a>
        </div>

        <!-- Lista de archivos -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-semibold">Archivos subidos ({{ $archivos->count() }})</h2>
                <button onclick="document.getElementById('subirArchivoForm').classList.toggle('hidden')"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                    <i class="fas fa-plus mr-2"></i> Nuevo Archivo
                </button>
            </div>

            @if($archivos->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Archivo
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tamaño
                                </th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($archivos as $archivo)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-lg flex items-center justify-center"
                                                     style="background-color: {{ $archivo->color ?? '#3B82F6' }}">
                                                    <i class="{{ $archivo->icono ?? 'fas fa-file' }} text-white"></i>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $archivo->nombre_archivo }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $archivo->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{-- Aquí podrías mostrar el tamaño si lo guardas en la BD --}}
                                        -
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-3">
                                            <a href="{{ url('usuario/cliente/archivos/ver/' . $archivo->id) }}"
                                               class="text-blue-600 hover:text-blue-900"
                                               target="_blank">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            <a href="{{ url('usuario/cliente/archivos/descargar/' . $archivo->id) }}"
                                               class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-download"></i> Descargar
                                            </a>
                                            <form action="{{ url('usuario/cliente/archivos/eliminar/' . $archivo->id) }}"
                                                  method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900"
                                                        onclick="return confirm('¿Eliminar este archivo?')">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-folder-open text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-600 mb-2">No hay archivos</h3>
                    <p class="text-gray-500">Aún no se han subido archivos para este cliente.</p>
                </div>
            @endif
        </div>

        <!-- Formulario para subir archivo (oculto por defecto) -->
        <div id="subirArchivoForm" class="hidden bg-white shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Subir nuevo archivo</h3>

            <form action="{{ url('usuario/cliente/' . $cliente->id . '/archivos/subir') }}"
                    method="POST"
                    enctype="multipart/form-data">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Seleccionar archivo (máx. 50MB)
                        </label>
                        <input type="file"
                                name="archivo"
                                id="archivo"
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.txt,.zip,.rar"
                                class="block w-full text-sm text-gray-500 border border-gray-300 rounded-lg p-2"
                                required>
                        <p class="mt-1 text-xs text-gray-500">
                            Formatos: PDF, imágenes (JPG, PNG), documentos (DOC, DOCX, XLS, XLSX), TXT, ZIP, RAR
                        </p>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                onclick="document.getElementById('subirArchivoForm').classList.add('hidden')"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-upload mr-2"></i> Subir Archivo
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Mensajes de éxito/error -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif
    </div>
</x-app-layout>
