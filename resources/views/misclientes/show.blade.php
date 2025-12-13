<x-app-layout>
    <!-- Sección de Archivos SIN JAVASCRIPT -->
    <div class="mt-8">
        <!-- Subir Archivo -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="bg-blue-100 w-10 h-10 rounded-lg flex items-center justify-center">
                    <span class="text-blue-600 font-bold">↑</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900">
                    Subir Nuevo Archivo
                </h3>
            </div>

            <form method="POST" action="{{ route('cliente.archivos.store') }}"
                enctype="multipart/form-data" class="space-y-4">
                @csrf

                <input type="hidden" name="id_cliente" value="{{ $cliente->id }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Seleccionar Archivo
                    </label>
                    <div class="relative">
                        <input type="file" name="archivo" id="archivo"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx,.txt" required>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Formatos permitidos: PDF, JPG, PNG, DOC, XLS, TXT (Máx. 50MB)
                    </p>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-5 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow">
                        <span class="mr-2">↑</span> Subir Archivo
                    </button>
                </div>
            </form>
        </div>

        <!-- Lista de Archivos -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-100 w-10 h-10 rounded-lg flex items-center justify-center">
                        <span class="text-emerald-600 font-bold">📁</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">
                            Archivos del Cliente
                        </h3>
                        <p class="text-sm text-gray-500">{{ $cliente->archivos->count() }} archivos</p>
                    </div>
                </div>
            </div>

            <!-- Lista de archivos -->
            <div class="space-y-3">
                @forelse($cliente->archivos as $archivo)
                    <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center gap-4 flex-1 min-w-0">
                            <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0"
                                    style="background-color: {{ $archivo->color === 'red' ? '#FEE2E2' : ($archivo->color === 'green' ? '#D1FAE5' : ($archivo->color === 'blue' ? '#DBEAFE' : ($archivo->color === 'purple' ? '#EDE9FE' : '#F3F4F6'))) }}">
                                @if($archivo->extension === 'pdf')
                                    <span class="text-lg font-bold" style="color: {{ $archivo->color === 'red' ? '#DC2626' : ($archivo->color === 'green' ? '#059669' : ($archivo->color === 'blue' ? '#2563EB' : ($archivo->color === 'purple' ? '#7C3AED' : '#6B7280'))) }}">📄</span>
                                @elseif(in_array($archivo->extension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <span class="text-lg font-bold" style="color: {{ $archivo->color === 'red' ? '#DC2626' : ($archivo->color === 'green' ? '#059669' : ($archivo->color === 'blue' ? '#2563EB' : ($archivo->color === 'purple' ? '#7C3AED' : '#6B7280'))) }}">🖼️</span>
                                @elseif(in_array($archivo->extension, ['doc', 'docx']))
                                    <span class="text-lg font-bold" style="color: {{ $archivo->color === 'red' ? '#DC2626' : ($archivo->color === 'green' ? '#059669' : ($archivo->color === 'blue' ? '#2563EB' : ($archivo->color === 'purple' ? '#7C3AED' : '#6B7280'))) }}">📝</span>
                                @elseif(in_array($archivo->extension, ['xls', 'xlsx']))
                                    <span class="text-lg font-bold" style="color: {{ $archivo->color === 'red' ? '#DC2626' : ($archivo->color === 'green' ? '#059669' : ($archivo->color === 'blue' ? '#2563EB' : ($archivo->color === 'purple' ? '#7C3AED' : '#6B7280'))) }}">📊</span>
                                @elseif($archivo->extension === 'txt')
                                    <span class="text-lg font-bold" style="color: {{ $archivo->color === 'red' ? '#DC2626' : ($archivo->color === 'green' ? '#059669' : ($archivo->color === 'blue' ? '#2563EB' : ($archivo->color === 'purple' ? '#7C3AED' : '#6B7280'))) }}">📃</span>
                                @else
                                    <span class="text-lg font-bold" style="color: {{ $archivo->color === 'red' ? '#DC2626' : ($archivo->color === 'green' ? '#059669' : ($archivo->color === 'blue' ? '#2563EB' : ($archivo->color === 'purple' ? '#7C3AED' : '#6B7280'))) }}">📎</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-900 truncate" title="{{ $archivo->nombre_archivo }}">
                                    {{ $archivo->nombre_archivo }}
                                </h4>
                                <div class="flex flex-wrap items-center gap-3 mt-1">
                                    <span class="text-xs text-gray-500">{{ $archivo->tamano_formateado }}</span>
                                    <span class="text-xs px-2 py-1 rounded-full capitalize"
                                            style="background-color: {{ $archivo->color === 'red' ? '#FEE2E2' : ($archivo->color === 'green' ? '#D1FAE5' : ($archivo->color === 'blue' ? '#DBEAFE' : ($archivo->color === 'purple' ? '#EDE9FE' : '#F3F4F6'))) }};
                                                color: {{ $archivo->color === 'red' ? '#991B1B' : ($archivo->color === 'green' ? '#065F46' : ($archivo->color === 'blue' ? '#1E40AF' : ($archivo->color === 'purple' ? '#5B21B6' : '#374151'))) }}">
                                        {{ $archivo->extension }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ $archivo->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 flex-shrink-0 ml-4">
                            @if(in_array(strtolower($archivo->extension), ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'txt']))
                            <a href="{{ route('cliente.archivos.ver', $archivo->id) }}" target="_blank"
                                class="px-3 py-2 bg-blue-50 text-blue-700 hover:bg-blue-100 rounded-lg transition-colors text-sm font-medium"
                                title="Ver archivo">
                                Ver
                            </a>
                            @endif

                            <a href="{{ route('cliente.archivos.download', $archivo->id) }}"
                                class="px-3 py-2 bg-green-50 text-green-700 hover:bg-green-100 rounded-lg transition-colors text-sm font-medium"
                                title="Descargar archivo">
                                Descargar
                            </a>

                            <form method="POST" action="{{ route('cliente.archivos.destroy', $archivo->id) }}"
                                    class="inline"
                                    onsubmit="return confirm('¿Estás seguro de eliminar el archivo \'{{ addslashes($archivo->nombre_archivo) }}\'?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-3 py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-lg transition-colors text-sm font-medium"
                                        title="Eliminar archivo">
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <span class="text-gray-300 text-4xl mb-3 block">📁</span>
                        <p class="text-gray-500 font-medium">No hay archivos subidos</p>
                        <p class="text-sm text-gray-400 mt-1">Sube el primer archivo usando el formulario superior</p>
                    </div>
                @endforelse
            </div>

            <!-- Paginación si hay muchos archivos -->
            @if($cliente->archivos->count() > 20)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-center text-sm text-gray-500">
                    Mostrando los últimos 20 archivos.
                    <a href="{{ route('cliente.archivos.index', $cliente->id) }}" class="text-blue-600 hover:text-blue-800 font-medium underline">
                        Ver todos los archivos
                    </a>
                </p>
            </div>
            @endif
        </div>
    </div>

</x-app-layout>
