<x-app-layout>
<div class="py-8">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Encabezado -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">🔑 Restablecer Contraseña</h1>
                        <p class="text-gray-600 mt-1">
                            Cambiar contraseña de {{ $user->name }}
                        </p>
                    </div>
                    <a href="{{ route('adminusuariosmaestro.show', $user->id) }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                        ← Volver
                    </a>
                </div>

                <!-- Información del usuario -->
                <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-blue-700 font-bold text-lg">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="ml-4">
                            <h3 class="font-semibold text-gray-800">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                            <p class="text-xs text-gray-500">
                                Rol: {{ $user->roles->first()->name ?? 'Sin rol' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                    <form action="{{ route('adminusuariosmaestro.cambiarPasswordfunction', $user->id) }}" method="POST" id="passwordForm">
                    @csrf

                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Campo de contraseña -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-medium text-gray-700">Nueva Contraseña *</label>
                            <button type="button" onclick="generarContrasena()"
                                    class="text-sm text-indigo-600 hover:text-indigo-800">
                                🔄 Generar automática
                            </button>
                        </div>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                                placeholder="Mínimo 8 caracteres" required>
                            <button type="button" onclick="togglePassword()"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                👁️ Mostrar
                            </button>
                        </div>
                        <div class="mt-2">
                            <div id="passwordStrength" class="text-sm"></div>
                            @error('password')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Confirmar contraseña -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña *</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                                placeholder="Repite la contraseña" required>
                            <button type="button" onclick="toggleConfirmPassword()"
                                    class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                                👁️ Mostrar
                            </button>
                        </div>
                        @error('password_confirmation')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                        <a href="{{ route('adminusuariosmaestro.show', $user->id) }}"
                            class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium shadow-sm">
                            🔑 Restablecer Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function generarContrasena() {
    fetch('{{ route("adminusuariosmaestro.generarContraseña") }}')
        .then(res => res.json())
        .then(data => {
            document.getElementById('password').value = data.password;
            document.getElementById('password_confirmation').value = data.password;
            verificarFortaleza(data.password);
        });
}

function togglePassword() {
    const input = document.getElementById('password');
    const button = input.nextElementSibling;
    if (input.type === 'password') {
        input.type = 'text';
        button.textContent = '👁️ Ocultar';
    } else {
        input.type = 'password';
        button.textContent = '👁️ Mostrar';
    }
}

function toggleConfirmPassword() {
    const input = document.getElementById('password_confirmation');
    const button = input.nextElementSibling;
    if (input.type === 'password') {
        input.type = 'text';
        button.textContent = '👁️ Ocultar';
    } else {
        input.type = 'password';
        button.textContent = '👁️ Mostrar';
    }
}

function verificarFortaleza(password) {
    const display = document.getElementById('passwordStrength');
    let score = 0;

    if (password.length >= 8) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    const niveles = [
        {text: 'Muy débil', color: 'text-red-600', emoji: '🔴'},
        {text: 'Débil', color: 'text-orange-600', emoji: '🟠'},
        {text: 'Regular', color: 'text-yellow-600', emoji: '🟡'},
        {text: 'Fuerte', color: 'text-green-600', emoji: '🟢'},
        {text: 'Muy fuerte', color: 'text-green-700', emoji: '💪'}
    ];

    const nivel = niveles[score] || niveles[0];
    display.innerHTML = `${nivel.emoji} <span class="${nivel.color} font-medium">${nivel.text}</span>`;
}

document.getElementById('password').addEventListener('input', function(e) {
    verificarFortaleza(e.target.value);
});

// Inicializar
verificarFortaleza(document.getElementById('password').value);
</script>
</x-app-layout>
