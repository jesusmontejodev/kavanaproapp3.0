<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                🏆 Rankings de Leads
            </h2>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-500">
                    ⏰ {{ $ultima_actualizacion }}
                </div>
                <a href="{{ url()->current() }}"
                    class="px-3 py-1 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition duration-200">
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

            <!-- DEBUG: Verificar datos -->
            @php
                $datosGraficoArray = json_decode($datos_grafico, true);
                $hayDatosGrafico = isset($datosGraficoArray['labels']) &&
                                  count($datosGraficoArray['labels']) > 0 &&
                                  !empty(array_filter($datosGraficoArray['labels']));

                echo "<!-- DEBUG: hayDatosGrafico = " . ($hayDatosGrafico ? 'true' : 'false') . " -->";
                echo "<!-- DEBUG: labels count = " . count($datosGraficoArray['labels'] ?? []) . " -->";
                echo "<!-- DEBUG: labels = " . implode(', ', $datosGraficoArray['labels'] ?? []) . " -->";
            @endphp

            <!-- Ganador del Día -->
            @if($ganador_dia)
            <div class="mb-6 bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-xl shadow-lg p-6 text-white transform transition duration-300 hover:scale-[1.02]">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="flex items-center space-x-4 mb-4 md:mb-0">
                        @if($ganador_dia['foto_perfil'] && file_exists(public_path($ganador_dia['foto_perfil'])))
                            <div class="relative">
                                <img src="{{ asset($ganador_dia['foto_perfil']) }}"
                                     alt="{{ $ganador_dia['usuario'] }}"
                                     class="w-20 h-20 rounded-full border-4 border-white shadow-lg">
                                <div class="absolute -top-2 -right-2 text-3xl">
                                    🥇
                                </div>
                            </div>
                        @else
                            <div class="relative">
                                <div class="bg-white text-yellow-500 rounded-full w-20 h-20 flex items-center justify-center text-4xl font-bold shadow-lg border-4 border-white">
                                    {{ strtoupper(substr($ganador_dia['usuario'], 0, 1)) }}
                                </div>
                                <div class="absolute -top-2 -right-2 text-3xl">
                                    🥇
                                </div>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-2xl font-bold">🎯 Ganador del Día</h3>
                            <p class="text-xl font-semibold">{{ $ganador_dia['usuario'] }}</p>
                            <p class="text-lg opacity-90">{{ $ganador_dia['email'] }}</p>
                        </div>
                    </div>
                    <div class="text-center md:text-right">
                        <div class="text-6xl font-bold drop-shadow-lg">{{ $ganador_dia['leads_hoy'] }}</div>
                        <p class="text-lg">Leads conseguidos hoy</p>
                        <p class="text-sm opacity-80">{{ $fecha_hoy }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Meta del Mes -->
            <div class="mb-6 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <h3 class="text-2xl font-bold mb-2 flex items-center">
                            <span class="mr-2">🎯</span> Meta del Mes
                        </h3>
                        <p class="opacity-90">Seguimiento del objetivo mensual</p>
                    </div>
                    @php
                        $metaLeads = 100;
                        $progreso = $estadisticas['total_leads_mes'] > 0
                            ? min(round(($estadisticas['total_leads_mes'] / $metaLeads) * 100), 100)
                            : 0;
                        $restante = max($metaLeads - $estadisticas['total_leads_mes'], 0);
                        $diasRestantes = now()->daysInMonth - now()->day;
                        $leadsDiariosNecesarios = $diasRestantes > 0 ? ceil($restante / $diasRestantes) : 0;
                    @endphp
                    <div class="text-center md:text-right">
                        <div class="text-4xl font-bold mb-2">{{ $progreso }}%</div>
                        <p class="text-sm opacity-90">{{ $estadisticas['total_leads_mes'] }}/{{ $metaLeads }} leads</p>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm mb-1">
                        <span>Progreso</span>
                        <span>{{ $progreso }}%</span>
                    </div>
                    <div class="w-full bg-blue-300 rounded-full h-3">
                        <div class="bg-white h-3 rounded-full transition-all duration-500"
                             style="width: {{ $progreso }}%"></div>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-3 text-sm">
                        <div class="bg-white/20 px-3 py-1 rounded-full">
                            📅 {{ $diasRestantes }} días restantes
                        </div>
                        <div class="bg-white/20 px-3 py-1 rounded-full">
                            ⚡ {{ $leadsDiariosNecesarios }} leads/día necesarios
                        </div>
                        <div class="bg-white/20 px-3 py-1 rounded-full">
                            📊 {{ $restante }} leads por conseguir
                        </div>
                    </div>
                    <p class="mt-3 text-sm opacity-90">
                        @if($progreso >= 100)
                            🎉 ¡Meta superada! ¡Excelente trabajo!
                        @elseif($progreso >= 80)
                            👍 Vas por buen camino, ¡sigue así!
                        @elseif($progreso >= 50)
                            💪 Mitad del camino recorrido
                        @elseif($progreso >= 25)
                            🔥 Buen inicio, ¡a por más!
                        @else
                            🚀 Comienza fuerte para alcanzar la meta
                        @endif
                    </p>
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500 hover:shadow-md transition duration-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                            <span class="text-2xl">☀️</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Hoy</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $estadisticas['usuarios_hoy'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $estadisticas['total_leads_hoy'] }} leads totales</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $fecha_hoy }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500 hover:shadow-md transition duration-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                            <span class="text-2xl">📅</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Esta Semana</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $estadisticas['usuarios_semana'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $estadisticas['total_leads_semana'] }} leads totales</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $fecha_semana }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500 hover:shadow-md transition duration-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-500 mr-4">
                            <span class="text-2xl">📆</span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Este Mes</p>
                            <h3 class="text-2xl font-bold text-gray-800">{{ $estadisticas['usuarios_mes'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $estadisticas['total_leads_mes'] }} leads totales</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $fecha_mes }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Alertas Automáticas -->
            @php
                $alertas = [];

                if($estadisticas['total_leads_hoy'] < 5 && now()->hour > 12) {
                    $alertas[] = "Baja productividad hoy. Solo {$estadisticas['total_leads_hoy']} leads registrados.";
                }

                $topUsuario = $ranking_diario->first();
                if($topUsuario && $topUsuario['leads_hoy'] >= 10) {
                    $alertas[] = "🚨 ¡{$topUsuario['usuario']} está en racha! Lleva {$topUsuario['leads_hoy']} leads hoy.";
                }

                if($estadisticas['usuarios_hoy'] < 3 && $estadisticas['usuarios_mes'] > 5) {
                    $alertas[] = "Pocos usuarios activos hoy ({$estadisticas['usuarios_hoy']}).";
                }
            @endphp

            @if(count($alertas) > 0)
                <div class="mb-6 bg-gradient-to-r from-orange-50 to-red-50 rounded-xl p-5 border-l-4 border-orange-400 shadow-sm">
                    <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                        <span class="text-xl mr-2">🔔</span> Alertas del Día
                    </h4>
                    <div class="space-y-2">
                        @foreach($alertas as $alerta)
                            <div class="flex items-start text-sm p-3 bg-white rounded-lg border border-orange-100">
                                <span class="text-orange-500 mr-2 mt-0.5">⚠️</span>
                                <span class="text-gray-700">{{ $alerta }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Rankings -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Ranking Diario -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 border-b bg-gradient-to-r from-blue-50 to-blue-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-800">
                                🏆 Ranking Diario
                            </h3>
                            <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-medium">
                                📅 Hoy
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $fecha_hoy }}</p>
                    </div>
                    <div class="p-4">
                        @if($ranking_diario->count() > 0)
                            @foreach($ranking_diario as $item)
                                <div class="ranking-item p-4 mb-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition duration-200 {{ $item['posicion'] <= 3 ? 'bg-gradient-to-r from-gray-50 to-white' : '' }}">
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
                                                            @if($item['posicion'] == 1) bg-yellow-100 text-yellow-600 border-2 border-yellow-400
                                                            @elseif($item['posicion'] == 2) bg-gray-100 text-gray-600 border-2 border-gray-300
                                                            @elseif($item['posicion'] == 3) bg-orange-100 text-orange-600 border-2 border-orange-400
                                                            @else bg-blue-100 text-blue-600 border-2 border-blue-300 @endif">
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
                                                <p class="text-xs text-gray-500 truncate max-w-[150px]">{{ $item['email'] }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-gray-800">{{ $item['leads_hoy'] }}</span>
                                            <p class="text-xs text-gray-500">leads</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <span class="text-3xl mb-3">📊</span>
                                <p>No hay datos disponibles para hoy</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Ranking Semanal -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 border-b bg-gradient-to-r from-green-50 to-green-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-800">
                                🥈 Ranking Semanal
                            </h3>
                            <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-sm font-medium">
                                📅 Semana
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $fecha_semana }}</p>
                    </div>
                    <div class="p-4">
                        @if($ranking_semanal->count() > 0)
                            @foreach($ranking_semanal as $item)
                                <div class="ranking-item p-4 mb-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition duration-200 {{ $item['posicion'] <= 3 ? 'bg-gradient-to-r from-gray-50 to-white' : '' }}">
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
                                                            @if($item['posicion'] == 1)🥇
                                                            @elseif($item['posicion'] == 2)🥈
                                                            @elseif($item['posicion'] == 3)🥉
                                                            @endif
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="relative">
                                                        <div class="w-12 h-12 flex items-center justify-center rounded-full font-bold text-lg
                                                            @if($item['posicion'] == 1) bg-yellow-100 text-yellow-600 border-2 border-yellow-400
                                                            @elseif($item['posicion'] == 2) bg-gray-100 text-gray-600 border-2 border-gray-300
                                                            @elseif($item['posicion'] == 3) bg-orange-100 text-orange-600 border-2 border-orange-400
                                                            @else bg-green-100 text-green-600 border-2 border-green-300 @endif">
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
                                                <p class="text-xs text-gray-500 truncate max-w-[150px]">{{ $item['email'] }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-gray-800">{{ $item['leads_semana'] }}</span>
                                            <p class="text-xs text-gray-500">leads</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <span class="text-3xl mb-3">📊</span>
                                <p>No hay datos disponibles esta semana</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Ranking Mensual -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6 border-b bg-gradient-to-r from-purple-50 to-purple-100">
                        <div class="flex justify-between items-center">
                            <h3 class="text-xl font-bold text-gray-800">
                                🥉 Ranking Mensual
                            </h3>
                            <span class="px-3 py-1 bg-purple-100 text-purple-600 rounded-full text-sm font-medium">
                                📅 Mes
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $fecha_mes }}</p>
                    </div>
                    <div class="p-4">
                        @if($ranking_mensual->count() > 0)
                            @foreach($ranking_mensual as $item)
                                <div class="ranking-item p-4 mb-3 rounded-lg border border-gray-100 hover:bg-gray-50 transition duration-200 {{ $item['posicion'] <= 3 ? 'bg-gradient-to-r from-gray-50 to-white' : '' }}">
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
                                                         @else border-purple-300 @endif">
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
                                                            @if($item['posicion'] == 1) bg-yellow-100 text-yellow-600 border-2 border-yellow-400
                                                            @elseif($item['posicion'] == 2) bg-gray-100 text-gray-600 border-2 border-gray-300
                                                            @elseif($item['posicion'] == 3) bg-orange-100 text-orange-600 border-2 border-orange-400
                                                            @else bg-purple-100 text-purple-600 border-2 border-purple-300 @endif">
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
                                                <p class="text-xs text-gray-500 truncate max-w-[150px]">{{ $item['email'] }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-gray-800">{{ $item['leads_mes'] }}</span>
                                            <p class="text-xs text-gray-500">leads</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-8 text-gray-400">
                                <span class="text-3xl mb-3">📊</span>
                                <p>No hay datos disponibles este mes</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Proyección Predictiva y Análisis -->
            <div class="mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 overflow-hidden shadow-sm sm:rounded-lg p-6 border border-indigo-200">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">
                        🔮 Proyección Predictiva
                    </h3>
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-600 rounded-full text-sm">
                        Basado en datos actuales
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Proyección de Leads Mensual -->
                    <div class="bg-white rounded-xl p-5 shadow-md border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="p-2 rounded-lg bg-blue-50 text-blue-500 mr-3">
                                <span class="text-xl">📊</span>
                            </div>
                            <h4 class="font-bold text-gray-800">Proyección de Leads</h4>
                        </div>
                        @php
                            $diasTranscurridos = now()->day;
                            $diasTotalesMes = now()->daysInMonth;
                            $proyeccionLeads = $estadisticas['total_leads_mes'] > 0
                                ? round(($estadisticas['total_leads_mes'] / $diasTranscurridos) * $diasTotalesMes)
                                : 0;
                            $diferenciaMeta = $proyeccionLeads - $metaLeads;
                        @endphp
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-800 mb-2">{{ $proyeccionLeads }}</div>
                            <p class="text-sm text-gray-600">Leads proyectados este mes</p>
                            <div class="mt-3">
                                @if($diferenciaMeta >= 0)
                                    <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">
                                        🎉 {{ abs($diferenciaMeta) }} sobre meta
                                    </span>
                                @else
                                    <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-700">
                                        ⚠️ {{ abs($diferenciaMeta) }} bajo meta
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Proyección de Conversión -->
                    <div class="bg-white rounded-xl p-5 shadow-md border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="p-2 rounded-lg bg-green-50 text-green-500 mr-3">
                                <span class="text-xl">📈</span>
                            </div>
                            <h4 class="font-bold text-gray-800">Tendencia de Actividad</h4>
                        </div>
                        @php
                            $actividadHoy = $estadisticas['usuarios_hoy'];
                            $actividadPromedio = $diasTranscurridos > 0
                                ? round($estadisticas['usuarios_mes'] / $diasTranscurridos, 1)
                                : 0;
                            $variacion = $actividadPromedio > 0
                                ? round((($actividadHoy - $actividadPromedio) / $actividadPromedio) * 100)
                                : 0;
                            $tendenciaConv = $variacion > 0 ? 'mejorando' : ($variacion < 0 ? 'bajando' : 'estable');
                        @endphp
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-800 mb-2">{{ $actividadHoy }}</div>
                            <p class="text-sm text-gray-600">Usuarios activos hoy</p>
                            <div class="mt-3">
                                @if($tendenciaConv == 'mejorando')
                                    <span class="text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">
                                        📈 +{{ abs($variacion) }}% vs promedio
                                    </span>
                                @elseif($tendenciaConv == 'bajando')
                                    <span class="text-xs px-2 py-1 rounded-full bg-red-100 text-red-700">
                                        📉 {{ $variacion }}% vs promedio
                                    </span>
                                @else
                                    <span class="text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-700">
                                        📊 Promedio estable
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Usuarios con Potencial -->
                    <div class="bg-white rounded-xl p-5 shadow-md border border-gray-100">
                        <div class="flex items-center mb-4">
                            <div class="p-2 rounded-lg bg-purple-50 text-purple-500 mr-3">
                                <span class="text-xl">⭐</span>
                            </div>
                            <h4 class="font-bold text-gray-800">Usuarios con Potencial</h4>
                        </div>
                        <div class="space-y-2">
                            @php
                                $usuariosPotencial = $ranking_diario
                                    ->where('leads_hoy', '>', 0)
                                    ->where('posicion', '>', 3)
                                    ->take(3);
                            @endphp

                            @if($usuariosPotencial->count() > 0)
                                @foreach($usuariosPotencial as $usuario)
                                    <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                                        <div class="flex items-center">
                                            @if($usuario['foto_perfil'] && file_exists(public_path($usuario['foto_perfil'])))
                                                <img src="{{ asset($usuario['foto_perfil']) }}"
                                                     class="w-8 h-8 rounded-full mr-2">
                                            @else
                                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm font-bold mr-2">
                                                    {{ strtoupper(substr($usuario['usuario'], 0, 1)) }}
                                                </div>
                                            @endif
                                            <span class="text-sm font-medium truncate max-w-[100px]">{{ $usuario['usuario'] }}</span>
                                        </div>
                                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                            +{{ $usuario['leads_hoy'] }} hoy
                                        </span>
                                    </div>
                                @endforeach
                                <p class="text-xs text-gray-500 text-center mt-2">
                                    En crecimiento hoy
                                </p>
                            @else
                                <p class="text-sm text-gray-500 text-center py-2">No hay usuarios con potencial hoy</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recomendaciones -->
                <div class="mt-6 bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                    <h4 class="font-bold text-gray-800 mb-3 flex items-center">
                        <span class="text-xl mr-2">💡</span> Recomendaciones Inteligentes
                    </h4>
                    <div class="space-y-3">
                        @php
                            $recomendaciones = [];

                            if($estadisticas['total_leads_hoy'] < 5 && now()->hour > 12) {
                                $recomendaciones[] = "📉 Hoy se registraron pocos leads. Considera incentivar la captura con un recordatorio al equipo.";
                            }

                            if($diferenciaMeta < 0 && $diasRestantes > 0) {
                                $recomendaciones[] = "🎯 Necesitas {$leadsDiariosNecesarios} leads/día para alcanzar la meta. Enfoca esfuerzos en las próximas horas.";
                            }

                            $topUsuario = $ranking_diario->first();
                            if($topUsuario && $topUsuario['leads_hoy'] > 10) {
                                $recomendaciones[] = "🚀 {$topUsuario['usuario']} está destacando con {$topUsuario['leads_hoy']} leads hoy. Podría compartir sus estrategias con el equipo.";
                            }

                            if(empty($recomendaciones)) {
                                $recomendaciones[] = "✅ Los datos muestran buen rendimiento general. Continúa con el buen trabajo.";
                            }
                        @endphp

                        <ul class="space-y-2">
                            @foreach($recomendaciones as $recomendacion)
                                <li class="flex items-start p-2 hover:bg-gray-50 rounded">
                                    <span class="text-indigo-500 mr-2 mt-0.5">•</span>
                                    <span class="text-sm text-gray-700">{{ $recomendacion }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Gráfico Comparativo -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-200">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">
                        📈 Comparativa de Rendimiento
                    </h3>
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm">
                        Top 5 Usuarios
                    </span>
                </div>
                <div class="h-80">
                    @if($hayDatosGrafico)
                        <canvas id="comparativaChart"></canvas>
                    @else
                        <div class="h-full flex flex-col items-center justify-center text-gray-400">
                            <span class="text-5xl mb-3">📊</span>
                            <p class="text-lg">No hay datos suficientes para el gráfico</p>
                            <p class="text-sm mt-2">Se necesitan al menos 2 usuarios con datos de leads</p>
                            <p class="text-xs mt-1">Intenta más tarde cuando haya más actividad</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Enlace a rankings de clientes -->
            <div class="mt-6 text-center">
                <a href="{{ route('analista.cliente-rankings') }}"
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition duration-200">
                    👥 Ver Rankings de Clientes
                </a>
            </div>
        </div>

        <!-- Enlace a rankings de clientes + Botón Imprimir -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('analista.cliente-rankings') }}"
            class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-lg hover:from-green-600 hover:to-emerald-700 transition duration-200">
                <span class="mr-2">💰</span>
                Ver Rankings de Clientes
            </a>

            <button onclick="window.print()"
            class="inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition duration-200">
                <span class="mr-2">🖨️</span>
                Imprimir Reporte
            </button>
        </div>

        <!-- También agrega enlace a Ranking Global -->
        <div class="mt-4 text-center">
            <a href="{{ route('analista.global-rankings') }}"
            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 transition duration-200">
                <span class="mr-2">🌍</span>
                Ver Ranking Global
            </a>
        </div>

    </div>

    <!-- JavaScript directo en la vista -->
    @if($hayDatosGrafico)
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('=== INICIANDO CARGA DE GRÁFICO ===');

            try {
                // Obtener datos PHP como JavaScript
                const datosGrafico = {!! $datos_grafico !!};

                console.log('Datos recibidos del servidor:', datosGrafico);

                // Verificar estructura de datos
                if (!datosGrafico || !datosGrafico.labels || !Array.isArray(datosGrafico.labels)) {
                    throw new Error('Estructura de datos inválida');
                }

                if (datosGrafico.labels.length === 0) {
                    throw new Error('No hay usuarios para mostrar');
                }

                // Verificar que haya datos en los datasets
                const tieneDatos = datosGrafico.datasets.some(dataset =>
                    Array.isArray(dataset.data) && dataset.data.some(valor => valor > 0)
                );

                if (!tieneDatos) {
                    console.warn('Todos los valores de datos son cero');
                }

                // Obtener el canvas
                const canvas = document.getElementById('comparativaChart');
                if (!canvas) {
                    throw new Error('No se encontró el elemento canvas');
                }

                console.log('Canvas encontrado, creando gráfico...');

                // Crear contexto
                const ctx = canvas.getContext('2d');

                // Crear el gráfico
                const chart = new Chart(ctx, {
                    type: 'bar',
                    data: datosGrafico,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Número de Leads',
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                },
                                ticks: {
                                    stepSize: 1,
                                    callback: function(value) {
                                        if (Number.isInteger(value)) {
                                            return value;
                                        }
                                    }
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Usuarios',
                                    font: {
                                        size: 14,
                                        weight: 'bold'
                                    }
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        }
                    }
                });

                console.log('✅ Gráfico creado exitosamente');
                console.log('Usuarios:', datosGrafico.labels);
                console.log('Datos hoy:', datosGrafico.datasets[0]?.data);
                console.log('Datos semana:', datosGrafico.datasets[1]?.data);
                console.log('Datos mes:', datosGrafico.datasets[2]?.data);

            } catch (error) {
                console.error('❌ Error al crear el gráfico:', error);

                // Mostrar mensaje de error en la página
                const chartContainer = document.querySelector('#comparativaChart')?.parentElement;
                if (chartContainer) {
                    chartContainer.innerHTML = `
                        <div class="h-full flex flex-col items-center justify-center text-red-500 p-4">
                            <span class="text-4xl mb-3">❌</span>
                            <p class="text-lg font-bold">Error al cargar el gráfico</p>
                            <p class="text-sm mt-2 text-center">${error.message}</p>
                            <p class="text-xs mt-2 text-gray-500">Verifica la consola para más detalles</p>
                        </div>
                    `;
                }

                // Mostrar datos en consola para debug
                console.log('=== DATOS PARA DEBUG ===');
                console.log('hayDatosGrafico:', @json($hayDatosGrafico));
                console.log('datos_grafico raw:', @json($datos_grafico));
            }
        });
    </script>
    @endif

    <!-- Estilos simples -->
    <style>
        .ranking-item {
            transition: all 0.3s ease;
        }
        .ranking-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        #comparativaChart {
            max-height: 320px;
            width: 100% !important;
        }
    </style>

    <style>
        @media print {
            /* Ocultar elementos no deseados en impresión */
            .no-print {
                display: none !important;
            }

            /* Botones */
            a, button {
                display: none !important;
            }

            /* Específicamente los botones de acción */
            [href*="actualizar"],
            [onclick*="print"],
            [href*="cliente-rankings"],
            [href*="global-rankings"] {
                display: none !important;
            }

            /* Header con fecha y actualizar */
            .flex.justify-between.items-center {
                display: block !important;
            }

            .flex.justify-between.items-center > div:last-child {
                display: none !important;
            }

            /* Fondo blanco para todo */
            body {
                background: white !important;
                color: black !important;
            }

            /* Tarjetas sin fondo de gradiente */
            .bg-gradient-to-r {
                background: #f3f4f6 !important;
            }

            /* Quitar sombras */
            .shadow-lg, .shadow-md, .shadow-sm {
                box-shadow: none !important;
            }

            /* Asegurar que las tablas no se rompan */
            table {
                break-inside: avoid;
            }

            /* Títulos más claros */
            h2, h3, h4 {
                color: #1f2937 !important;
            }

            /* Quitar efectos hover */
            .hover\\:scale-\\[1\\.02\\], .hover\\:shadow-md, .hover\\:bg-gray-50 {
                transform: none !important;
                box-shadow: none !important;
                background: transparent !important;
            }

            /* Ajustar márgenes */
            .py-6 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }

            /* Mostrar fecha de actualización como texto normal */
            .text-sm.text-gray-500 {
                color: #6b7280 !important;
                font-size: 0.875rem !important;
            }

            /* Quitar bordes redondeados para mejor impresión */
            .rounded-lg, .rounded-xl, .rounded-full {
                border-radius: 0.25rem !important;
            }
        }

        /* Estilo para el botón de imprimir en pantalla */
        @media screen {
            button[onclick*="print"] {
                cursor: pointer;
            }
        }
    </style>
</x-app-layout>
