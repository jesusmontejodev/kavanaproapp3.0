<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🌍 Ranking Global - Top 30 Usuarios
            </h2>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-500">
                    ⏰ {{ $ultima_actualizacion }}
                </div>
                <a href="{{ url()->current() }}"
                    class="px-3 py-1 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition duration-200">
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

            <!-- Banner de Información -->
            <div class="mb-6 bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-2xl font-bold mb-2">🏆 Ranking Global Mensual</h3>
                        <p class="opacity-90">Top 30 usuarios con mejor desempeño integral</p>
                        <p class="text-sm opacity-80 mt-1">{{ $periodo }}</p>
                    </div>
                    <div class="text-center md:text-right">
                        <div class="text-4xl font-bold">{{ $estadisticas['usuarios_top30'] }}/30</div>
                        <p class="text-sm opacity-90">Usuarios en ranking</p>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Generales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                            <span class="text-2xl">👥</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Usuarios</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $estadisticas['total_usuarios'] }}</h3>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                            <span class="text-2xl">📊</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Leads Totales</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $estadisticas['total_leads_mes'] }}</h3>
                            <p class="text-xs text-gray-500">{{ $estadisticas['promedio_leads_usuario'] }} por usuario</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                            <span class="text-2xl">💰</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Venta Total</p>
                            <h3 class="text-2xl font-bold text-gray-800">
                                ${{ number_format($estadisticas['venta_total_mes'], 2) }}
                            </h3>
                            <p class="text-xs text-gray-500">${{ number_format($estadisticas['promedio_venta_usuario'], 2) }} promedio</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-orange-500">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-orange-100 text-orange-500 mr-4">
                            <span class="text-2xl">📈</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tasa Conversión</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $estadisticas['tasa_conversion_mes'] }}%</h3>
                            <p class="text-xs text-gray-500">{{ $estadisticas['total_clientes_mes'] }} clientes</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Categorías de Usuarios -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 p-4 rounded-xl border border-yellow-200">
                    <div class="flex items-center mb-2">
                        <span class="text-2xl mr-2">👑</span>
                        <h4 class="font-bold text-gray-800">Top Performers</h4>
                    </div>
                    <p class="text-3xl font-bold text-yellow-600">{{ count($categorias_usuarios['top_performers']) }}</p>
                    <p class="text-sm text-gray-600">Posición 1-10, Puntaje 70+</p>
                </div>

                <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-4 rounded-xl border border-blue-200">
                    <div class="flex items-center mb-2">
                        <span class="text-2xl mr-2">📊</span>
                        <h4 class="font-bold text-gray-800">Consistentes</h4>
                    </div>
                    <p class="text-3xl font-bold text-blue-600">{{ count($categorias_usuarios['consistentes']) }}</p>
                    <p class="text-sm text-gray-600">Tasa ≥20%, Leads ≥10</p>
                </div>

                <div class="bg-gradient-to-r from-green-50 to-green-100 p-4 rounded-xl border border-green-200">
                    <div class="flex items-center mb-2">
                        <span class="text-2xl mr-2">📈</span>
                        <h4 class="font-bold text-gray-800">En Crecimiento</h4>
                    </div>
                    <p class="text-3xl font-bold text-green-600">{{ count($categorias_usuarios['en_crecimiento']) }}</p>
                    <p class="text-sm text-gray-600">Posición 20+, Activos</p>
                </div>

                <div class="bg-gradient-to-r from-gray-50 to-gray-100 p-4 rounded-xl border border-gray-200">
                    <div class="flex items-center mb-2">
                        <span class="text-2xl mr-2">🚀</span>
                        <h4 class="font-bold text-gray-800">Principiantes</h4>
                    </div>
                    <p class="text-3xl font-bold text-gray-600">{{ count($categorias_usuarios['principiantes']) }}</p>
                    <p class="text-sm text-gray-600">En desarrollo</p>
                </div>
            </div>

            <!-- Gráficos de Distribución -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                        <span class="text-xl mr-2">📊</span> Distribución por Leads
                    </h4>
                    <div class="h-64">
                        <canvas id="distribucionLeadsChart"></canvas>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                    <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                        <span class="text-xl mr-2">💰</span> Distribución por Ventas (Ticket Alto)
                    </h4>
                    <div class="h-64">
                        <canvas id="distribucionVentasChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Ranking Global - Tabla Completa -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-6">
                <div class="p-6 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-800">
                            🏅 Ranking Global de 30 Usuarios
                        </h3>
                        <span class="px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-sm">
                            Ordenado por Puntaje Global
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">{{ $periodo }}</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Posición
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Usuario
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Leads
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Clientes
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ventas
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Conversión
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Puntaje
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($ranking_global as $item)
                                <tr class="hover:bg-gray-50 transition duration-150
                                    {{ $item['posicion'] <= 3 ? 'bg-gradient-to-r from-yellow-50 to-yellow-25' : '' }}
                                    {{ $item['posicion'] >= 4 && $item['posicion'] <= 10 ? 'bg-blue-50' : '' }}">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 flex items-center justify-center rounded-full font-bold
                                                @if($item['posicion'] == 1) bg-yellow-100 text-yellow-600
                                                @elseif($item['posicion'] == 2) bg-gray-100 text-gray-600
                                                @elseif($item['posicion'] == 3) bg-orange-100 text-orange-600
                                                @else bg-gray-100 text-gray-500 @endif">
                                                @if($item['posicion'] <= 3)
                                                    @if($item['posicion'] == 1)🥇
                                                    @elseif($item['posicion'] == 2)🥈
                                                    @elseif($item['posicion'] == 3)🥉
                                                    @endif
                                                @else
                                                    {{ $item['posicion'] }}
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 mr-3">
                                                @if($item['foto_perfil'] && file_exists(public_path($item['foto_perfil'])))
                                                    <img class="h-10 w-10 rounded-full border-2 border-gray-200"
                                                         src="{{ asset($item['foto_perfil']) }}"
                                                         alt="{{ $item['usuario'] }}">
                                                @else
                                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600 border-2 border-gray-200">
                                                        {{ strtoupper(substr($item['usuario'], 0, 1)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $item['usuario'] }}</div>
                                                <div class="text-xs text-gray-500 truncate max-w-[200px]">{{ $item['email'] }}</div>
                                                @if($item['empresa'])
                                                    <div class="text-xs text-gray-400">{{ $item['empresa'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $item['leads_totales'] }}</div>
                                        <div class="text-xs text-gray-500">leads</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-green-600">{{ $item['clientes_totales'] }}</div>
                                        <div class="text-xs text-gray-500">clientes</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-purple-600">
                                            ${{ number_format($item['venta_total'], 2) }}
                                        </div>
                                        <div class="text-xs text-gray-500">ventas</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-bold
                                                @if($item['tasa_conversion'] >= 30) text-green-600
                                                @elseif($item['tasa_conversion'] >= 15) text-yellow-600
                                                @else text-red-600 @endif">
                                                {{ $item['tasa_conversion'] }}%
                                            </div>
                                            <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                                                <div class="h-2 rounded-full
                                                    @if($item['tasa_conversion'] >= 30) bg-green-500
                                                    @elseif($item['tasa_conversion'] >= 15) bg-yellow-500
                                                    @else bg-red-500 @endif"
                                                    style="width: {{ min($item['tasa_conversion'], 100) }}%">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-lg font-bold
                                                @if($item['puntaje_global'] >= 80) text-green-600
                                                @elseif($item['puntaje_global'] >= 60) text-blue-600
                                                @elseif($item['puntaje_global'] >= 40) text-yellow-600
                                                @else text-gray-600 @endif">
                                                {{ $item['puntaje_global'] }}
                                            </div>
                                            <div class="ml-2 text-xs text-gray-500">
                                                /100
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-400 mt-1">
                                            @if($item['puntaje_global'] >= 80) Excelente
                                            @elseif($item['puntaje_global'] >= 60) Bueno
                                            @elseif($item['puntaje_global'] >= 40) Regular
                                            @else Iniciando
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($ranking_global->count() == 0)
                    <div class="text-center py-12 text-gray-400">
                        <span class="text-4xl mb-3">📊</span>
                        <p class="text-lg">No hay datos disponibles</p>
                        <p class="text-sm mt-2">No hay usuarios con actividad en el período</p>
                    </div>
                @endif
            </div>

            <!-- Leyenda del Ranking -->
            <div class="mb-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-5 border border-gray-200">
                <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                    <span class="text-xl mr-2">📋</span> Explicación del Ranking
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div>
                        <h5 class="font-semibold mb-2">🏆 Puntaje Global:</h5>
                        <ul class="list-disc pl-5 space-y-1">
                            <li><span class="font-medium">Leads (40%):</span> Cantidad total de leads capturados</li>
                            <li><span class="font-medium">Ventas (35%):</span> Monto total de ventas generadas</li>
                            <li><span class="font-medium">Conversión (25%):</span> Efectividad en convertir leads</li>
                        </ul>
                    </div>
                    <div>
                        <h5 class="font-semibold mb-2">🎨 Categorías:</h5>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-yellow-100 border border-yellow-300 rounded mr-2"></div>
                                <span class="text-sm">Top 3 posiciones</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-blue-50 border border-blue-200 rounded mr-2"></div>
                                <span class="text-sm">Posiciones 4-10</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-4 h-4 bg-white border border-gray-300 rounded mr-2"></div>
                                <span class="text-sm">Resto de posiciones</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enlaces a otros rankings -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('analista.lead-rankings') }}"
                   class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition duration-200">
                    <span class="mr-2">📊</span>
                    Rankings Diarios de Leads
                </a>

                <a href="{{ route('analista.cliente-rankings') }}"
                   class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 transition duration-200">
                    <span class="mr-2">💰</span>
                    Rankings de Clientes
                </a>

                <button onclick="window.print()"
                   class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition duration-200">
                    <span class="mr-2">🖨️</span>
                    Imprimir Ranking
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript para gráficos -->
    @if(isset($datos_distribucion))
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const datosDistribucion = {!! $datos_distribucion !!};

            // Gráfico de distribución por leads
            const ctxLeads = document.getElementById('distribucionLeadsChart');
            if (ctxLeads) {
                new Chart(ctxLeads.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: datosDistribucion.labels_leads,
                        datasets: [{
                            label: 'Cantidad de Usuarios',
                            data: datosDistribucion.data_leads,
                            backgroundColor: [
                                'rgba(147, 51, 234, 0.7)',
                                'rgba(79, 70, 229, 0.7)',
                                'rgba(59, 130, 246, 0.7)',
                                'rgba(34, 197, 94, 0.7)',
                                'rgba(245, 158, 11, 0.7)'
                            ],
                            borderColor: [
                                'rgb(147, 51, 234)',
                                'rgb(79, 70, 229)',
                                'rgb(59, 130, 246)',
                                'rgb(34, 197, 94)',
                                'rgb(245, 158, 11)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Cantidad de Usuarios'
                                },
                                ticks: {
                                    stepSize: 1
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Rango de Leads'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }

            // Gráfico de distribución por ventas (ticket alto)
            const ctxVentas = document.getElementById('distribucionVentasChart');
            if (ctxVentas) {
                new Chart(ctxVentas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: datosDistribucion.labels_ventas,
                        datasets: [{
                            label: 'Cantidad de Usuarios',
                            data: datosDistribucion.data_ventas,
                            backgroundColor: [
                                'rgba(147, 51, 234, 0.8)',
                                'rgba(79, 70, 229, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(34, 197, 94, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(6, 182, 212, 0.8)'
                            ],
                            borderColor: [
                                'rgb(147, 51, 234)',
                                'rgb(79, 70, 229)',
                                'rgb(59, 130, 246)',
                                'rgb(34, 197, 94)',
                                'rgb(245, 158, 11)',
                                'rgb(239, 68, 68)',
                                'rgb(6, 182, 212)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        label += context.raw + ' usuario(s)';
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endif

    <!-- Estilos para la tabla -->
    <style>
        .ranking-table tr:hover {
            background-color: #f9fafb;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .bg-gradient-to-r {
                background: #f3f4f6 !important;
            }

            table {
                break-inside: avoid;
            }
        }
    </style>


</x-app-layout>
