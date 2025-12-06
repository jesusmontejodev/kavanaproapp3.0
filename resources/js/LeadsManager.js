// resources/js/LeadsManager.js - Versión modular para todos los embudos

class LeadsManager {
    constructor(containerId, options = {}) {
        this.containerId = containerId;
        this.container = document.getElementById(containerId);
        this.embudoId = options.embudoId || this.container?.dataset?.embudoId;
        this.token = options.token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.baseURL = options.baseURL || '/api';

        this.leads = [];
        this.etapas = [];
        this.embudo = null;

        this.autoLoad = options.autoLoad !== false;
        this.debug = options.debug !== false;
    }

    // Inicialización automática
    async init() {
        if (!this.container) {
            console.error(`❌ Contenedor #${this.containerId} no encontrado`);
            return this;
        }

        if (!this.embudoId) {
            console.error(`❌ embudoId no definido para #${this.containerId}`);
            return this;
        }

        if (this.debug) {
            console.log(`🚀 Inicializando LeadsManager para embudo ${this.embudoId}`);
        }

        if (this.autoLoad) {
            await this.loadLeads();
        }

        return this;
    }

    // Cargar leads desde la API
    async loadLeads() {
        try {
            this.showLoading();

            const response = await fetch(`${this.baseURL}/embudos/${this.embudoId}/leads`, {
                headers: {
                    'Authorization': `Bearer ${this.token}`,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${await response.text()}`);
            }

            const data = await response.json();

            this.leads = data.leads || [];
            this.etapas = data.etapas || [];
            this.embudo = data.embudo || {};

            if (this.debug) {
                console.log(`✅ Leads cargados: ${this.leads.length} leads, ${this.etapas.length} etapas`);
            }

            this.render();
            this.dispatchEvent('leadsLoaded', data);

        } catch (error) {
            console.error(`❌ Error cargando leads:`, error);
            this.showError(error.message);
            this.dispatchEvent('leadsError', { error });
        }
    }

    // Mostrar estado de carga
    showLoading() {
        if (this.container) {
            this.container.innerHTML = `
                <div class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <p class="mt-2 text-gray-600">Cargando leads...</p>
                </div>
            `;
        }
    }

    // Mostrar error
    showError(message) {
        if (this.container) {
            this.container.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <p class="font-bold">Error</p>
                    <p>${message}</p>
                </div>
            `;
        }
    }

    // Renderizar leads en el contenedor
    render() {
        if (!this.container) return;

        if (this.leads.length === 0) {
            this.container.innerHTML = this.renderNoLeads();
            return;
        }

        this.container.innerHTML = this.renderLeadsGrid();
    }

    // Renderizar cuando no hay leads
    renderNoLeads() {
        return `
            <div class="text-center py-8">
                <p class="text-gray-500 text-lg">📭</p>
                <p class="text-gray-600 mt-2">No hay leads en este embudo</p>
                <button onclick="leadsManager.loadLeads()"
                        class="mt-4 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                    Reintentar
                </button>
            </div>
        `;
    }

    // Renderizar grid de leads por etapa
    renderLeadsGrid() {
        const leadsByStage = this.groupLeadsByStage();

        return `
            <div class="leads-manager">
                ${this.embudo.nombre ? `
                    <div class="embudo-header bg-gray-50 p-4 rounded-lg mb-6">
                        <h2 class="text-xl font-bold">${this.embudo.nombre}</h2>
                        ${this.embudo.descripcion ? `<p class="text-gray-600 mt-1">${this.embudo.descripcion}</p>` : ''}
                        <div class="flex gap-4 mt-2 text-sm text-gray-500">
                            <span>${this.leads.length} leads</span>
                            <span>${this.etapas.length} etapas</span>
                        </div>
                    </div>
                ` : ''}

                <div class="etapas-grid grid grid-cols-1 md:grid-cols-${Math.min(this.etapas.length, 3)} gap-6">
                    ${this.etapas.map(etapa => `
                        <div class="etapa-card bg-white rounded-lg shadow border overflow-hidden">
                            <div class="etapa-header bg-blue-500 text-white p-4">
                                <h3 class="font-bold text-lg">${etapa.nombre}</h3>
                                ${etapa.descripcion ? `<p class="text-blue-100 text-sm mt-1">${etapa.descripcion}</p>` : ''}
                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-blue-200 text-sm">Orden: ${etapa.orden}</span>
                                    <span class="bg-blue-600 text-white px-2 py-1 rounded-full text-xs">
                                        ${leadsByStage[etapa.id]?.length || 0} leads
                                    </span>
                                </div>
                            </div>
                            <div class="etapa-content p-4">
                                ${this.renderLeadsInStage(leadsByStage[etapa.id] || [])}
                            </div>
                        </div>
                    `).join('')}
                </div>

                <div class="mt-6 text-center">
                    <button onclick="leadsManager.loadLeads()"
                            class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                        Actualizar
                    </button>
                </div>
            </div>
        `;
    }

    // Renderizar leads de una etapa específica
    renderLeadsInStage(leads) {
        if (leads.length === 0) {
            return `
                <div class="text-center py-8 text-gray-500">
                    <p>No hay leads en esta etapa</p>
                </div>
            `;
        }

        return `
            <div class="space-y-3">
                ${leads.map(lead => `
                    <div class="lead-card bg-gray-50 border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="lead-header">
                            <h4 class="font-semibold text-gray-800">${lead.nombre || 'Sin nombre'}</h4>
                        </div>
                        <div class="lead-body mt-2 space-y-1">
                            <p class="text-sm text-gray-600 flex items-center">
                                <span class="mr-2">📧</span>
                                ${lead.correo || 'Sin email'}
                            </p>
                            <p class="text-sm text-gray-600 flex items-center">
                                <span class="mr-2">📞</span>
                                ${lead.numero_telefono || 'Sin teléfono'}
                            </p>
                            <p class="text-sm text-gray-500 flex items-center">
                                <span class="mr-2">📅</span>
                                ${this.formatDate(lead.fecha_creado)}
                            </p>
                        </div>
                    </div>
                `).join('')}
            </div>
        `;
    }

    // Agrupar leads por etapa
    groupLeadsByStage() {
        const groups = {};

        this.etapas.forEach(etapa => {
            groups[etapa.id] = this.leads.filter(lead => lead.id_etapa === etapa.id);
        });

        return groups;
    }

    // Formatear fecha
    formatDate(dateString) {
        if (!dateString) return 'Sin fecha';
        return new Date(dateString).toLocaleDateString('es-ES');
    }

    // Manejar eventos
    dispatchEvent(name, detail) {
        const event = new CustomEvent(`leads:${name}`, { detail });
        document.dispatchEvent(event);
    }

    on(eventName, callback) {
        document.addEventListener(`leads:${eventName}`, callback);
    }

    off(eventName, callback) {
        document.removeEventListener(`leads:${eventName}`, callback);
    }
}

// Auto-inicialización para elementos con data-leads-manager
document.addEventListener('DOMContentLoaded', function() {
    const leadContainers = document.querySelectorAll('[data-leads-manager]');

    leadContainers.forEach(container => {
        const manager = new LeadsManager(container.id, {
            debug: true,
            autoLoad: true
        });
        manager.init();

        // Guardar referencia para acceso global
        window[`leadsManager_${container.id}`] = manager;
    });
});

// Exportar para uso modular
window.LeadsManager = LeadsManager;
