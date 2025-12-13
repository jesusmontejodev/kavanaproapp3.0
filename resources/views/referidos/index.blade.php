<x-app-layout>
    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Encabezado -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800">Tu programa de referidos</h2>
            <p class="text-gray-600 mt-2">Comparte tu enlace único para invitar a otros y gana recompensas.</p>
        </div>

        <!-- Tarjeta del enlace de referido -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-md p-6 mb-8 border border-blue-100">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Tu enlace de referido personal</h3>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-grow">
                    <input
                        type="text"
                        value="{{ $linkReferido }}"
                        id="referral-link"
                        class="w-full p-3 pl-4 pr-12 border-2 border-blue-200 rounded-lg bg-white text-gray-800 font-mono text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                        readonly
                    >
                    <button
                        onclick="copyToClipboard()"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-blue-600 hover:text-blue-800 transition-colors"
                        title="Copiar enlace"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                </div>
                <button
                    onclick="copyToClipboard()"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 whitespace-nowrap"
                >
                    Copiar enlace
                </button>
            </div>
            <p class="text-sm text-gray-600 mt-3 flex items-center" id="copy-message">
                <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Comparte este enlace con amigos y familiares
            </p>
        </div>

        <!-- Sección de referidos -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Tus referidos</h3>
                    <div class="flex items-center bg-gray-100 text-gray-800 font-medium py-1 px-3 rounded-full">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 1.5a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ $misReferidos->count() }} referido(s)</span>
                    </div>
                </div>
            </div>

            @if($misReferidos->count() == 0)
                <!-- Estado vacío -->
                <div class="text-center py-12 px-4">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 1.5a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-medium text-gray-700 mb-2">Aún no tienes referidos</h4>
                    <p class="text-gray-500 max-w-md mx-auto">
                        Comparte tu enlace de referido para invitar a otras personas. ¡Cada referido puede brindarte beneficios especiales!
                    </p>
                </div>
            @else
                <!-- Tabla de referidos -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Nombre</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Email</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Fecha</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Estado</th>
                                <th class="py-3 px-6 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($misReferidos as $ref)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center text-blue-800 font-semibold">
                                                {{ substr($ref->referido->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $ref->referido->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-sm text-gray-800">{{ $ref->referido->email }}</td>
                                    <td class="py-4 px-6">
                                        <div class="text-sm text-gray-900">{{ $ref->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $ref->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                            Activo
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if(Auth::user()->hasRole('administrador'))
                                            <a href="{{ route('cliente.show', $ref->referido->id) }}"
                                               class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200"
                                               title="Ver clientes de este usuario">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Ver Clientes
                                            </a>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Solo para administradores</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Removido el bloque de paginación que causaba el error -->
            @endif
        </div>

        <!-- Consejos para compartir -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-lg shadow p-5 border border-gray-100">
                <div class="text-blue-600 mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-800 mb-2">Comparte en redes</h4>
                <p class="text-sm text-gray-600">Publica tu enlace en tus redes sociales para llegar a más personas.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-5 border border-gray-100">
                <div class="text-blue-600 mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-800 mb-2">Envía por correo</h4>
                <p class="text-sm text-gray-600">Comparte tu enlace directamente por correo electrónico con contactos.</p>
            </div>

            <div class="bg-white rounded-lg shadow p-5 border border-gray-100">
                <div class="text-blue-600 mb-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h4 class="font-medium text-gray-800 mb-2">Invita personalmente</h4>
                <p class="text-sm text-gray-600">Comparte tu enlace con amigos y colegas que puedan estar interesados.</p>
            </div>
        </div>
    </div>

    <!-- Script para copiar al portapapeles -->
    <script>
        function copyToClipboard() {
            const linkInput = document.getElementById('referral-link');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999); // Para dispositivos móviles

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    // Mostrar mensaje de confirmación
                    const copyMessage = document.getElementById('copy-message');
                    const originalText = copyMessage.innerHTML;
                    copyMessage.innerHTML = '<svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> ¡Enlace copiado al portapapeles!';
                    copyMessage.classList.remove('text-gray-600');
                    copyMessage.classList.add('text-green-600', 'font-medium');

                    setTimeout(() => {
                        copyMessage.innerHTML = originalText;
                        copyMessage.classList.remove('text-green-600', 'font-medium');
                        copyMessage.classList.add('text-gray-600');
                    }, 3000);
                }
            } catch (err) {
                console.error('Error al copiar: ', err);
                // Fallback usando la API moderna del portapapeles
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(linkInput.value).then(() => {
                        const copyMessage = document.getElementById('copy-message');
                        const originalText = copyMessage.innerHTML;
                        copyMessage.innerHTML = '<svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> ¡Enlace copiado al portapapeles!';
                        copyMessage.classList.remove('text-gray-600');
                        copyMessage.classList.add('text-green-600', 'font-medium');

                        setTimeout(() => {
                            copyMessage.innerHTML = originalText;
                            copyMessage.classList.remove('text-green-600', 'font-medium');
                            copyMessage.classList.add('text-gray-600');
                        }, 3000);
                    });
                }
            }
        }

        // Seleccionar automáticamente el texto al hacer clic en el input
        document.getElementById('referral-link').addEventListener('click', function() {
            this.select();
        });
    </script>
</x-app-layout>
