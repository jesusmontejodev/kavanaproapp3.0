<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Lead;
use App\Models\Cliente;
use Carbon\Carbon;

class AnalistaController extends Controller
{
    /**
     * Muestra la vista de rankings de leads con datos procesados
     */
    public function UserLeadGraficas(Request $request)
    {
        try {
            $hoy = now()->format('Y-m-d');
            $inicioSemana = now()->startOfWeek()->format('Y-m-d');
            $finSemana = now()->endOfWeek()->format('Y-m-d');
            $inicioMes = now()->startOfMonth()->format('Y-m-d');
            $finMes = now()->endOfMonth()->format('Y-m-d');

            // Ranking Diario
            $rankingDiario = User::select('users.id', 'users.name', 'users.email', 'users.foto_perfil')
                ->selectRaw('COUNT(leads.id) as total_leads')
                ->leftJoin('leads', function ($join) use ($hoy) {
                    $join->on('users.id', '=', 'leads.id_user')
                        ->whereDate('leads.fecha_creado', $hoy);
                })
                ->groupBy('users.id', 'users.name', 'users.email', 'users.foto_perfil')
                ->orderBy('total_leads', 'desc')
                ->orderBy('users.name')
                ->limit(10)
                ->get()
                ->map(function ($user, $index) {
                    return [
                        'posicion' => $index + 1,
                        'usuario' => $user->name,
                        'email' => $user->email,
                        'foto_perfil' => $user->foto_perfil,
                        'leads_hoy' => $user->total_leads
                    ];
                });

            // Ranking Semanal
            $rankingSemanal = User::select('users.id', 'users.name', 'users.email', 'users.foto_perfil')
                ->selectRaw('COUNT(leads.id) as total_leads')
                ->leftJoin('leads', function ($join) use ($inicioSemana, $finSemana) {
                    $join->on('users.id', '=', 'leads.id_user')
                         ->whereBetween('leads.fecha_creado', [$inicioSemana, $finSemana]);
                })
                ->groupBy('users.id', 'users.name', 'users.email', 'users.foto_perfil')
                ->orderBy('total_leads', 'desc')
                ->orderBy('users.name')
                ->limit(10)
                ->get()
                ->map(function ($user, $index) {
                    return [
                        'posicion' => $index + 1,
                        'usuario' => $user->name,
                        'email' => $user->email,
                        'foto_perfil' => $user->foto_perfil,
                        'leads_semana' => $user->total_leads
                    ];
                });

            // Ranking Mensual
            $rankingMensual = User::select('users.id', 'users.name', 'users.email', 'users.foto_perfil')
                ->selectRaw('COUNT(leads.id) as total_leads')
                ->leftJoin('leads', function ($join) use ($inicioMes, $finMes) {
                    $join->on('users.id', '=', 'leads.id_user')
                         ->whereBetween('leads.fecha_creado', [$inicioMes, $finMes]);
                })
                ->groupBy('users.id', 'users.name', 'users.email', 'users.foto_perfil')
                ->orderBy('total_leads', 'desc')
                ->orderBy('users.name')
                ->limit(10)
                ->get()
                ->map(function ($user, $index) {
                    return [
                        'posicion' => $index + 1,
                        'usuario' => $user->name,
                        'email' => $user->email,
                        'foto_perfil' => $user->foto_perfil,
                        'leads_mes' => $user->total_leads
                    ];
                });

            // Estadísticas
            $estadisticas = [
                'usuarios_hoy' => $rankingDiario->where('leads_hoy', '>', 0)->count(),
                'usuarios_semana' => $rankingSemanal->where('leads_semana', '>', 0)->count(),
                'usuarios_mes' => $rankingMensual->where('leads_mes', '>', 0)->count(),
                'total_leads_hoy' => $rankingDiario->sum('leads_hoy'),
                'total_leads_semana' => $rankingSemanal->sum('leads_semana'),
                'total_leads_mes' => $rankingMensual->sum('leads_mes'),
            ];

            // Datos para el gráfico (top 5 usuarios para comparativa) - CORREGIDO
            $usuariosTop5 = $rankingMensual->take(5)->map(function($item) {
                return $item['usuario'];
            })->toArray();

            // Si $usuariosTop5 está vacío, intenta con rankingDiario o rankingSemanal
            if (empty($usuariosTop5) || (count($usuariosTop5) === 1 && $usuariosTop5[0] === null)) {
                $usuariosTop5 = $rankingDiario->take(5)->map(function($item) {
                    return $item['usuario'];
                })->toArray();

                if (empty($usuariosTop5) || (count($usuariosTop5) === 1 && $usuariosTop5[0] === null)) {
                    $usuariosTop5 = $rankingSemanal->take(5)->map(function($item) {
                        return $item['usuario'];
                    })->toArray();
                }
            }

            // Si sigue vacío o tiene muy pocos elementos, completar con placeholders
            $usuariosTop5 = array_filter($usuariosTop5, function($usuario) {
                return !empty($usuario) && $usuario !== null;
            });

            if (count($usuariosTop5) < 2) {
                // Usar todos los usuarios disponibles
                $todosUsuarios = collect()
                    ->merge($rankingMensual->pluck('usuario'))
                    ->merge($rankingDiario->pluck('usuario'))
                    ->merge($rankingSemanal->pluck('usuario'))
                    ->unique()
                    ->filter()
                    ->take(5)
                    ->toArray();

                if (count($todosUsuarios) > 0) {
                    $usuariosTop5 = $todosUsuarios;
                }
            }

            // Preparar datos para Chart.js - CORREGIDO
            $datosGrafico = [
                'labels' => $usuariosTop5,
                'datasets' => [
                    [
                        'label' => 'Hoy',
                        'data' => array_map(function ($usuario) use ($rankingDiario) {
                            $user = $rankingDiario->firstWhere('usuario', $usuario);
                            return $user ? $user['leads_hoy'] : 0;
                        }, $usuariosTop5),
                        'backgroundColor' => 'rgba(59, 130, 246, 0.7)',
                        'borderColor' => 'rgb(59, 130, 246)',
                        'borderWidth' => 1
                    ],
                    [
                        'label' => 'Semana',
                        'data' => array_map(function ($usuario) use ($rankingSemanal) {
                            $user = $rankingSemanal->firstWhere('usuario', $usuario);
                            return $user ? $user['leads_semana'] : 0;
                        }, $usuariosTop5),
                        'backgroundColor' => 'rgba(34, 197, 94, 0.7)',
                        'borderColor' => 'rgb(34, 197, 94)',
                        'borderWidth' => 1
                    ],
                    [
                        'label' => 'Mes',
                        'data' => array_map(function ($usuario) use ($rankingMensual) {
                            $user = $rankingMensual->firstWhere('usuario', $usuario);
                            return $user ? $user['leads_mes'] : 0;
                        }, $usuariosTop5),
                        'backgroundColor' => 'rgba(168, 85, 247, 0.7)',
                        'borderColor' => 'rgb(168, 85, 247)',
                        'borderWidth' => 1
                    ]
                ]
            ];

            return view('analytics.lead-rankings', [
                'fecha_hoy' => $hoy,
                'fecha_semana' => "{$inicioSemana} al {$finSemana}",
                'fecha_mes' => "{$inicioMes} al {$finMes}",
                'ranking_diario' => $rankingDiario,
                'ranking_semanal' => $rankingSemanal,
                'ranking_mensual' => $rankingMensual,
                'ganador_dia' => $rankingDiario->first(),
                'estadisticas' => $estadisticas,
                'datos_grafico' => json_encode($datosGrafico),
                'ultima_actualizacion' => now()->format('d/m/Y H:i:s')
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar los rankings: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la vista de rankings de clientes
     */
    public function UserClienteGraficas(Request $request)
    {
        try {
            // Cálculo de conversión de leads a clientes por usuario
            $rankingConversion = User::select('users.id', 'users.name', 'users.email', 'users.foto_perfil')
                ->selectRaw('COUNT(DISTINCT leads.id) as total_leads')
                ->selectRaw('COUNT(DISTINCT clientes.id) as total_clientes')
                ->selectRaw('ROUND(
                    COUNT(DISTINCT clientes.id) * 100.0 /
                    NULLIF(COUNT(DISTINCT leads.id), 0), 2
                ) as tasa_conversion')
                ->leftJoin('leads', 'users.id', '=', 'leads.id_user')
                ->leftJoin('clientes', function($join) {
                    $join->on('leads.id', '=', 'clientes.id_lead')
                        ->whereNotNull('clientes.id_lead');
                })
                ->groupBy('users.id', 'users.name', 'users.email', 'users.foto_perfil')
                ->having('total_leads', '>', 0)
                ->orderBy('tasa_conversion', 'desc')
                ->orderBy('total_clientes', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($user, $index) {
                    return [
                        'posicion' => $index + 1,
                        'usuario' => $user->name,
                        'email' => $user->email,
                        'foto_perfil' => $user->foto_perfil,
                        'leads_totales' => $user->total_leads,
                        'clientes_conseguidos' => $user->total_clientes,
                        'tasa_conversion' => $user->tasa_conversion
                    ];
                });

            // Top vendedores por monto
            $topVendedores = User::select('users.id', 'users.name', 'users.email', 'users.foto_perfil')
                ->selectRaw('COUNT(DISTINCT clientes.id) as total_clientes')
                ->selectRaw('SUM(clientes.precio_compra) as venta_total')
                ->leftJoin('leads', 'users.id', '=', 'leads.id_user')
                ->leftJoin('clientes', function($join) {
                    $join->on('leads.id', '=', 'clientes.id_lead')
                        ->whereNotNull('clientes.id_lead');
                })
                ->whereNotNull('clientes.id')
                ->groupBy('users.id', 'users.name', 'users.email', 'users.foto_perfil')
                ->orderBy('venta_total', 'desc')
                ->orderBy('total_clientes', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($user, $index) {
                    return [
                        'posicion' => $index + 1,
                        'usuario' => $user->name,
                        'email' => $user->email,
                        'foto_perfil' => $user->foto_perfil,
                        'clientes_conseguidos' => $user->total_clientes,
                        'venta_total' => $user->venta_total ?: 0
                    ];
                });

            // Estadísticas generales
            $totalLeads = Lead::count();
            $totalClientes = Cliente::whereNotNull('id_lead')->count();

            $estadisticas = [
                'total_usuarios' => User::count(),
                'usuarios_con_clientes' => User::whereHas('leads.clientes')->count(),
                'total_clientes' => $totalClientes,
                'tasa_conversion_global' => $totalLeads > 0 ?
                    round(($totalClientes * 100) / $totalLeads, 2) : 0,
                'venta_total' => Cliente::sum('precio_compra') ?: 0
            ];

            return view('analytics.cliente-rankings', [
                'ranking_conversion' => $rankingConversion,
                'top_vendedores' => $topVendedores,
                'estadisticas' => $estadisticas,
                'ultima_actualizacion' => now()->format('d/m/Y H:i:s')
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar los rankings de clientes: ' . $e->getMessage());
        }
    }



        /**
     * Muestra la vista de ranking global de los 30 mejores usuarios
     */
    public function UserGlobalGraficas(Request $request)
    {
        try {
            $hoy = now()->format('Y-m-d');
            $inicioSemana = now()->startOfWeek()->format('Y-m-d');
            $finSemana = now()->endOfWeek()->format('Y-m-d');
            $inicioMes = now()->startOfMonth()->format('Y-m-d');
            $finMes = now()->endOfMonth()->format('Y-m-d');
            $inicioAnio = now()->startOfYear()->format('Y-m-d');
            $finAnio = now()->endOfYear()->format('Y-m-d');

            // Ranking Global (últimos 30 días)
            $rankingGlobal = User::select('users.id', 'users.name', 'users.email', 'users.foto_perfil', 'users.empresa')
                ->selectRaw('COUNT(leads.id) as total_leads')
                ->selectRaw('COUNT(DISTINCT clientes.id) as total_clientes')
                ->selectRaw('COALESCE(SUM(clientes.precio_compra), 0) as venta_total')
                ->selectRaw('ROUND(
                    COUNT(DISTINCT clientes.id) * 100.0 /
                    NULLIF(COUNT(leads.id), 0), 2
                ) as tasa_conversion')
                ->leftJoin('leads', function ($join) use ($inicioMes) {
                    $join->on('users.id', '=', 'leads.id_user')
                        ->where('leads.fecha_creado', '>=', $inicioMes);
                })
                ->leftJoin('clientes', function($join) {
                    $join->on('leads.id', '=', 'clientes.id_lead')
                        ->whereNotNull('clientes.id_lead');
                })
                ->groupBy('users.id', 'users.name', 'users.email', 'users.foto_perfil', 'users.empresa')
                ->orderBy('total_leads', 'desc')
                ->orderBy('venta_total', 'desc')
                ->orderBy('tasa_conversion', 'desc')
                ->limit(30)
                ->get()
                ->map(function ($user, $index) {
                    return [
                        'posicion' => $index + 1,
                        'usuario' => $user->name,
                        'email' => $user->email,
                        'empresa' => $user->empresa,
                        'foto_perfil' => $user->foto_perfil,
                        'leads_totales' => $user->total_leads,
                        'clientes_totales' => $user->total_clientes,
                        'venta_total' => $user->venta_total,
                        'tasa_conversion' => $user->tasa_conversion,
                        'puntaje_global' => $this->calcularPuntajeGlobal(
                            $user->total_leads,
                            $user->total_clientes,
                            $user->venta_total,
                            $user->tasa_conversion
                        )
                    ];
                });

            // Estadísticas generales
            $totalUsuarios = User::count();
            $totalLeadsMes = Lead::where('fecha_creado', '>=', $inicioMes)->count();
            $totalClientesMes = Cliente::where('created_at', '>=', $inicioMes)->count();
            $ventaTotalMes = Cliente::where('created_at', '>=', $inicioMes)->sum('precio_compra') ?: 0;

            $estadisticas = [
                'total_usuarios' => $totalUsuarios,
                'usuarios_top30' => $rankingGlobal->count(),
                'total_leads_mes' => $totalLeadsMes,
                'total_clientes_mes' => $totalClientesMes,
                'venta_total_mes' => $ventaTotalMes,
                'tasa_conversion_mes' => $totalLeadsMes > 0 ?
                    round(($totalClientesMes * 100) / $totalLeadsMes, 2) : 0,
                'promedio_leads_usuario' => $totalUsuarios > 0 ?
                    round($totalLeadsMes / $totalUsuarios, 1) : 0,
                'promedio_venta_usuario' => $rankingGlobal->count() > 0 ?
                    round($ventaTotalMes / $rankingGlobal->count(), 2) : 0,
            ];

            // Datos para gráfico de distribución
            $datosDistribucion = $this->generarDatosDistribucion($rankingGlobal);

            // Categorías de usuarios
            $categoriasUsuarios = $this->clasificarUsuarios($rankingGlobal);

            return view('analytics.global-rankings', [
                'ranking_global' => $rankingGlobal,
                'estadisticas' => $estadisticas,
                'datos_distribucion' => json_encode($datosDistribucion),
                'categorias_usuarios' => $categoriasUsuarios,
                'periodo' => "{$inicioMes} al {$finMes}",
                'ultima_actualizacion' => now()->format('d/m/Y H:i:s')
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el ranking global: ' . $e->getMessage());
        }
    }

    /**
     * Calcula el puntaje global de un usuario
     */
    private function calcularPuntajeGlobal($leads, $clientes, $ventas, $tasaConversion)
    {
        // Pesos: Leads (40%), Ventas (35%), Tasa Conversión (25%)
        $puntajeLeads = min($leads * 2, 100); // Máximo 100 puntos por leads
        $puntajeVentas = min($ventas / 100, 100); // Cada $100 = 1 punto, máximo 100
        $puntajeConversion = $tasaConversion; // Tasa directa como puntos

        $puntajeTotal = ($puntajeLeads * 0.4) + ($puntajeVentas * 0.35) + ($puntajeConversion * 0.25);

        return round($puntajeTotal, 1);
    }

    /**
     * Genera datos para el gráfico de distribución (con rangos de ticket alto)
     */
    private function generarDatosDistribucion($rankingGlobal)
    {
        // Rangos de leads (sin cambios)
        $rangosLeads = ['0-10', '11-25', '26-50', '51-100', '101+'];

        // NUEVO: Rangos de ventas con tickets altos
        $rangosVentas = [
            '$0-$500,000',
            '$500,001-$1,000,000',
            '$1,000,001-$1,500,000',
            '$1,500,001-$2,000,000',
            '$2,000,001-$2,500,000',
            '$2,500,001-$3,000,000',
            '$3,000,000+'
        ];

        $distribucionLeads = array_fill(0, 5, 0);
        $distribucionVentas = array_fill(0, 7, 0); // 7 rangos ahora

        foreach ($rankingGlobal as $usuario) {
            // Distribución por leads (sin cambios)
            if ($usuario['leads_totales'] <= 10) $distribucionLeads[0]++;
            elseif ($usuario['leads_totales'] <= 25) $distribucionLeads[1]++;
            elseif ($usuario['leads_totales'] <= 50) $distribucionLeads[2]++;
            elseif ($usuario['leads_totales'] <= 100) $distribucionLeads[3]++;
            else $distribucionLeads[4]++;

            // Distribución por ventas (CON RANGOS ALTOS)
            $venta = $usuario['venta_total'];

            if ($venta <= 500000) $distribucionVentas[0]++;
            elseif ($venta <= 1000000) $distribucionVentas[1]++;
            elseif ($venta <= 1500000) $distribucionVentas[2]++;
            elseif ($venta <= 2000000) $distribucionVentas[3]++;
            elseif ($venta <= 2500000) $distribucionVentas[4]++;
            elseif ($venta <= 3000000) $distribucionVentas[5]++;
            else $distribucionVentas[6]++; // Más de $3,000,000
        }

        return [
            'labels_leads' => $rangosLeads,
            'data_leads' => $distribucionLeads,
            'labels_ventas' => $rangosVentas,
            'data_ventas' => $distribucionVentas,
        ];
    }

    /**
     * Clasifica a los usuarios en categorías
     */
    private function clasificarUsuarios($rankingGlobal)
    {
        $categorias = [
            'top_performers' => [],
            'consistentes' => [],
            'en_crecimiento' => [],
            'principiantes' => []
        ];

        foreach ($rankingGlobal as $usuario) {
            if ($usuario['posicion'] <= 10 && $usuario['puntaje_global'] >= 70) {
                $categorias['top_performers'][] = $usuario;
            } elseif ($usuario['tasa_conversion'] >= 20 && $usuario['leads_totales'] >= 10) {
                $categorias['consistentes'][] = $usuario;
            } elseif ($usuario['posicion'] > 20 && $usuario['leads_totales'] > 5) {
                $categorias['en_crecimiento'][] = $usuario;
            } else {
                $categorias['principiantes'][] = $usuario;
            }
        }

        return $categorias;
    }
}
