<x-app-layout>
    <div class="space-y-8">
        <!-- Sección de Embudos -->
        <div>


            @if($embudos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($embudos as $embudo)
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow duration-300 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-800 truncate">
                                            {{ $embudo->nombre }}
                                        </h3>
                                        @if($embudo->descripcion)
                                            <p class="text-sm text-gray-500 mt-2 truncate">
                                                {{ $embudo->descripcion }}
                                            </p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1">ID: {{ $embudo->id }}</p>
                                    </div>
                                    {{-- @if($embudo->leads_count > 0)
                                        <span class="flex-shrink-0 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 ml-2">
                                            {{ $embudo->leads_count }} leads
                                        </span>
                                    @endif --}}
                                </div>

                                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                    <a
                                        href="{{ route('embudos.show', $embudo->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 text-sm font-medium shadow-sm hover:shadow"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        Ver detalles
                                    </a>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-8 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.801 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.801 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">No hay proyectos aún</h3>
                    <p class="text-gray-500 mb-6">Comienza creando tu primer proyecto para organizar tus leads</p>
                    <a href="{{ route('embudos.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-medium shadow-sm hover:shadow">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Crear primer proyecto
                    </a>
                </div>
            @endif
        </div>

        <!-- Sección de Leads -->
        @if($leads->count() > 0)
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Mis Leads</h2>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500">
                            Total: {{ $leads->count() }} leads
                        </span>
                        {{-- <a
                            href="{{ route('leads.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-300 font-medium shadow-sm hover:shadow"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Nuevo Lead
                        </a> --}}
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Contacto
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Proyecto / Etapa
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($leads as $lead)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-r from-blue-100 to-blue-200 rounded-full flex items-center justify-center">
                                                    <span class="text-blue-600 font-semibold">
                                                        {{ strtoupper(substr($lead->nombre, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $lead->nombre }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        ID: {{ $lead->id }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $lead->correo }}</div>
                                            @if($lead->numero_telefono)
                                                <div class="text-sm text-gray-600 mt-1">
                                                    {{ $lead->numero_telefono }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="mb-2">
                                                @if($lead->embudo)
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        {{ $lead->embudo->nombre }}
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        Web externa
                                                    </span>
                                                @endif
                                            </div>
                                            @if($lead->etapa)
                                                <div class="text-xs text-gray-500">
                                                    Etapa: {{ $lead->etapa->nombre }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($lead->fecha_creado)
                                                {{ $lead->fecha_creado->format('d/m/Y') }}
                                                <div class="text-xs text-gray-400">
                                                    {{ $lead->fecha_creado->format('H:i') }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">

                                                <form
                                                    action="{{ route('leads.destroy', $lead->id) }}"
                                                    method="POST"
                                                    class="inline"
                                                    onsubmit="return confirm('¿Estás seguro de eliminar este lead?')"
                                                >
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        type="submit"
                                                        class="text-red-600 hover:text-red-900 px-2 py-1 rounded hover:bg-red-50 transition-colors"
                                                    >
                                                        Eliminar
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        @else
            <!-- Si no hay leads -->
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-8 text-center">
                <svg class="w-20 h-20 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="text-2xl font-semibold text-gray-700 mb-3">No hay leads aún</h3>
                <p class="text-gray-500 mb-6">Comienza agregando leads a tus proyectos para hacer seguimiento</p>
            </div>
        @endif
    </div>

    {{-- Formulario crear lead --}}
    {{-- <div class="mt-8 bg-white p-6 rounded-lg shadow">
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

            </div>

            <div class="mt-4">
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition">
                    Crear Lead
                </button>
            </div>
        </form>
    </div> --}}

    <script>
        // Manejar envío del formulario (actualizado para agregar visualmente)
        document.getElementById('lead-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = {
                nombre: document.getElementById('nombre').value,
                correo: document.getElementById('correo').value,
                numero_telefono: document.getElementById('numero_telefono').value,
                fecha_creado: document.getElementById('fecha_creado').value,
            };

            fetch('/api/leads', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${TOKEN}`
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);

                mostrarNotificacion('Lead creado exitosamente!', 'success');
                this.reset();
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarNotificacion('Error al crear lead', 'error');
            });
        });

    </script>

</x-app-layout>
