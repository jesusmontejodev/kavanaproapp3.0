<section class="mt-10">

    <header class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-2">
            <span class="inline-block w-2 h-6 bg-indigo-500 rounded"></span>
            {{ __('Update Profile Photo') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Upload a new profile picture for your account.') }}
        </p>
    </header>

    <form method="POST"
        action="{{ route('profile.photo.update') }}"
        enctype="multipart/form-data"
        class="p-6 rounded-xl bg-white/70 backdrop-blur shadow-lg border border-gray-100 space-y-6 transition-all duration-300 hover:shadow-xl">

        @csrf
        @method('PUT')

        <!-- Campo de archivo -->
        <div x-data="profilePhotoHandler()">
            <x-input-label for="foto_perfil" :value="__('Profile Photo')" class="font-semibold text-gray-800" />

            <label class="mt-2 flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-indigo-500 transition relative overflow-hidden">

                <!-- Vista previa -->
                <template x-if="previewUrl">
                    <img :src="previewUrl" class="absolute inset-0 w-full h-full object-cover opacity-60">
                </template>

                <!-- Ícono mientras no hay vista previa -->
                <div class="flex flex-col items-center justify-center pt-3 pb-4" x-show="!previewUrl">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 16.5V21h4.5M3 16.5l2.25-2.25M3 16.5h4.5m13.5-9V3h-4.5m4.5 4.5l-2.25 2.25M21 7.5h-4.5M16.5 21h4.5v-4.5m-4.5 4.5l2.25-2.25M16.5 21v-4.5M7.5 3H3v4.5M7.5 3L5.25 5.25M7.5 3v4.5" />
                    </svg>
                    <p class="text-sm text-gray-500">Click para seleccionar imagen</p>
                </div>

                <!-- Input -->
                <input id="foto_perfil"
                    name="foto_perfil"
                    type="file"
                    accept="image/*"
                    @change="handleFileChange"
                    class="hidden" />
            </label>

            <p class="text-sm text-red-600 mt-2" x-text="errorMessage"></p>

            <x-input-error :messages="$errors->get('foto_perfil')" class="mt-2" />
        </div>

        <!-- Foto actual -->
        @if (auth()->user()->foto_perfil)
            <div class="mt-4">
                <p class="text-sm text-gray-600 mb-1 font-semibold">{{ __('Current photo:') }}</p>
                <img src="{{ asset(auth()->user()->foto_perfil) }}"
                    alt="Profile Photo"
                    class="w-28 h-28 rounded-full object-cover border-4 border-indigo-200 shadow-md transition-all duration-300 hover:scale-105">
            </div>
        @endif

        <div class="flex items-center gap-4">
            <button
                class="px-5 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'foto-actualizada')
                <p x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600 font-medium">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>

<!-- Alpine.js Script para vista previa + límite 2MB -->
<script>
function profilePhotoHandler() {
    return {
        previewUrl: null,
        errorMessage: '',

        handleFileChange(event) {
            const file = event.target.files[0];

            if (!file) return;

            // Validar tamaño
            if (file.size > 2 * 1024 * 1024) { // 2 MB
                this.errorMessage = '⚠️ La imagen supera el límite de 2 MB';
                this.previewUrl = null;
                event.target.value = ""; // reset input
                return;
            }

            this.errorMessage = '';

            // Crear preview
            this.previewUrl = URL.createObjectURL(file);
        }
    }
}
</script>
