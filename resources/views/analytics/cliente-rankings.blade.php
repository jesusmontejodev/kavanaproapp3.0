<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📊 Rankings de Clientes
            </h2>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-500">
                    ⏰ {{ $ultima_actualizacion }}
                </div>
                <a href="{{ url()->current() }}"
                    class="px-3 py-1 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition duration-200">
                    🔄 Actualizar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Mensajes de error/success -->
            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <!-- Estadísticas Generales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                            <span class="text-2xl">👥</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Usuarios</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $estadisticas['total_usuarios'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                            <span class="text-2xl">✅</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Usuarios con Clientes</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $estadisticas['usuarios_con_clientes'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                            <span class="text-2xl">📈</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tasa de Conversión</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $estadisticas['tasa_conversion_global'] }}%</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                            <span class="text-2xl">💰</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Venta Total</p>
                            <h3 class="text-2xl font-bold text-gray-800">
                                ${{ number_format($estadisticas['venta_total'], 2) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rankings -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Ranking por Tasa de Conversión -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 border-b bg-gradient-to-r from-blue-50 to-blue-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-800">
                                📊 Tasa de Conversión
                            </h3>
                            <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm">
                                Leads → Clientes
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        @if($ranking_conversion->count() > 0)
                            @foreach($ranking_conversion as $item)
                                <div class="ranking-item p-4 mb-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition duration-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <!-- Contenedor de posición/foto -->
                                            <div class="relative mr-3">
                                                @if($item['foto_perfil'] && file_exists(public_path($item['foto_perfil'])))
                                                    <img src="{{ asset($item['foto_perfil']) }}"
                                                         alt="{{ $item['usuario'] }}"
                                                         class="w-12 h-12 rounded-full border-2
                                                         @if($item['posicion'] == 1) border-yellow-400
                                                         @elseif($item['posicion'] == 2) border-gray-300
                                                         @elseif($item['posicion'] == 3) border-orange-400
                                                         @else border-blue-300 @endif">
                                                    @if($item['posicion'] <= 3)
                                                        <div class="absolute -top-2 -right-2 text-lg">
                                                            @if($item['posicion'] == 1)🥇
                                                            @elseif($item['posicion'] == 2)🥈
                                                            @elseif($item['posicion'] == 3)🥉
                                                            @endif
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="relative">
                                                        <div class="w-12 h-12 flex items-center justify-center rounded-full font-bold text-lg
                                                            @if($item['posicion'] == 1) bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-600 border-2 border-yellow-400
                                                            @elseif($item['posicion'] == 2) bg-gradient-to-r from-gray-100 to-gray-200 text-gray-600 border-2 border-gray-300
                                                            @elseif($item['posicion'] == 3) bg-gradient-to-r from-orange-100 to-orange-200 text-orange-600 border-2 border-orange-400
                                                            @else bg-gradient-to-r from-blue-100 to-blue-200 text-blue-600 border-2 border-blue-300 @endif">
                                                            {{ strtoupper(substr($item['usuario'], 0, 1)) }}
                                                        </div>
                                                        @if($item['posicion'] <= 3)
                                                            <div class="absolute -top-2 -right-2 text-lg">
                                                                @if($item['posicion'] == 1)🥇
                                                                @elseif($item['posicion'] == 2)🥈
                                                                @elseif($item['posicion'] == 3)🥉
                                                                @endif
                                                            </div>
                                                        @else
                                                            <div class="absolute -top-2 -right-2 text-xs font-bold bg-white rounded-full px-1.5 py-0.5 border shadow-sm">
                                                                {{ $item['posicion'] }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                            <div>
                                                <h4 class="font-semibold text-gray-800">{{ $item['usuario'] }}</h4>
                                                <p class="text-xs text-gray-500">{{ $item['email'] }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-gray-800">{{ $item['tasa_conversion'] }}%</div>
                                            <p class="text-xs text-gray-500">
                                                {{ $item['clientes_conseguidos'] }}/{{ $item['leads_totales'] }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-500 h-2 rounded-full"
                                                    style="width: {{ min($item['tasa_conversion'], 100) }}%">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <span class="text-3xl mb-3">📊</span>
                                <p>No hay datos de conversión disponibles</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Top Vendedores -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 border-b bg-gradient-to-r from-green-50 to-green-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-800">
                                🏆 Top Vendedores
                            </h3>
                            <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-sm">
                                Por Monto Total
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        @if($top_vendedores->count() > 0)
                            @foreach($top_vendedores as $item)
                                <div class="ranking-item p-4 mb-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition duration-200">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <!-- Contenedor de posición/foto -->
                                            <div class="relative mr-3">
                                                @if($item['foto_perfil'] && file_exists(public_path($item['foto_perfil'])))
                                                    <img src="{{ asset($item['foto_perfil']) }}"
                                                         alt="{{ $item['usuario'] }}"
                                                         class="w-12 h-12 rounded-full border-2
                                                         @if($item['posicion'] == 1) border-yellow-400
                                                         @elseif($item['posicion'] == 2) border-gray-300
                                                         @elseif($item['posicion'] == 3) border-orange-400
                                                         @else border-green-300 @endif">
                                                    @if($item['posicion'] <= 3)
                                                        <div class="absolute -top-2 -right-2 text-lg">
                                                            @if($item['posicion'] == 1)👑
                                                            @elseif($item['posicion'] == 2)🥈
                                                            @elseif($item['posicion'] == 3)🥉
                                                            @endif
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="relative">
                                                        <div class="w-12 h-12 flex items-center justify-center rounded-full font-bold text-lg
                                                            @if($item['posicion'] == 1) bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-600 border-2 border-yellow-400
                                                            @elseif($item['posicion'] == 2) bg-gradient-to-r from-gray-100 to-gray-200 text-gray-600 border-2 border-gray-300
                                                            @elseif($item['posicion'] == 3) bg-gradient-to-r from-orange-100 to-orange-200 text-orange-600 border-2 border-orange-400
                                                            @else bg-gradient-to-r from-green-100 to-green-200 text-green-600 border-2 border-green-300 @endif">
                                                            {{ strtoupper(substr($item['usuario'], 0, 1)) }}
                                                        </div>
                                                        @if($item['posicion'] <= 3)
                                                            <div class="absolute -top-2 -right-2 text-lg">
                                                                @if($item['posicion'] == 1)👑
                                                                @elseif($item['posicion'] == 2)🥈
                                                                @elseif($item['posicion'] == 3)🥉
                                                                @endif
                                                            </div>
                                                        @else
                                                            <div class="absolute -top-2 -right-2 text-xs font-bold bg-white rounded-full px-1.5 py-0.5 border shadow-sm">
                                                                {{ $item['posicion'] }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                            <div>
                                                <h4 class="font-semibold text-gray-800">{{ $item['usuario'] }}</h4>
                                                <p class="text-xs text-gray-500">{{ $item['email'] }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-lg font-bold text-gray-800">
                                                ${{ number_format($item['venta_total'], 2) }}
                                            </div>
                                            <p class="text-xs text-gray-500">
                                                {{ $item['clientes_conseguidos'] }} clientes
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex justify-between text-xs text-gray-500">
                                        <span>Promedio: ${{ $item['clientes_conseguidos'] > 0 ? number_format($item['venta_total'] / $item['clientes_conseguidos'], 2) : '0.00' }}</span>
                                        <span>{{ $item['clientes_conseguidos'] }} ventas</span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <span class="text-3xl mb-3">📊</span>
                                <p>No hay datos de ventas disponibles</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información Adicional -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    ℹ️ Información sobre los Rankings
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <h4 class="font-semibold mb-2">Tasa de Conversión:</h4>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Calculado como: (Clientes / Leads) × 100</li>
                            <li>Solo se consideran usuarios con al menos 1 lead</li>
                            <li>Muestra la efectividad en convertir leads en clientes</li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-2">Top Vendedores:</h4>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Ordenado por monto total de ventas</li>
                            <li>Incluye todos los clientes convertidos</li>
                            <li>Considera el precio de compra de cada cliente</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Enlace de regreso a rankings de leads -->
            <div class="mt-6 text-center">
                <a href="{{ route('analista.lead-rankings') }}"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition duration-200">
                    ↩️ Volver a Rankings de Leads
                </a>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .ranking-item {
            transition: all 0.3s ease;
        }
        .ranking-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
    @endpush
</x-app-layout>
