<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Clientes de {{ $usuario->name }}</h1>
            <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800">
                ← Volver
            </a>
        </div>

        @if($clientes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($clientes as $cliente)
                    <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $cliente->nombre_completo }}</h3>
                                <p class="text-gray-600 text-sm">{{ $cliente->email }}</p>
                                @if($cliente->telefono)
                                    <p class="text-gray-600 text-sm">{{ $cliente->telefono }}</p>
                                @endif
                            </div>
                            {{-- <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($cliente->estado_entrega == 'entrega_finalizada') bg-green-100 text-green-800
                                @elseif($cliente->esta_proximo_a_entregar) bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ $cliente->estado_entrega_texto }}
                            </span> --}}
                        </div>

                        @if($cliente->inmueble_comprado)
                            <p class="text-gray-700 mb-2">
                                <span class="font-medium">Inmueble:</span> {{ $cliente->inmueble_comprado }}
                            </p>
                        @endif

                        @if($cliente->precio_compra)
                            <p class="text-gray-700 mb-2">
                                <span class="font-medium">Precio:</span> {{ $cliente->precio_compra_formateado }}
                            </p>
                        @endif

                        @if($cliente->fecha_entrega_estimada)
                            <p class="text-gray-700 mb-4">
                                <span class="font-medium">Entrega estimada:</span>
                                {{ \Carbon\Carbon::parse($cliente->fecha_entrega_estimada)->format('d/m/Y') }}
                            </p>
                        @endif

                        <!-- Barra de progreso -->
                        {{-- <div class="mb-4">
                            <div class="flex justify-between text-sm text-gray-600 mb-1">
                                <span>Progreso entrega</span>
                                <span>{{ $cliente->progreso_entrega }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full"
                                    style="width: {{ $cliente->progreso_entrega }}%"></div>
                            </div>
                        </div> --}}

                        <!-- Botones de acción -->
                        <div class="flex space-x-2">
                            <a href="{{ route('cliente.archivosclienteusuario', $cliente->id) }}"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg transition">
                                <i class="fas fa-folder-open mr-2"></i> Archivos
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No hay clientes</h3>
                <p class="text-gray-500">Este usuario no tiene clientes asignados.</p>
            </div>
        @endif
    </div>
</x-app-layout>
