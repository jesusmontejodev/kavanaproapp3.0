<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Tarjeta principal -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="bg-gradient-to-r from-blue-50 via-white to-white p-8">
                    <!-- Grid de información principal -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
                        <!-- Información Personal -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="bg-blue-100 w-10 h-10 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    Información Personal
                                </h3>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                        Nombre Completo
                                    </label>
                                    <p class="text-gray-900 font-medium">
                                        {{ $cliente->nombre_completo }}
                                    </p>
                                </div>
                                <div class="pt-3 border-t border-gray-100">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                        Email
                                    </label>
                                    <p class="text-gray-900 font-medium">
                                        {{ $cliente->email ?? 'No especificado' }}
                                    </p>
                                </div>
                                <div class="pt-3 border-t border-gray-100">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                        Teléfono
                                    </label>
                                    <p class="text-gray-900 font-medium">
                                        {{ $cliente->telefono ?? 'No especificado' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Inmueble -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="bg-emerald-100 w-10 h-10 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-home text-emerald-600"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    Información del Inmueble
                                </h3>
                            </div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                        Inmueble Comprado
                                    </label>
                                    <p class="text-gray-900 font-medium">
                                        {{ $cliente->inmueble_comprado ?? 'No especificado' }}
                                    </p>
                                </div>
                                <div class="pt-3 border-t border-gray-100">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                        Tipo de Inmueble
                                    </label>
                                    <p class="text-gray-900 font-medium">
                                        {{ $cliente->tipo_inmueble ?? 'No especificado' }}
                                    </p>
                                </div>
                                <div class="pt-3 border-t border-gray-100">
                                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">
                                        Precio de Compra
                                    </label>
                                    <p class="text-2xl font-bold text-emerald-600">
                                        @if($cliente->precio_compra)
                                            ${{ number_format($cliente->precio_compra, 2) }}
                                        @else
                                            No especificado
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Progreso y Estado -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="bg-purple-100 w-10 h-10 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-tasks text-purple-600"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    Estado del Proceso
                                </h3>
                            </div>
                            <div class="space-y-6">
                                <!-- Fechas clave -->
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                                        <i class="fas fa-calendar-check text-blue-500"></i>
                                        <div>
                                            <p class="text-xs text-gray-500">Entrega Estimada</p>
                                            <p class="text-sm font-semibold text-gray-900">{{ $cliente->fecha_entrega_estimada ?? 'No especificada' }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                                        <i class="fas fa-check-circle text-green-500"></i>
                                        <div>
                                            <p class="text-xs text-gray-500">Compra Realizada</p>
                                            <p class="text-sm font-semibold text-gray-900">{{ $cliente->fecha_compra ?? 'No especificada' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grid secundario -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
                        <!-- Dirección del Inmueble -->
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="bg-amber-100 w-10 h-10 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-map-marker-alt text-amber-600"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    Dirección del Inmueble
                                </h3>
                            </div>
                            <div class="flex items-start gap-3">
                                <i class="fas fa-map-pin text-gray-400 mt-1"></i>
                                <p class="text-gray-700 font-medium">
                                    {{ $cliente->direccion_inmueble ?? 'No especificada' }}
                                </p>
                            </div>
                        </div>


                    <!-- Observaciones -->
                    <div>
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="bg-indigo-100 w-10 h-10 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clipboard-list text-indigo-600"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    Observaciones de Entrega
                                </h3>
                            </div>
                            @if($cliente->observaciones_entrega)
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-gray-700 whitespace-pre-line">
                                        {{ $cliente->observaciones_entrega }}
                                    </p>
                                </div>
                            @else
                                <div class="text-center py-8">
                                    <i class="fas fa-clipboard text-gray-300 text-4xl mb-3"></i>
                                    <p class="text-gray-500 font-medium">No hay observaciones registradas</p>
                                    <p class="text-sm text-gray-400 mt-1">Agrega observaciones en la edición del cliente</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Notas adicionales -->
                    @if($cliente->notas)
                    <div class="mt-8">
                        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="bg-teal-100 w-10 h-10 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-notes-medical text-teal-600"></i>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900">
                                    Notas Adicionales
                                </h3>
                            </div>
                            <div class="bg-teal-50 rounded-lg p-4 border border-teal-100">
                                <p class="text-gray-700 whitespace-pre-line">
                                    {{ $cliente->notas }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
                        <form>
                            @csrf
                            <input name="id_cliente" id="" value="{{ $cliente->id }}">
                            <input type="file">
                            <input name="nombre_archivo">
                            <button>Subir archivo</button>
                        </form>

                    </div>

                    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">

                    </div>

                    <!-- Información del sistema -->
                    <div class="mt-10 pt-8 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-calendar-plus text-gray-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Cliente desde</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $cliente->created_at->format('d/m/Y') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-sync-alt text-gray-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Última actualización</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $cliente->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>

                            @if($cliente->lead_id)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-external-link-alt text-gray-500"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Lead Original</p>
                                    <p class="text-sm font-semibold text-gray-900">#{{ $cliente->lead_id }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>




                </div>
            </div>
        </div>

    </div>
</x-app-layout>
