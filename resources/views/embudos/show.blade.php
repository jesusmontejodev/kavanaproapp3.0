<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-bold">Embudo: {{ $embudo->nombre }}</h1>
        <p class="text-gray-600">{{ $embudo->descripcion }}</p>
        {{-- Mostrar etapas con leads --}}
        <div class="mt-4">
            <h2 class="text-xl font-semibold mb-3">Leads por Etapa</h2>

            <div class="flex space-x-2 overflow-x-auto pb-3" id="etapas-container">
                @foreach($embudo->etapas as $etapa)
                <div class="bg-white rounded-lg shadow-sm border p-3 min-w-72 flex-shrink-0 min-h-[60vh] flex flex-col" id="etapa-{{ $etapa->id }}">
                    <h3 class="font-bold text-base bg-blue-500 text-white p-2 rounded -mx-3 -mt-3 mb-3 text-center flex-shrink-0">
                        {{ $etapa->nombre }}
                    </h3>
                    <div id="leads-etapa-{{ $etapa->id }}" class="leads-container flex-1 overflow-y-auto space-y-2">
                        <!-- Los leads se cargarán aquí -->
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Formulario crear lead --}}
        <div class="mt-8 bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4">Crear Nuevo Lead</h2>
            <form id="lead-form">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre *</label>
                        <input type="text" name="nombre" id="nombre" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <div>
                        <label for="correo" class="block text-sm font-medium text-gray-700">Correo *</label>
                        <input type="email" name="correo" id="correo" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <div>
                        <label for="numero_telefono" class="block text-sm font-medium text-gray-700">Teléfono *</label>
                        <input type="text" name="numero_telefono" id="numero_telefono" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                    </div>

                    <div>
                        <label for="fecha_creado" class="block text-sm font-medium text-gray-700">Fecha *</label>
                        <input type="date" name="fecha_creado" id="fecha_creado" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                            value="{{ now()->format('Y-m-d') }}">
                    </div>

                    <div>
                        <label for="id_etapa" class="block text-sm font-medium text-gray-700">Etapa *</label>
                        <select name="id_etapa" id="id_etapa" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                            <option value="">Selecciona una etapa</option>
                            @foreach($embudo->etapas as $etapa)
                                <option value="{{ $etapa->id }}">{{ $etapa->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit"
                            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                        Crear Lead
                    </button>
                </div>
            </form>
        </div>

    </div>

    <div id="app-config"
        data-embudo-id="{{ $embudo->id }}"
        data-token="{{ $token }}"
        data-api-base="/api">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // Leer configuración
        const config = document.getElementById('app-config').dataset;
        const EMBUDO_ID = config.embudoId;
        const TOKEN = config.token;

        // Función para obtener el orden actual de una etapa ANTES del movimiento
        function obtenerOrdenEtapaAntesDelMovimiento(container, indexMovido, leadIdMovido) {
            const leads = Array.from(container.querySelectorAll('.lead-item'));
            const leadsFiltrados = leads.filter(lead => lead.dataset.leadId !== leadIdMovido.toString());

            return leadsFiltrados.map((lead, index) => ({
                id_lead: parseInt(lead.dataset.leadId),
                nuevo_orden: index + 1
            }));
        }

        // NUEVA: Función para obtener orden COMPLETO (incluye lead movido)
        function obtenerOrdenEtapaCompleto(container) {
            const leads = Array.from(container.querySelectorAll('.lead-item'));
            return leads.map((lead, index) => ({
                id_lead: parseInt(lead.dataset.leadId),
                nuevo_orden: index + 1
            }));
        }

        // Función para obtener el orden actual de una etapa
        function obtenerOrdenEtapa(etapaId) {
            const container = document.getElementById(`leads-etapa-${etapaId}`);
            if (!container) return [];

            const leads = Array.from(container.querySelectorAll('.lead-item'));
            return leads.map((lead, index) => ({
                id_lead: parseInt(lead.dataset.leadId),
                nuevo_orden: index + 1
            }));
        }

        // Función para ordenar leads en el cliente
        function ordenarLeads(leads) {
            return leads.sort((a, b) => {
                if (a.id_etapa !== b.id_etapa) {
                    return a.id_etapa - b.id_etapa;
                }
                return a.orden - b.orden;
            });
        }

        // Función para agrupar leads por etapa después de ordenar
        function agruparLeadsPorEtapa(leads) {
            const grupos = {};
            leads.forEach(lead => {
                if (!grupos[lead.id_etapa]) {
                    grupos[lead.id_etapa] = [];
                }
                grupos[lead.id_etapa].push(lead);
            });
            return grupos;
        }

        // Inicializar Sortable.js después de cargar los leads
        function inicializarSortable() {
            @foreach($embudo->etapas as $etapa)
            new Sortable(document.getElementById('leads-etapa-{{ $etapa->id }}'), {
                group: 'leads',
                animation: 150,
                ghostClass: 'bg-blue-50',
                chosenClass: 'bg-blue-100',
                dragClass: 'opacity-50',

                onEnd: function(evt) {
                    const leadId = evt.item.dataset.leadId;
                    const etapaOrigenId = evt.from.id.replace('leads-etapa-', '');
                    const etapaDestinoId = evt.to.id.replace('leads-etapa-', '');
                    const nuevaPosicion = evt.newIndex + 1;

                    console.log('🔄 Movimiento detectado:');
                    console.log('   Lead:', leadId);
                    console.log('   Etapa origen:', etapaOrigenId);
                    console.log('   Etapa destino:', etapaDestinoId);
                    console.log('   Nueva posición:', nuevaPosicion);

                    // DETERMINAR TIPO DE MOVIMIENTO
                    const esMovimientoInterno = (etapaOrigenId === etapaDestinoId);

                    if (esMovimientoInterno) {
                        console.log('📌 Movimiento INTERNO en la misma etapa');
                        const etapaCompleta = obtenerOrdenEtapaCompleto(evt.to);
                        actualizarOrdenInterno(etapaDestinoId, etapaCompleta);
                    } else {
                        console.log('🚀 Movimiento ENTRE etapas diferentes');
                        const etapaOrigen = obtenerOrdenEtapaAntesDelMovimiento(evt.from, evt.oldIndex, leadId);
                        const etapaDestino = obtenerOrdenEtapaAntesDelMovimiento(evt.to, evt.newIndex, leadId);
                        actualizarEtapaLead(leadId, etapaDestinoId, etapaOrigenId, etapaOrigen, etapaDestino, nuevaPosicion);
                    }
                }
            });
            @endforeach
        }

        // Función para actualizar la etapa del lead via API (movimiento entre etapas)
        function actualizarEtapaLead(leadId, nuevaEtapaId, etapaOrigenId, etapaOrigen, etapaDestino, nuevaPosicion) {
            const datosParaAPI = {
                etapaOrigen: etapaOrigen,
                etapaDestino: etapaDestino,
                frameLead: {
                    id_lead: parseInt(leadId),
                    id_nueva_etapa: parseInt(nuevaEtapaId),
                    nueva_posicion: nuevaPosicion
                }
            };

            console.log('📨 Enviando a API (entre etapas):', datosParaAPI);

            fetch(`/api/leads/actualizaretapayorden`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${TOKEN}`
                },
                body: JSON.stringify(datosParaAPI)
            })
            .then(response => response.text().then(text => {
                try {
                    const data = JSON.parse(text);
                    if (!response.ok) throw new Error(data.error || `Error: ${response.status}`);
                    return data;
                } catch (e) {
                    throw new Error(text.includes('<!DOCTYPE html>')
                        ? 'Error interno del servidor'
                        : `Error: ${text.substring(0, 100)}`);
                }
            }))
            .then(data => {
                console.log('✅ Lead movido entre etapas:', data);
                mostrarNotificacion('Lead movido correctamente', 'success');

                const leadElement = document.querySelector(`[data-lead-id="${leadId}"]`);
                if (leadElement) {
                    leadElement.setAttribute('data-orden', nuevaPosicion);
                }

                actualizarContadoresLeads();
            })
            .catch(error => {
                console.error('❌ Error:', error);
                mostrarNotificacion(error.message, 'error');
            });
        }

        // NUEVA: Función para actualizar orden interno
        function actualizarOrdenInterno(etapaId, leadsConOrden) {
            const datosParaAPI = {
                etapaActualizada: leadsConOrden,
                tipo: 'interno'
            };

            console.log('📨 Actualizando orden interno:', datosParaAPI);

            fetch(`/api/leads/actualizar-orden-interno`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${TOKEN}`
                },
                body: JSON.stringify(datosParaAPI)
            })
            .then(response => response.text().then(text => {
                try {
                    const data = JSON.parse(text);
                    if (!response.ok) throw new Error(data.error || `Error: ${response.status}`);
                    return data;
                } catch (e) {
                    throw new Error(text.includes('<!DOCTYPE html>')
                        ? 'Error interno del servidor'
                        : `Error: ${text.substring(0, 100)}`);
                }
            }))
            .then(data => {
                console.log('✅ Orden interno actualizado:', data);
                mostrarNotificacion('Orden actualizado correctamente', 'success');

                // Actualizar data-attributes
                leadsConOrden.forEach(lead => {
                    const leadElement = document.querySelector(`[data-lead-id="${lead.id_lead}"]`);
                    if (leadElement) {
                        leadElement.setAttribute('data-orden', lead.nuevo_orden);
                    }
                });
            })
            .catch(error => {
                console.error('❌ Error al actualizar orden interno:', error);
                mostrarNotificacion(error.message, 'error');
            });
        }

        // Función para mostrar notificaciones
        function mostrarNotificacion(mensaje, tipo = 'info') {
            const colores = {
                success: 'bg-green-500',
                error: 'bg-red-500',
                info: 'bg-blue-500'
            };

            const notificacion = document.createElement('div');
            notificacion.className = `fixed top-4 right-4 ${colores[tipo]} text-white px-6 py-3 rounded-lg shadow-lg z-50`;
            notificacion.textContent = mensaje;

            document.body.appendChild(notificacion);

            setTimeout(() => {
                notificacion.remove();
            }, 3000);
        }

        // Función para cargar leads en las etapas
        function cargarLeadsEnEtapas(leads) {
            console.log('🎨 Renderizando leads...');

            // Limpiar contenedores
            @foreach($embudo->etapas as $etapa)
                const container{{ $etapa->id }} = document.getElementById(`leads-etapa-{{ $etapa->id }}`);
                if (container{{ $etapa->id }}) container{{ $etapa->id }}.innerHTML = '';
            @endforeach

            // Agregar leads ordenados
            leads.forEach(lead => {
                const container = document.getElementById(`leads-etapa-${lead.id_etapa}`);
                if (container) {
                    container.appendChild(crearElementoLead(lead));
                }
            });

            actualizarContadoresLeads();
            console.log('✅ Leads renderizados');
        }

        // Función para actualizar contadores
        function actualizarContadoresLeads() {
            console.log('📊 Actualizando contadores...');
            @foreach($embudo->etapas as $etapa)
                const container{{ $etapa->id }} = document.getElementById(`leads-etapa-{{ $etapa->id }}`);
                if (container{{ $etapa->id }}) {
                    const count = container{{ $etapa->id }}.querySelectorAll('.lead-item').length;
                    console.log(`   Etapa {{ $etapa->id }}: ${count} leads`);
                }
            @endforeach
        }

        // Función para crear elemento lead
        function crearElementoLead(lead) {
            const leadDiv = document.createElement('div');
            leadDiv.className = 'lead-item bg-white p-3 rounded shadow border mb-2 cursor-move hover:shadow-md transition-shadow';
            leadDiv.setAttribute('data-lead-id', lead.id);
            leadDiv.setAttribute('data-orden', lead.orden);
            leadDiv.innerHTML = `
                <div class="flex justify-between items-start">
                    <h4 class="font-semibold text-gray-800">${lead.nombre}</h4>
                    <div class="flex gap-1">
                        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded">Orden: ${lead.orden}</span>
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">Drag</span>
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-1">📧 ${lead.correo}</p>
                <p class="text-sm text-gray-600">📞 ${lead.numero_telefono}</p>
                <div class="flex justify-between items-center mt-2">
                    <span class="text-xs text-gray-400">${lead.fecha_creado}</span>
                    <button onclick="eliminarLead(${lead.id})" class="text-red-500 hover:text-red-700 text-xs">×</button>
                </div>
            `;
            return leadDiv;
        }

        // Función para eliminar lead
        function eliminarLead(leadId) {
            if (!confirm('¿Estás seguro de eliminar este lead?')) return;

            fetch(`/api/leads/${leadId}`, {
                method: 'DELETE',
                headers: {
                    'Authorization': `Bearer ${TOKEN}`
                }
            })
            .then(response => {
                if (response.ok) {
                    document.querySelector(`[data-lead-id="${leadId}"]`).remove();
                    mostrarNotificacion('Lead eliminado', 'success');
                    actualizarContadoresLeads();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error al eliminar', 'error');
            });
        }

        // Función para debug
        window.mostrarOrdenActual = function() {
            console.log('🔍 ORDEN ACTUAL:');
            @foreach($embudo->etapas as $etapa)
                const container{{ $etapa->id }} = document.getElementById(`leads-etapa-{{ $etapa->id }}`);
                if (container{{ $etapa->id }}) {
                    const leads = Array.from(container{{ $etapa->id }}.querySelectorAll('.lead-item'));
                    console.log(`Etapa {{ $etapa->id }} ({{ $etapa->nombre }}):`);
                    leads.forEach((lead, index) => {
                        const leadId = lead.dataset.leadId;
                        const orden = lead.dataset.orden;
                        const nombre = lead.querySelector('h4').textContent;
                        console.log(`   ${index + 1}. ${nombre} (ID: ${leadId}, Orden: ${orden})`);
                    });
                }
            @endforeach
        };

        // Función principal para cargar leads
        function cargarLeads() {
            console.log('🚀 Cargando leads...');

            fetch(`/api/embudos/${EMBUDO_ID}/leads`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${TOKEN}`
                }
            })
            .then(response => {
                if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
                return response.json();
            })
            .then(data => {
                console.log('📦 Datos recibidos:', data.leads);

                const leadsOrdenados = ordenarLeads(data.leads);
                console.log('🔄 Leads ordenados:', leadsOrdenados);

                const leadsAgrupados = agruparLeadsPorEtapa(leadsOrdenados);
                console.log('📊 Leads agrupados:', leadsAgrupados);

                cargarLeadsEnEtapas(leadsOrdenados);
                inicializarSortable();
            })
            .catch(error => {
                console.error('❌ Error:', error);
                mostrarNotificacion('Error al cargar leads', 'error');
            });
        }

        // Inicializar aplicación
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🏠 DOM cargado');
            console.log('⚙️ Configuración:', { EMBUDO_ID, TOKEN: TOKEN ? '✅' : '❌' });

            const etapasContainer = document.getElementById('etapas-container');
            if (!etapasContainer) {
                console.error('❌ No se encontró el contenedor de etapas');
                return;
            }

            cargarLeads();

            // Botón de recarga manual
            const recargarBtn = document.createElement('button');
            recargarBtn.textContent = '🔄 Recargar Leads';
            recargarBtn.className = 'fixed bottom-4 right-4 bg-gray-600 text-white px-4 py-2 rounded-lg shadow-lg z-50 hover:bg-gray-700 transition';
            recargarBtn.onclick = cargarLeads;
            document.body.appendChild(recargarBtn);

            console.log('🎉 Aplicación inicializada');
        });

        // Manejar errores no capturados
        window.addEventListener('error', function(e) {
            console.error('💥 Error no capturado:', e.error);
            mostrarNotificacion('Error en la aplicación', 'error');
        });
    </script>
    @vite([ 'resources/js/MakeLeadForm.js'])
</x-app-layout>
