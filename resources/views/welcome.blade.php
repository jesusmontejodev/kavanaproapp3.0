<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Portafolio') }} - Mis Proyectos</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */@layer theme{/* ... (todo el CSS original se mantiene igual) ... */}
                /* Estilos adicionales para las cards */
                .project-card {
                    transition: all 0.3s ease;
                }
                .project-card:hover {
                    transform: translateY(-5px);
                }
                .project-image {
                    transition: transform 0.5s ease;
                }
                .project-card:hover .project-image {
                    transform: scale(1.05);
                }
            </style>
        @endif
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen">
        <!-- Header -->
        <header class="w-full p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                <nav class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-xl font-semibold dark:text-[#EDEDEC]">{{ config('app.name', 'Portafolio') }}</span>
                    </div>

                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/home') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Home
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Iniciar Sesión
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-block px-5 py-1.5 bg-[#1b1b18] dark:bg-[#eeeeec] dark:text-[#1C1C1A] text-white rounded-sm hover:bg-black dark:hover:bg-white text-sm leading-normal">
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    </div>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-6 lg:px-8 mb-12">
            <div class="text-center">
                <h1 class="text-3xl lg:text-4xl font-bold mb-4 dark:text-[#EDEDEC]">
                    <span class="text-[#f53003] dark:text-[#FF4433]">Proyectos</span>
                </h1>
                <p class="text-lg text-[#706f6c] dark:text-[#A1A09A] max-w-2xl mx-auto">
                    Una colección de mis trabajos más recientes y destacados
                </p>
            </div>
        </div>

        <!-- Proyectos Grid -->
        <main class="max-w-7xl mx-auto px-6 lg:px-8 pb-20">
            @if(isset($proyectos) && $proyectos->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($proyectos as $proyecto)
                        <div class="project-card bg-white dark:bg-[#161615] rounded-lg overflow-hidden border border-[#e3e3e0] dark:border-[#3E3E3A] hover:shadow-xl">
                            <!-- Imagen del proyecto -->
                            @if($proyecto->url_imagen)
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ $proyecto->url_imagen }}"
                                        alt="{{ $proyecto->nombre }}"
                                        class="w-full h-full object-cover project-image">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                                </div>
                            @else
                                <div class="h-48 bg-gradient-to-br from-[#f53003]/10 to-[#FF4433]/10 dark:from-[#f53003]/20 dark:to-[#FF4433]/20 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-[#f53003] dark:text-[#FF4433]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            @endif

                            <!-- Contenido de la card -->
                            <div class="p-5">
                                <!-- Título y enlace principal -->
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-lg font-semibold dark:text-[#EDEDEC] truncate">
                                        {{ $proyecto->nombre }}
                                    </h3>
                                    @if($proyecto->link_proyecto)
                                        <a href="{{ $proyecto->link_proyecto }}"
                                            target="_blank"
                                            class="ml-2 flex-shrink-0 text-[#f53003] dark:text-[#FF4433] hover:text-[#d42a03] dark:hover:text-[#ff3300]"
                                            title="Visitar proyecto">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                            </svg>
                                        </a>
                                    @endif
                                </div>

                                <!-- Descripción -->
                                @if($proyecto->descripcion)
                                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A] mb-4 line-clamp-3">
                                        {{ $proyecto->descripcion }}
                                    </p>
                                @endif

                                <!-- Media del proyecto (galería) -->
                                @if($proyecto->mediaProyectos->count() > 0)
                                    <div class="mb-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                                {{ $proyecto->mediaProyectos->count() }} archivos multimedia
                                            </span>
                                        </div>
                                        <div class="flex gap-2">
                                            @foreach($proyecto->mediaProyectos->take(3) as $media)
                                                <div class="w-8 h-8 rounded bg-[#e3e3e0] dark:bg-[#3E3E3A] flex items-center justify-center">
                                                    @if(str_contains($media->tipo, 'image'))
                                                        <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                                                            <path fill-rule="evenodd" d="M14 6h-4v4h4V6z" clip-rule="evenodd"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                            @endforeach
                                            @if($proyecto->mediaProyectos->count() > 3)
                                                <div class="w-8 h-8 rounded bg-[#e3e3e0] dark:bg-[#3E3E3A] flex items-center justify-center">
                                                    <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">+{{ $proyecto->mediaProyectos->count() - 3 }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Enlaces adicionales -->
                                @if($proyecto->linkProyectos->count() > 0)
                                    <div class="mb-4">
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-4 h-4 text-[#706f6c] dark:text-[#A1A09A]" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                                <path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"/>
                                            </svg>
                                            <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">Enlaces relacionados</span>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($proyecto->linkProyectos->take(3) as $link)
                                                <a href="{{ $link->url }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1 px-2 py-1 text-xs border border-[#e3e3e0] dark:border-[#3E3E3A] rounded hover:border-[#f53003] dark:hover:border-[#FF4433] transition-colors">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"/>
                                                    </svg>
                                                    <span class="truncate max-w-[80px]">{{ $link->nombre ?? 'Enlace' }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Acciones -->
                                <div class="flex items-center justify-between pt-4 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
                                    @auth
                                        <a href="{{ route('proyectos.show', $proyecto->id) }}"
                                            lass="text-sm text-[#f53003] dark:text-[#FF4433] hover:underline inline-flex items-center gap-1">
                                            Ver detalles
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </a>
                                    @else
                                        <span class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                            Inicia sesión para ver más detalles
                                        </span>
                                    @endauth

                                    <div class="text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                        {{ $proyecto->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Estado vacío -->
                <div class="text-center py-20">
                    <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-[#f53003]/10 to-[#FF4433]/10 dark:from-[#f53003]/20 dark:to-[#FF4433]/20 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-[#f53003] dark:text-[#FF4433]" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm7-1a1 1 0 11-2 0 1 1 0 012 0zm-.464 5.535a1 1 0 10-1.415-1.414 3 3 0 01-4.242 0 1 1 0 00-1.415 1.414 5 5 0 007.072 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2 dark:text-[#EDEDEC]">No hay proyectos disponibles</h3>
                    <p class="text-[#706f6c] dark:text-[#A1A09A] mb-6 max-w-md mx-auto">
                        Aún no se han agregado proyectos. Cuando lo hagas, aparecerán aquí en tarjetas organizadas.
                    </p>
                    @auth
                        <a href="{{ route('proyectos.create') }}"
                            class="inline-block px-6 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] dark:text-[#1C1C1A] text-white rounded-sm hover:bg-black dark:hover:bg-white transition-all">
                            Crear primer proyecto
                        </a>
                    @endauth
                </div>
            @endif
        </main>

        <!-- Footer -->
        @if (Route::has('login'))
            <div class="mt-12 px-6 lg:px-8">
                <div class="max-w-7xl mx-auto border-t border-[#e3e3e0] dark:border-[#3E3E3A] pt-8 text-center">
                    <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        © {{ date('Y') }} {{ config('app.name', 'Portafolio') }}. Todos los derechos reservados.
                    </p>
                </div>
            </div>
        @endif
    </body>
</html>
