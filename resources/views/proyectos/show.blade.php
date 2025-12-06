 <x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $proyecto->nombre }}
                </h2>
            </div>
            <a href="{{ route('home') }}"
                class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver
            </a>
        </div>
    </x-slot>

    <!-- Incluir Swiper JS CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Información del proyecto -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $proyecto->nombre }}</h3>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($proyecto->descripcion)) !!}
                        </div>
                    </div>

                    <!-- Detalles adicionales si existen -->
                    @if($proyecto->fecha_inicio || $proyecto->estado || $proyecto->cliente)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-6 border-t border-gray-200">
                        @if($proyecto->cliente)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">Cliente: <span class="font-medium">{{ $proyecto->cliente }}</span></span>
                        </div>
                        @endif

                        @if($proyecto->fecha_inicio)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($proyecto->fecha_inicio)->format('F Y') }}</span>
                        </div>
                        @endif

                        @if($proyecto->estado)
                        <div class="flex items-center">
                            @php
                                $estadoColors = [
                                    'Completado' => 'bg-green-100 text-green-800',
                                    'En progreso' => 'bg-blue-100 text-blue-800',
                                    'Planificación' => 'bg-yellow-100 text-yellow-800',
                                    'Pausado' => 'bg-gray-100 text-gray-800',
                                ];
                                $colorClass = $estadoColors[$proyecto->estado] ?? 'bg-gray-100 text-gray-800';
                            @endphp
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $colorClass }}">
                                {{ $proyecto->estado }}
                            </span>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Galería con Swiper -->
            @if($mediasProyecto->count())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">Galería de Imágenes</h3>
                        <span class="text-sm text-gray-500">{{ $mediasProyecto->count() }} imágenes</span>
                    </div>

                    <!-- Swiper Container con altura fija -->
                    <div class="swiper-container relative" style="height: 500px;">
                        <div class="swiper-wrapper">
                            @foreach($mediasProyecto as $media)
                            <div class="swiper-slide">
                                <div class="relative w-full h-full overflow-hidden rounded-lg bg-gray-100">
                                    <img src="{{ asset($media->url_imagen) }}"
                                         alt="{{ $media->descripcion ?? 'Imagen del proyecto' }}"
                                         class="w-full h-full object-cover">

                                    @if($media->descripcion)
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                                        <p class="text-white text-sm">{{ $media->descripcion }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Navigation buttons -->
                        <div class="swiper-button-next !text-white !bg-black/30 !w-12 !h-12 rounded-full hover:!bg-black/50 transition-all"></div>
                        <div class="swiper-button-prev !text-white !bg-black/30 !w-12 !h-12 rounded-full hover:!bg-black/50 transition-all"></div>

                        <!-- Pagination -->
                        <div class="swiper-pagination !relative !mt-6"></div>
                    </div>

                    <!-- Miniaturas (opcional) -->
                    <div class="mt-8">
                        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                            @foreach($mediasProyecto as $index => $media)
                            <button class="thumbnail-btn focus:outline-none" data-index="{{ $index }}">
                                <img src="{{ asset($media->url_imagen) }}"
                                    alt="Miniatura {{ $index + 1 }}"
                                    class="w-full h-20 object-cover rounded border-2 border-transparent hover:border-blue-500 transition-all cursor-pointer">
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Enlaces del proyecto -->
            @if(isset($linksProyecto) && $linksProyecto->count())
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6">Enlaces Relacionados</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($linksProyecto as $link)
                        <a href="{{ $link->url_archivo }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group flex items-start p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:shadow-md transition-all duration-200">
                            <div class="flex-shrink-0 mr-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 group-hover:text-blue-600">
                                    {{ $link->descripcion ?: 'Enlace del proyecto' }}
                                </h4>
                                <p class="text-sm text-gray-500 mt-1 truncate">{{ $link->url_archivo }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Mensaje si no hay contenido -->
            @if(!$mediasProyecto->count() && (!isset($linksProyecto) || !$linksProyecto->count()))
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Proyecto en desarrollo</h3>
                <p class="text-gray-600">Próximamente se añadirá más contenido.</p>
            </div>
            @endif

        </div>
    </div>

    <!-- Incluir Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar Swiper
            const swiper = new Swiper('.swiper-container', {
                // Optional parameters
                direction: 'horizontal',
                loop: true,
                slidesPerView: 1,
                spaceBetween: 0,
                speed: 500,

                // Autoplay
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },

                // Pagination
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    dynamicBullets: true,
                },

                // Navigation arrows
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },

                // Ajustar altura automáticamente
                autoHeight: false,

                // Efecto de transición
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },

                // Breakpoints (mantener 1 slide por vista)
                breakpoints: {
                    640: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                    768: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                    1024: {
                        slidesPerView: 1,
                        spaceBetween: 0,
                    },
                }
            });

            // Navegación por miniaturas
            document.querySelectorAll('.thumbnail-btn').forEach((btn, index) => {
                btn.addEventListener('click', () => {
                    swiper.slideTo(index + 1); // +1 porque Swiper está en loop
                });
            });

            // Resaltar miniatura activa
            swiper.on('slideChange', function () {
                document.querySelectorAll('.thumbnail-btn img').forEach((img, index) => {
                    // swiper.realIndex es el índice real cuando hay loop
                    if (index === swiper.realIndex) {
                        img.classList.add('border-blue-500', 'border-2');
                        img.classList.remove('border-transparent');
                    } else {
                        img.classList.remove('border-blue-500', 'border-2');
                        img.classList.add('border-transparent');
                    }
                });
            });

            // Inicializar primera miniatura como activa
            if (document.querySelector('.thumbnail-btn')) {
                document.querySelector('.thumbnail-btn img').classList.add('border-blue-500', 'border-2');
            }

            // Pausar autoplay al pasar el ratón
            const swiperContainer = document.querySelector('.swiper-container');
            swiperContainer.addEventListener('mouseenter', () => {
                swiper.autoplay.stop();
            });

            swiperContainer.addEventListener('mouseleave', () => {
                swiper.autoplay.start();
            });
        });
    </script>

    <style>
        .swiper-container {
            width: 100%;
            height: 500px;
        }

        .swiper-slide {
            text-align: center;
            font-size: 18px;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .swiper-slide > div {
            width: 100%;
            height: 100%;
        }

        .swiper-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 24px;
            font-weight: bold;
        }

        .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: #d1d5db;
            opacity: 1;
        }

        .swiper-pagination-bullet-active {
            background: #3b82f6;
        }

        .prose {
            line-height: 1.75;
        }

        .prose p {
            margin-bottom: 1rem;
        }

        .prose p:last-child {
            margin-bottom: 0;
        }

        /* Efecto fade para las transiciones */
        .swiper-fade .swiper-slide {
            pointer-events: none;
            transition-property: opacity;
        }

        .swiper-fade .swiper-slide-active {
            pointer-events: auto;
        }
    </style>
</x-app-layout>
