<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <h1 class="text-2xl font-bold text-gray-800 mb-6">Mis Páginas</h1>

                @if($proyectos->isEmpty())
                    <div class="text-center py-8">
                        <p class="text-gray-500">No tienes páginas/proyectos creados.</p>
                        <a href="{{ route('proyectos.create') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                            Crear nuevo proyecto
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($proyectos as $proyecto)
                            @php
                                // RUTA CORREGIDA: /open/{id_proyecto}/usuario/{id_usuario}
                                $projectUrl = url('/open/' . $proyecto->id . '/usuario/' . $id_usuario);
                            @endphp

                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <h3 class="font-semibold text-lg text-gray-800 mb-2">
                                    {{ $proyecto->nombre ?? 'Proyecto #' . $proyecto->id }}
                                </h3>

                                @if(isset($proyecto->descripcion))
                                    <p class="text-gray-600 text-sm mb-4">
                                        {{ Str::limit($proyecto->descripcion, 100) }}
                                    </p>
                                @endif

                                <!-- URL del proyecto (para copiar) -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Enlace del proyecto:</label>
                                    <div class="flex items-center">
                                        <input type="text"
                                               id="url-{{ $proyecto->id }}"
                                               value="{{ $projectUrl }}"
                                               readonly
                                               class="flex-grow px-3 py-2 border border-gray-300 rounded-l text-sm bg-gray-50">
                                        <button onclick="copyToClipboard('url-{{ $proyecto->id }}')"
                                                class="bg-gray-200 hover:bg-gray-300 px-4 py-2 border border-gray-300 border-l-0 rounded-r transition-colors"
                                                title="Copiar enlace">
                                            📋
                                        </button>
                                    </div>
                                </div>

                                <!-- Botones de acción -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <!-- Botón Abrir en nueva pestaña -->
                                    <a href="{{ $projectUrl }}"
                                       target="_blank"
                                       class="inline-flex items-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                        Abrir
                                    </a>

                                    <!-- Botón Copiar URL -->
                                    <button onclick="copyToClipboard('url-{{ $proyecto->id }}')"
                                            class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                        </svg>
                                        Copiar URL
                                    </button>

                                    <!-- Botón para mostrar QR -->
                                    {{-- <button onclick="generateAndShowQR('{{ $projectUrl }}', '{{ $proyecto->nombre ?? 'Proyecto #' . $proyecto->id }}')"
                                            class="inline-flex items-center gap-2 bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded text-sm transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                        </svg>
                                        Ver QR
                                    </button> --}}
                                </div>

                                <!-- Información adicional -->
                                <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                                    <span class="text-xs text-gray-500">
                                        Creado: {{ $proyecto->created_at->format('d/m/Y') }}
                                    </span>

                                    <span class="text-xs text-gray-500">
                                        ID: {{ $proyecto->id }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>

    <!-- Modal para QR Code -->
    <div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-800" id="qrTitle">Código QR</h3>
                <button onclick="closeQRModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <div id="qrContainer" class="flex justify-center mb-4">
                    <!-- QR se generará aquí -->
                    <canvas id="qrCanvas" class="hidden"></canvas>
                    <div id="qrImageContainer"></div>
                </div>
                <div class="text-center mb-4">
                    <input type="text"
                           id="qrUrlInput"
                           readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-50 text-center">
                </div>
                <p class="text-sm text-gray-600 text-center mb-4">Escanea este código para acceder al proyecto</p>
                <div class="flex gap-2 justify-center">
                    <button onclick="downloadQR()"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm transition-colors">
                        Descargar QR
                    </button>
                    <button onclick="copyQRUrl()"
                            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded text-sm transition-colors">
                        Copiar URL
                    </button>
                    <button onclick="closeQRModal()"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded text-sm transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Variables globales para el QR
        let currentQRUrl = '';
        let currentProjectName = '';

        // Función para generar y mostrar QR con JavaScript
        function generateAndShowQR(url, projectName) {
            currentQRUrl = url;
            currentProjectName = projectName;

            // Actualizar título
            document.getElementById('qrTitle').textContent = 'QR: ' + projectName;

            // Actualizar input con la URL
            document.getElementById('qrUrlInput').value = url;

            // Limpiar contenedor anterior
            const qrContainer = document.getElementById('qrImageContainer');
            qrContainer.innerHTML = '';

            // Generar QR con qrcode.js
            QRCode.toCanvas(document.getElementById('qrCanvas'), url, {
                width: 200,
                margin: 1,
                color: {
                    dark: '#000000',
                    light: '#FFFFFF'
                }
            }, function(error) {
                if (error) {
                    console.error('Error generando QR:', error);
                    qrContainer.innerHTML = '<p class="text-red-500">Error generando QR</p>';
                    return;
                }

                // Obtener el canvas y convertirlo a imagen
                const canvas = document.getElementById('qrCanvas');
                const dataUrl = canvas.toDataURL('image/png');

                // Crear elemento imagen
                const img = document.createElement('img');
                img.src = dataUrl;
                img.alt = 'Código QR para ' + projectName;
                img.className = 'w-48 h-48';
                img.id = 'qrGeneratedImage';

                // Agregar al contenedor
                qrContainer.appendChild(img);

                // Mostrar el modal
                document.getElementById('qrModal').classList.remove('hidden');
            });
        }

        // Función para descargar el QR
        function downloadQR() {
            const qrImage = document.getElementById('qrGeneratedImage');
            if (!qrImage) return;

            const link = document.createElement('a');
            link.href = qrImage.src;
            link.download = `qr-${currentProjectName.replace(/\s+/g, '-').toLowerCase()}.png`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            showNotification('✅ QR descargado');
        }

        // Función para copiar la URL del QR
        function copyQRUrl() {
            const qrUrlInput = document.getElementById('qrUrlInput');
            qrUrlInput.select();
            qrUrlInput.setSelectionRange(0, 99999);

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showNotification('✅ URL copiada al portapapeles');
                } else {
                    showNotification('❌ Error al copiar URL');
                }
            } catch (err) {
                navigator.clipboard.writeText(qrUrlInput.value)
                    .then(() => showNotification('✅ URL copiada al portapapeles'))
                    .catch(() => showNotification('❌ Error al copiar URL'));
            }
        }

        // Función para copiar al portapapeles (para los inputs de URL)
        function copyToClipboard(elementId) {
            const element = document.getElementById(elementId);
            element.select();
            element.setSelectionRange(0, 99999);

            try {
                const successful = document.execCommand('copy');
                if (successful) {
                    showNotification('✅ Enlace copiado al portapapeles');
                } else {
                    showNotification('❌ Error al copiar');
                }
            } catch (err) {
                navigator.clipboard.writeText(element.value)
                    .then(() => showNotification('✅ Enlace copiado al portapapeles'))
                    .catch(() => showNotification('❌ Error al copiar'));
            }
        }

        // Función para cerrar el modal
        function closeQRModal() {
            document.getElementById('qrModal').classList.add('hidden');
            // Limpiar el canvas
            const canvas = document.getElementById('qrCanvas');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
        }

        // Función para mostrar notificaciones
        function showNotification(message) {
            // Crear o reutilizar notificación
            let notification = document.getElementById('copyNotification');

            if (!notification) {
                notification = document.createElement('div');
                notification.id = 'copyNotification';
                notification.className = 'fixed bottom-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-y-0 opacity-100';
                document.body.appendChild(notification);
            }

            notification.textContent = message;
            notification.classList.remove('hidden', 'opacity-0', 'translate-y-2');
            notification.classList.add('opacity-100', 'translate-y-0');

            // Ocultar después de 3 segundos
            setTimeout(() => {
                notification.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => {
                    notification.classList.add('hidden');
                }, 300);
            }, 3000);
        }

        // Cerrar modal con ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeQRModal();
            }
        });

        // Cerrar modal haciendo click fuera
        document.getElementById('qrModal').addEventListener('click', (e) => {
            if (e.target.id === 'qrModal') {
                closeQRModal();
            }
        });
    </script>

    <style>
        /* Estilos adicionales */
        #copyNotification {
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        #qrModal {
            backdrop-filter: blur(4px);
        }

        input:read-only {
            cursor: default;
        }

        input:read-only:focus {
            outline: none;
            border-color: #d1d5db;
            box-shadow: 0 0 0 3px rgba(209, 213, 219, 0.1);
        }

        #qrGeneratedImage {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.5rem;
            background: white;
        }
    </style>
</x-app-layout>
