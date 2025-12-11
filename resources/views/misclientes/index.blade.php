<x-app-layout>
    <div class="container">
        <div class="header">
            <h1 class="title">Gestión de Clientes</h1>
            <p class="subtitle">Convierte leads en clientes y gestiona tu cartera de clientes</p>
        </div>

        <!-- Mostrar mensajes -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- SECCIÓN: Mis Clientes -->
        @if($misClientes && $misClientes->count() > 0)
        <div class="clientes-section">
            <h2 class="section-title">Mis Clientes</h2>
            <div class="clientes-grid">
                @foreach ($misClientes as $cliente)
                <div class="cliente-card">
                    <div class="cliente-header">
                        <h4 class="cliente-name">{{ $cliente->nombre_completo }}</h4>
                    </div>

                    <div class="cliente-info">
                        <div class="cliente-email">
                            <strong>Email:</strong> {{ $cliente->email }}
                        </div>

                        @if($cliente->telefono)
                            <div class="cliente-telefono">
                                <strong>Teléfono:</strong> {{ $cliente->telefono }}
                            </div>
                        @endif

                        @if($cliente->inmueble_comprado)
                            <div class="cliente-inmueble">
                                <strong>Inmueble:</strong> {{ $cliente->inmueble_comprado }}
                            </div>
                        @endif

                        @if($cliente->precio_compra)
                            <div class="cliente-precio">
                                <strong>Precio:</strong> {{ $cliente->precio_compra_formateado }}
                            </div>
                        @endif
                    </div>

                    <div class="cliente-meta">
                        <span class="cliente-fecha">
                            Cliente desde: {{ $cliente->created_at->format('d/m/Y') }}
                        </span>
                        @if($cliente->fecha_entrega_estimada)
                            <span class="cliente-entrega">
                                Entrega estimada: {{ $cliente->fecha_entrega_estimada->format('d/m/Y') }}
                            </span>
                        @endif

                        <a href="{{ route('misclientes.show', $cliente->id) }}"
                        class="inline-flex items-center px-4 py-2 border border-blue-600 text-blue-600 font-medium rounded-lg hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors duration-200">
                            <i class="fas fa-user-circle mr-2"></i>
                            Ver Cliente
                        </a>

                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- SECCIÓN: Solicitudes Enviadas -->
        @if($misSolicitudes && $misSolicitudes->count() > 0)
        <div class="solicitudes-section">
            <h2 class="section-title">Tus Solicitudes Enviadas</h2>
            <div class="solicitudes-grid">
                @foreach ($misSolicitudes as $solicitud)
                <div class="solicitud-card">
                    <div class="solicitud-header">
                        <h4 class="solicitud-lead">
                            @if($solicitud->lead)
                                {{ $solicitud->lead->nombre }} {{ $solicitud->lead->apellido }}
                            @else
                                Lead no encontrado
                            @endif
                        </h4>
                        <span class="estado-badge estado-{{ $solicitud->estado ?? 'pendiente' }}">
                            {{ ucfirst($solicitud->estado ?? 'pendiente') }}
                        </span>
                    </div>

                    <p class="solicitud-descripcion">{{ $solicitud->descripcion_solicitud }}</p>

                    <div class="solicitud-meta">
                        <span class="solicitud-fecha">
                            Enviada: {{ $solicitud->created_at->format('d/m/Y H:i') }}
                        </span>
                        @if($solicitud->fecha_revision)
                            <span class="solicitud-revision">
                                Revisada:
                                {{-- CORRECCIÓN: Usar Carbon::parse para convertir string a fecha --}}
                                {{ \Carbon\Carbon::parse($solicitud->fecha_revision)->format('d/m/Y H:i') }}
                            </span>
                        @endif
                    </div>

                    @if($solicitud->comentario_admin)
                        <div class="comentario-admin">
                            <strong>Comentario del administrador:</strong>
                            <p>{{ $solicitud->comentario_admin }}</p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- SECCIÓN: Leads Disponibles para Conversión -->
        @if($leads->count() > 0)
            <div class="leads-section">
                <h2 class="section-title">Convertir Leads a Clientes</h2>
                <div class="leads-grid">
                    @foreach ($leads as $lead)
                    <form method="POST" action="{{ route('solicitudcliente.store') }}" class="lead-form">
                        @csrf

                        <div class="form-header">
                            <h3 class="form-title">Convertir a cliente</h3>
                            <div class="lead-info">
                                <div class="lead-name">{{ $lead->nombre }} {{ $lead->apellido }}</div>
                                <div class="lead-email">{{ $lead->correo }}</div>
                            </div>
                        </div>

                        <input type="hidden" name="id_lead" value="{{ $lead->id }}">

                        <div class="form-group">
                            <label for="descripcion_solicitud_{{ $lead->id }}" class="form-label">
                                Descripción de la solicitud:
                            </label>
                            <textarea
                                name="descripcion_solicitud"
                                id="descripcion_solicitud_{{ $lead->id }}"
                                class="form-control"
                                rows="4"
                                placeholder="Describe por qué quieres convertir este lead a cliente, incluye información relevante..."
                                required
                            ></textarea>
                        </div>

                        <button type="submit" class="submit-button">
                            <span class="button-text">Enviar solicitud</span>
                            <span class="button-icon">→</span>
                        </button>
                    </form>
                    @endforeach
                </div>
            </div>
        @else
            <div class="empty-state">
                <h3>No hay leads disponibles</h3>
                <p>No tienes leads para convertir en clientes en este momento.</p>
            </div>
        @endif
    </div>

    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin: 40px 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #f3f4f6;
        }

        /* Alertas */
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        /* Estilos para Clientes */
        .clientes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .cliente-card {
            background: white;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #059669;
        }

        .cliente-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .cliente-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .cliente-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .estado-contrato_firmado {
            background-color: #fef3c7;
            color: #92400e;
        }

        .estado-proceso_escrituras {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .estado-avance_obra {
            background-color: #fce7f3;
            color: #be185d;
        }

        .estado-ultimos_detalles {
            background-color: #f0fdf4;
            color: #166534;
        }

        .estado-entrega_finalizada {
            background-color: #d1fae5;
            color: #065f46;
        }

        .cliente-info {
            margin-bottom: 12px;
        }

        .cliente-info div {
            margin-bottom: 6px;
            color: #4b5563;
        }

        .cliente-info strong {
            color: #374151;
        }

        .cliente-meta {
            font-size: 0.875rem;
            color: #6b7280;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        /* Estilos para Solicitudes */
        .solicitudes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .solicitud-card {
            background: white;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .solicitud-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .solicitud-lead {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .estado-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .estado-pendiente {
            background-color: #fef3c7;
            color: #92400e;
        }

        .estado-aprobada {
            background-color: #d1fae5;
            color: #065f46;
        }

        .estado-rechazada {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .solicitud-descripcion {
            color: #4b5563;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        .solicitud-meta {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .comentario-admin {
            margin-top: 12px;
            padding: 12px;
            background-color: #f8fafc;
            border-radius: 8px;
            border-left: 4px solid #fbbf24;
        }

        .comentario-admin strong {
            color: #374151;
        }

        .comentario-admin p {
            margin: 4px 0 0 0;
            color: #4b5563;
        }

        /* Estilos para Leads */
        .leads-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
            margin-top: 20px;
        }

        .lead-form {
            background: white;
            padding: 24px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .lead-form:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        .form-header {
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f3f4f6;
        }

        .form-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .lead-info {
            color: #4b5563;
        }

        .lead-name {
            font-weight: 600;
            font-size: 1.1rem;
            color: #111827;
        }

        .lead-email {
            color: #6b7280;
            margin: 4px 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
            resize: vertical;
            min-height: 100px;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #fbbf24;
            box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.1);
        }

        .submit-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
            border: none;
            border-radius: 8px;
            padding: 12px 24px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .submit-button:hover {
            background: linear-gradient(135deg, #fde68a 0%, #fcd34d 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
        }

        .submit-button:active {
            transform: translateY(0);
        }

        .button-text {
            font-weight: 600;
        }

        .button-icon {
            font-weight: bold;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-state h3 {
            color: #374151;
            margin-bottom: 8px;
            font-size: 1.5rem;
        }

        .empty-state p {
            font-size: 1rem;
            margin: 0;
        }

        /* Estados adicionales para clientes */
        .cliente-inmueble,
        .cliente-precio,
        .cliente-direccion {
            font-size: 0.9rem;
        }

        .cliente-entrega {
            color: #059669;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .clientes-grid,
            .solicitudes-grid,
            .leads-grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 16px;
            }

            .title {
                font-size: 2rem;
            }

            .section-title {
                font-size: 1.3rem;
                margin: 30px 0 15px 0;
            }

            .solicitud-header,
            .cliente-header {
                flex-direction: column;
                gap: 8px;
            }

            .lead-form {
                padding: 20px;
            }

            .form-title {
                font-size: 1.1rem;
            }

            .cliente-name,
            .solicitud-lead {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 480px) {
            .title {
                font-size: 1.75rem;
            }

            .subtitle {
                font-size: 1rem;
            }

            .lead-form {
                padding: 16px;
            }

            .form-control {
                padding: 10px;
                font-size: 13px;
            }

            .submit-button {
                padding: 10px 20px;
                font-size: 13px;
            }

            .cliente-card,
            .solicitud-card {
                padding: 16px;
            }
        }

        /* Mejoras de accesibilidad */
        .submit-button:focus {
            outline: 2px solid #fbbf24;
            outline-offset: 2px;
        }

        .form-control:focus {
            outline: 2px solid #fbbf24;
            outline-offset: 2px;
        }

        /* Animaciones suaves */
        .cliente-card,
        .solicitud-card,
        .lead-form {
            transition: all 0.2s ease-in-out;
        }

        /* Estados de carga */
        .submit-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .submit-button:disabled:hover {
            transform: none;
            box-shadow: none;
        }
    </style>
</x-app-layout>
