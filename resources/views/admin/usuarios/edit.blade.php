<x-app-layout>
<div class="py-8">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- Encabezado -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">✏️ Editar Usuario</h1>
                        <p class="text-gray-600 mt-1">
                            Actualizar información de {{ $user->name }}
                        </p>
                    </div>
                    <a href="{{ route('adminusuariosmaestro.show', $user->id) }}"
                        class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                        ← Volver
                    </a>
                </div>

                <!-- Formulario -->
                <form action="{{ route('adminusuariosmaestro.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Información básica -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800">📋 Información Básica</h3>

                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nombre completo *</label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $user->name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                                    required>
                                @error('name')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', $user->email) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                                    required>
                                @error('email')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                                <input type="tel" name="phone" id="phone"
                                    value="{{ old('phone', $user->phone) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                                    placeholder="+52 123 456 7890">
                                @error('phone')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-800">📍 Información Adicional</h3>

                            <div>
                                <label for="empresa" class="block text-sm font-medium text-gray-700 mb-2">Empresa</label>
                                <input type="text" name="empresa" id="empresa"
                                    value="{{ old('empresa', $user->empresa) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white"
                                    placeholder="Nombre de la empresa">
                                @error('empresa')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="ciudad" class="block text-sm font-medium text-gray-700 mb-2">Ciudad</label>
                                    <input type="text" name="ciudad" id="ciudad"
                                        value="{{ old('ciudad', $user->ciudad) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                    @error('ciudad')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                                    <input type="text" name="estado" id="estado"
                                        value="{{ old('estado', $user->estado) }}"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                    @error('estado')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Rol actual -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rol actual</label>
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-sm mr-2 mb-2 inline-block">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-500">Sin rol asignado</span>
                                    @endif
                                    <p class="text-xs text-gray-500 mt-2">
                                        Para cambiar el rol, usa la opción en la vista de detalles del usuario.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-200">
                        <a href="{{ route('adminusuariosmaestro.show', $user->id) }}"
                            class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                            Cancelar
                        </a>
                        <button type="submit"
                                class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium shadow-sm">
                            💾 Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
