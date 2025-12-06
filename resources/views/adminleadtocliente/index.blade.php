<x-app-layout>
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">Panel de Administración - Solicitudes de Clientes</h1>
            <p class="admin-subtitle">Gestiona las solicitudes de conversión de leads a clientes</p>
        </div>

        <!-- Estadísticas -->
        <div class="estadisticas-grid">
            <div class="estadistica-card">
                <div class="estadistica-icon pendiente">
                    <span>📋</span>
                </div>
                <div class="estadistica-info">
                    <h3>{{ $estadisticas['pendientes'] }}</h3>
                    <p>Solicitudes Pendientes</p>
                </div>
            </div>
            <div class="estadistica-card">
                <div class="estadistica-icon aprobada">
                    <span>✅</span>
                </div>
                <div class="estadistica-info">
                    <h3>{{ $estadisticas['aprobadas'] }}</h3>
                    <p>Solicitudes Aprobadas</p>
                </div>
            </div>
            <div class="estadistica-card">
                <div class="estadistica-icon rechazada">
                    <span>❌</span>
                </div>
                <div class="estadistica-info">
                    <h3>{{ $estadisticas['rechazadas'] }}</h3>
                    <p>Solicitudes Rechazadas</p>
                </div>
            </div>
            <div class="estadistica-card">
                <div class="estadistica-icon clientes">
                    <span>👥</span>
                </div>
                <div class="estadistica-info">
                    <h3>{{ $estadisticas['total_clientes'] }}</h3>
                    <p>Total Clientes</p>
                </div>
            </div>
        </div>

        <!-- Solicitudes Pendientes -->
        <div class="solicitudes-section">
            <div class="section-header">
                <h2 class="section-title">Solicitudes Pendientes</h2>
                <a href="{{ route('adminleadtocliente.historial') }}" class="btn-historial">
                    Ver Historial Completo
                </a>
            </div>

            @if($solicitudesPendientes->count() > 0)
                <div class="solicitudes-grid">
                    @foreach($solicitudesPendientes as $solicitud)
                    <div class="solicitud-card">
                        <div class="solicitud-header">
                            <div class="solicitud-info">
                                <h3 class="solicitud-lead">
                                    {{ $solicitud->lead->nombre }} {{ $solicitud->lead->apellido }}
                                </h3>
                                <p class="solicitud-agente">
                                    Agente: <strong>{{ $solicitud->user->name }}</strong>
                                </p>
                                <p class="solicitud-contacto">
                                    📧 {{ $solicitud->lead->correo }}
                                    @if($solicitud->lead->telefono)
                                        | 📞 {{ $solicitud->lead->telefono }}
                                    @endif
                                </p>
                            </div>
                            <span class="estado-badge estado-pendiente">Pendiente</span>
                        </div>

                        <div class="solicitud-descripcion">
                            <h4>Descripción de la solicitud:</h4>
                            <p>{{ $solicitud->descripcion_solicitud }}</p>
                        </div>

                        <div class="solicitud-meta">
                            <span class="solicitud-fecha">
                                📅 Enviada: {{ $solicitud->created_at->format('d/m/Y H:i') }}
                            </span>
                        </div>

                        <!-- Formulario para Aprobar -->
                        <form method="POST" action="{{ route('adminleadtocliente.aprobar', $solicitud->id) }}" class="form-aprobar">
                            @csrf
                            <div class="form-section">
                                <h4>Información del Inmueble</h4>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="inmueble_comprado_{{ $solicitud->id }}">Inmueble Comprado *</label>
                                        <input type="text" name="inmueble_comprado" id="inmueble_comprado_{{ $solicitud->id }}"
                                               class="form-control" required placeholder="Ej: Casa en Residencial Las Lomas">
                                    </div>
                                    <div class="form-group">
                                        <label for="precio_compra_{{ $solicitud->id }}">Precio de Compra *</label>
                                        <input type="number" step="0.01" name="precio_compra" id="precio_compra_{{ $solicitud->id }}"
                                               class="form-control" required placeholder="Ej: 250000.00">
                                    </div>
                                    <div class="form-group">
                                        <label for="tipo_inmueble_{{ $solicitud->id }}">Tipo de Inmueble *</label>
                                        <select name="tipo_inmueble" id="tipo_inmueble_{{ $solicitud->id }}" class="form-control" required>
                                            <option value="">Seleccionar...</option>
                                            <option value="casa">Casa</option>
                                            <option value="apartamento">Apartamento</option>
                                            <option value="local">Local Comercial</option>
                                            <option value="oficina">Oficina</option>
                                            <option value="terreno">Terreno</option>
                                            <option value="otros">Otros</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_entrega_estimada_{{ $solicitud->id }}">Fecha Entrega Estimada *</label>
                                        <input type="date" name="fecha_entrega_estimada" id="fecha_entrega_estimada_{{ $solicitud->id }}"
                                               class="form-control" required min="{{ now()->format('Y-m-d') }}">
                                    </div>
                                </div>
                                <div class="form-group full-width">
                                    <label for="direccion_inmueble_{{ $solicitud->id }}">Dirección del Inmueble *</label>
                                    <textarea name="direccion_inmueble" id="direccion_inmueble_{{ $solicitud->id }}"
                                              class="form-control" rows="2" required
                                              placeholder="Dirección completa del inmueble"></textarea>
                                </div>
                                <div class="form-group full-width">
                                    <label for="comentario_admin_aprobar_{{ $solicitud->id }}">Comentario (Opcional)</label>
                                    <textarea name="comentario_admin" id="comentario_admin_aprobar_{{ $solicitud->id }}"
                                              class="form-control" rows="2"
                                              placeholder="Comentarios adicionales para el agente..."></textarea>
                                </div>
                            </div>

                            <div class="solicitud-actions">
                                <button type="submit" class="btn-aprobar">
                                    ✅ Aprobar y Crear Cliente
                                </button>
                            </div>
                        </form>

                        <!-- Formulario para Rechazar -->
                        <form method="POST" action="{{ route('adminleadtocliente.rechazar', $solicitud->id) }}" class="form-rechazar">
                            @csrf
                            <div class="form-group full-width">
                                <label for="comentario_admin_rechazar_{{ $solicitud->id }}">Motivo del Rechazo *</label>
                                <textarea name="comentario_admin" id="comentario_admin_rechazar_{{ $solicitud->id }}"
                                          class="form-control" rows="2" required
                                          placeholder="Explica al agente por qué se rechaza la solicitud..."></textarea>
                            </div>
                            <div class="solicitud-actions">
                                <button type="submit" class="btn-rechazar">
                                    ❌ Rechazar Solicitud
                                </button>
                            </div>
                        </form>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">🎉</div>
                    <h3>No hay solicitudes pendientes</h3>
                    <p>Todas las solicitudes han sido revisadas. ¡Buen trabajo!</p>
                </div>
            @endif
        </div>
    </div>

    <style>
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .admin-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .admin-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 10px;
        }

        .admin-subtitle {
            font-size: 1.1rem;
            color: #6b7280;
            margin-bottom: 20px;
        }

        /* Estadísticas */
        .estadisticas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .estadistica-card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 16px;
            border-left: 4px solid #e5e7eb;
        }

        .estadistica-card:nth-child(1) { border-left-color: #f59e0b; }
        .estadistica-card:nth-child(2) { border-left-color: #10b981; }
        .estadistica-card:nth-child(3) { border-left-color: #ef4444; }
        .estadistica-card:nth-child(4) { border-left-color: #3b82f6; }

        .estadistica-icon {
            font-size: 2rem;
        }

        .estadistica-info h3 {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .estadistica-info p {
            color: #6b7280;
            margin: 4px 0 0 0;
            font-weight: 500;
        }

        /* Sección de Solicitudes */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
        }

        .btn-historial {
            background: #6b7280;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-historial:hover {
            background: #4b5563;
            transform: translateY(-1px);
        }

        /* Grid de Solicitudes */
        .solicitudes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 24px;
        }

        .solicitud-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .solicitud-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f3f4f6;
        }

        .solicitud-info h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0 0 8px 0;
        }

        .solicitud-agente, .solicitud-contacto {
            color: #6b7280;
            margin: 4px 0;
            font-size: 0.9rem;
        }

        .estado-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .estado-pendiente {
            background: #fef3c7;
            color: #92400e;
        }

        .solicitud-descripcion {
            margin-bottom: 16px;
        }

        .solicitud-descripcion h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #374151;
            margin: 0 0 8px 0;
        }

        .solicitud-descripcion p {
            color: #4b5563;
            line-height: 1.5;
            margin: 0;
        }

        .solicitud-meta {
            font-size: 0.875rem;
            color: #6b7280;
            margin-bottom: 20px;
        }

        /* Formularios */
        .form-section {
            margin-bottom: 20px;
            padding: 16px;
            background: #f8fafc;
            border-radius: 8px;
        }

        .form-section h4 {
            font-size: 1rem;
            font-weight: 600;
            color: #374151;
            margin: 0 0 16px 0;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }

        .form-group {
            margin-bottom: 12px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-aprobar {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        /* Botones de Acción */
        .solicitud-actions {
            display: flex;
            gap: 12px;
        }

        .btn-aprobar, .btn-rechazar {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.875rem;
        }

        .btn-aprobar {
            background: #10b981;
            color: white;
        }

        .btn-aprobar:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .btn-rechazar {
            background: #ef4444;
            color: white;
        }

        .btn-rechazar:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        /* Estado Vacío */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 16px;
        }

        .empty-state h3 {
            color: #374151;
            margin-bottom: 8px;
            font-size: 1.5rem;
        }

        .empty-state p {
            margin: 0;
            font-size: 1rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-container {
                padding: 16px;
            }

            .admin-title {
                font-size: 2rem;
            }

            .estadisticas-grid {
                grid-template-columns: 1fr;
            }

            .solicitudes-grid {
                grid-template-columns: 1fr;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .section-header {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .solicitud-header {
                flex-direction: column;
                gap: 12px;
            }

            .solicitud-actions {
                flex-direction: column;
            }
        }
    </style>
</x-app-layout>
