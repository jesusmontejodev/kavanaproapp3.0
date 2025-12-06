<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- META TAGS MEJORADOS --}}
    <meta name="description" content="{{ $proyecto->descripcion }}">
    <meta property="og:title" content="{{ $proyecto->nombre }}">
    <meta property="og:description" content="{{ $proyecto->descripcion }}">
    <meta property="og:image" content="{{ asset($proyecto->url_imagen) }}">
    <meta name="robots" content="index, follow">

    {{-- ESTILOS EXTERNOS --}}
    <link rel="stylesheet" href="{{ asset('styles/leaflet.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('styles/inmuebleTienda.css') }}">

    {{-- FAVICON --}}
    <link rel="icon" href="{{ asset($proyecto->url_imagen) }}" type="image/png">

    {{-- TÍTULO --}}
    <title>{{ $proyecto->nombre }} | Kavana</title>

    {{-- ESTILOS INLINE CON MODO OSCURO POR DEFECTO --}}
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }
        :root {
            /* Colores modo oscuro (por defecto) */
            --color-primary: #8a6aff;
            --color-primary-dark: #7a5aff;
            --color-secondary: #a886ff;
            --color-text: #f0f0f0;
            --color-text-light: #b0b0b0;
            --color-bg: #0a0a0a;
            --color-surface: #1a1a1a;
            --color-border: #333333;
            --color-white: #1a1a1a;

            /* Colores modo claro (alternativa) */
            --color-primary-light: #6e3aff;
            --color-primary-dark-light: #5a2fe0;
            --color-secondary-light: #9f68ff;
            --color-text-light-mode: #222222;
            --color-text-light-light-mode: #666666;
            --color-bg-light: #f5f5f5;
            --color-surface-light: #ffffff;
            --color-border-light: #e0e0e0;

            /* Bordes */
            --border-radius: 14px;
            --border-width: 2px;
            --border-color: #333333;

            /* Espaciados */
            --spacing-xs: 5px;
            --spacing-sm: 15px;
            --spacing-md: 25px;
            --spacing-lg: 40px;

            /* Sombras */
            --box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.4);
            --card-border: var(--border-width) solid var(--border-color);

            /* Transiciones */
            --transition-speed: 0.4s;
        }

        /* Modo claro (alternativo) */
        [data-theme="light"] {
            --color-primary: var(--color-primary-light);
            --color-primary-dark: var(--color-primary-dark-light);
            --color-secondary: var(--color-secondary-light);
            --color-text: var(--color-text-light-mode);
            --color-text-light: var(--color-text-light-light-mode);
            --color-bg: var(--color-bg-light);
            --color-surface: var(--color-surface-light);
            --color-border: var(--color-border-light);
            --color-white: var(--color-surface-light);
            --box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.08);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: background-color var(--transition-speed) ease,
                        color var(--transition-speed) ease,
                        border-color var(--transition-speed) ease,
                        box-shadow var(--transition-speed) ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--color-bg);
            color: var(--color-text);
            line-height: 1.6;
            min-height: 100vh;
        }

        /* Toggle de modo oscuro/claro */
        .theme-toggle {
            position: fixed;
            bottom: 25px;
            right: 25px;
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: var(--color-primary);
            color: white;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(138, 106, 255, 0.3);
            z-index: 1000;
            transition: all 0.3s ease;
            border: 2px solid var(--color-border);
        }

        .theme-toggle:hover {
            transform: scale(1.1) rotate(15deg);
            background: var(--color-primary-dark);
            box-shadow: 0 6px 20px rgba(138, 106, 255, 0.4);
        }

        /* HEADER - Estilo modo oscuro */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 30px;
            background: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            position: sticky;
            top: 0;
            z-index: 1000;
            border: var(--card-border);
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            margin: 0 20px;
            margin-top: 20px;
            backdrop-filter: blur(10px);
            background: rgba(26, 26, 26, 0.9);
        }

        [data-theme="light"] .header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .header__logo {
            width: 140px;
            height: auto;
            filter: brightness(1.2);
        }

        [data-theme="light"] .header__logo {
            filter: brightness(0.9);
        }

        .nav__toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--color-text);
            padding: var(--spacing-xs);
            transition: transform 0.3s ease;
        }

        .nav__toggle:hover {
            transform: scale(1.1);
        }

        .nav__list {
            display: flex;
            gap: 30px;
            list-style: none;
        }

        .nav__link {
            text-decoration: none;
            color: var(--color-text);
            font-weight: 500;
            transition: all 0.2s ease;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid transparent;
        }

        .nav__link:hover,
        .nav__link:focus {
            color: var(--color-primary);
            background: rgba(138, 106, 255, 0.1);
            border-color: var(--color-primary);
            transform: translateY(-2px);
        }

        /* HERO - Estilo modo oscuro */
        .hero {
            text-align: center;
            padding: var(--spacing-lg) 20px;
            background: linear-gradient(145deg,
                var(--color-primary) 0%,
                var(--color-secondary) 50%,
                #6e3aff 100%);
            color: white;
            margin: 20px;
            border-radius: var(--border-radius);
            border: var(--card-border);
            box-shadow: var(--box-shadow);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center,
                rgba(255, 255, 255, 0.1) 0%,
                transparent 70%);
            pointer-events: none;
        }

        .hero__image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid white;
            margin-bottom: var(--spacing-sm);
            box-shadow: 0 0 0 4px var(--color-primary),
                        0 0 20px rgba(138, 106, 255, 0.5);
            position: relative;
            z-index: 1;
        }

        .hero__title {
            font-size: 2.5rem;
            margin: 0;
            position: relative;
            z-index: 1;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        /* CONTENEDOR PRINCIPAL */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 30px auto;
            border: var(--card-border);
            border-radius: var(--border-radius);
            overflow: hidden;
            background: var(--color-surface);
            padding: 25px;
            box-shadow: var(--box-shadow);
        }

        /* SWIPER - Estilo modo oscuro */
        .swiper-slide__image {
            width: 100%;
            height: 380px;
            object-fit: cover;
            border-radius: var(--border-radius);
            border: var(--card-border);
            transition: transform 0.5s ease;
        }

        .swiper-slide-active .swiper-slide__image {
            transform: scale(1.02);
        }

        .swiper {
            border-radius: var(--border-radius);
            overflow: hidden;
            position: relative;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: var(--color-text);
            background: rgba(26, 26, 26, 0.8);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            border: var(--card-border);
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 24px;
            font-weight: bold;
        }

        .swiper-pagination-bullet {
            background: var(--color-text-light);
            opacity: 0.5;
            width: 12px;
            height: 12px;
        }

        .swiper-pagination-bullet-active {
            background: var(--color-primary);
            opacity: 1;
            transform: scale(1.2);
        }

        /* LAYOUT PRINCIPAL - AHORA SOLO CONTENIDO */
        .main-content {
            width: 90%;
            max-width: 900px;
            margin: var(--spacing-lg) auto;
        }

        /* CONTENIDO DEL PROYECTO - AHORA OCUPA TODO EL ANCHO */
        .project-content {
            width: 100%;
        }

        .card {
            background: var(--color-surface);
            padding: var(--spacing-md);
            border-radius: var(--border-radius);
            margin-bottom: var(--spacing-md);
            box-shadow: var(--box-shadow);
            border: var(--card-border);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(to bottom, var(--color-primary), var(--color-secondary));
            opacity: 0.7;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
            border-color: var(--color-primary);
        }

        [data-theme="light"] .card:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }

        .card__header {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            margin-bottom: var(--spacing-sm);
            padding-bottom: var(--spacing-sm);
            border-bottom: 1px solid var(--color-border);
        }

        .card__avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--color-primary);
            box-shadow: 0 0 10px rgba(138, 106, 255, 0.3);
        }

        .card__title {
            font-size: 1.3rem;
            margin-bottom: var(--spacing-xs);
            color: var(--color-text);
            font-weight: 600;
        }

        .card__description {
            color: var(--color-text-light);
            margin-bottom: var(--spacing-sm);
            line-height: 1.6;
            font-size: 0.95rem;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .nav__toggle {
                display: block;
            }

            .nav__list {
                display: none;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: var(--color-surface);
                padding: var(--spacing-md);
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                border: var(--card-border);
                border-radius: 0 0 var(--border-radius) var(--border-radius);
                z-index: 1000;
                backdrop-filter: blur(10px);
                background: rgba(26, 26, 26, 0.95);
            }

            [data-theme="light"] .nav__list {
                background: rgba(255, 255, 255, 0.95);
            }

            .nav__list--open {
                display: flex;
            }

            .hero__title {
                font-size: 2rem;
            }

            .header {
                margin: 10px;
                margin-top: 10px;
            }

            .hero {
                margin: 10px;
            }

            .swiper-slide__image {
                height: 300px;
            }

            .main-content {
                width: 95%;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: 12px 15px;
            }

            .hero {
                padding: var(--spacing-md) 15px;
            }

            .hero__title {
                font-size: 1.8rem;
            }

            .container,
            .main-content {
                width: 95%;
            }

            .theme-toggle {
                bottom: 15px;
                right: 15px;
                width: 50px;
                height: 50px;
            }
        }

        /* ACCESIBILIDAD */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
        }

        .focus-visible {
            outline: 3px solid var(--color-primary);
            outline-offset: 3px;
        }

        /* Animación de entrada */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card,
        .container {
            animation: fadeIn 0.6s ease-out;
        }

        .card:nth-child(2) { animation-delay: 0.1s; }
        .card:nth-child(3) { animation-delay: 0.2s; }
        .card:nth-child(4) { animation-delay: 0.3s; }
    </style>
</head>

<body data-theme="dark"> <!-- MODO OSCURO POR DEFECTO -->
    {{-- BOTÓN TOGGLE MODO OSCURO/CLARO --}}
    <button class="theme-toggle" aria-label="Cambiar modo claro/oscuro" title="Cambiar tema">
        ☀️ <!-- Empieza con sol porque está en modo oscuro -->
    </button>

    {{-- HEADER MEJORADO --}}
    <header class="header" role="banner">
        <img class="header__logo" src="{{ asset('icons/logo-kavana.svg') }}" alt="Logo Kavana" width="140" height="auto">

        <button class="nav__toggle" aria-label="Menú de navegación" aria-expanded="false">
            ☰
        </button>

        <nav class="nav" role="navigation">
            <ul class="nav__list">
                {{-- <li><a class="nav__link" href="/">ACADEMIA</a></li>
                <li><a class="nav__link" href="#">BUSCADOR</a></li> --}}
                <li><a class="nav__link" href="/register">REGÍSTRATE</a></li>
                <li><a class="nav__link" href="/login">INICIAR SESIÓN</a></li>
            </ul>
        </nav>
    </header>

    {{-- HERO SECTION --}}
    <section class="hero" role="region" aria-label="Información del proyecto">
        <img class="hero__image" src="{{ asset($proyecto->url_imagen) }}" alt="{{ $proyecto->nombre }}" width="100" height="100">
        <h1 class="hero__title">{{ $proyecto->nombre }}</h1>
    </section>

    {{-- GALERÍA DE IMÁGENES --}}
    <div class="container">
        <div class="swiper" role="region" aria-label="Galería de imágenes">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img class="swiper-slide__image"
                         src="{{ asset($proyecto->url_imagen) }}"
                         alt="{{ $proyecto->nombre }}"
                         loading="lazy">
                </div>

                @foreach ($medias as $media)
                    <div class="swiper-slide">
                        <img class="swiper-slide__image"
                             src="{{ asset($media->url_imagen) }}"
                             alt="{{ $media->descripcion ?: $proyecto->descripcion }}"
                             loading="lazy">
                    </div>
                @endforeach
            </div>

            <div class="swiper-pagination" aria-hidden="true"></div>
            <button class="swiper-button-prev" aria-label="Imagen anterior"></button>
            <button class="swiper-button-next" aria-label="Siguiente imagen"></button>
        </div>
    </div>

    {{-- CONTENIDO PRINCIPAL - SIN FORMULARIO --}}
    <main class="main-content">
        <article class="project-content" role="article">
            <section class="card" aria-labelledby="proyecto-descripcion">
                <div class="card__header">
                    <img class="card__avatar"
                         src="{{ asset($proyecto->url_imagen) }}"
                         alt="{{ $proyecto->nombre }}"
                         width="60" height="60">
                    <div>
                        <h3 id="proyecto-descripcion" class="card__title">{{ $proyecto->nombre }}</h3>
                        <p class="card__description">{{ $proyecto->descripcion }}</p>
                    </div>
                </div>
            </section>

            {{-- GALERÍA DE MEDIA --}}
            @foreach ($medias as $media)
                <section class="card" aria-labelledby="media-{{ $loop->index }}">
                    <div class="card__header">
                        <img class="card__avatar"
                             src="{{ asset($proyecto->url_imagen) }}"
                             alt="{{ $proyecto->nombre }}"
                             width="60" height="60">
                        <div>
                            <h3 id="media-{{ $loop->index }}" class="card__title">{{ $proyecto->nombre }}</h3>
                            <p class="card__description">
                                {{ $media->descripcion ?: $proyecto->descripcion }}
                            </p>
                        </div>
                    </div>

                    <img class="swiper-slide__image"
                         src="{{ asset($media->url_imagen) }}"
                         alt="{{ $media->descripcion ?: $proyecto->descripcion }}"
                         loading="lazy">
                </section>
            @endforeach
        </article>
    </main>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/leaflet.js') }}"></script>

    <script>
        // Inicializar Swiper
        const swiper = new Swiper('.swiper', {
            direction: 'horizontal',
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            effect: 'slide',
            speed: 800,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
                dynamicBullets: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            on: {
                init: function () {
                    console.log('Swiper inicializado en modo oscuro');
                }
            }
        });

        // Menú responsive
        const navToggle = document.querySelector('.nav__toggle');
        const navList = document.querySelector('.nav__list');

        navToggle.addEventListener('click', () => {
            const isExpanded = navToggle.getAttribute('aria-expanded') === 'true';
            navToggle.setAttribute('aria-expanded', !isExpanded);
            navList.classList.toggle('nav__list--open');
        });

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', (event) => {
            if (!navToggle.contains(event.target) && !navList.contains(event.target)) {
                navToggle.setAttribute('aria-expanded', 'false');
                navList.classList.remove('nav__list--open');
            }
        });

        // Manejo de teclado para el menú
        navToggle.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                navToggle.setAttribute('aria-expanded', 'false');
                navList.classList.remove('nav__list--open');
                navToggle.focus();
            }
        });

        // SISTEMA DE MODO OSCURO/CLARO (Inicia en oscuro)
        const themeToggle = document.querySelector('.theme-toggle');

        // Verificar tema guardado o usar oscuro por defecto
        const savedTheme = localStorage.getItem('theme');
        const defaultTheme = 'dark'; // Modo oscuro por defecto
        const currentTheme = savedTheme || defaultTheme;

        // Aplicar tema oscuro por defecto
        document.body.setAttribute('data-theme', currentTheme);
        updateThemeIcon(currentTheme);

        // Toggle de tema
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            // Aplicar transición suave
            document.documentElement.style.transition = 'all 0.5s ease';

            document.body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);

            // Reiniciar transición después de la animación
            setTimeout(() => {
                document.documentElement.style.transition = '';
            }, 500);
        });

        function updateThemeIcon(theme) {
            // Modo oscuro: muestra sol (para cambiar a claro)
            // Modo claro: muestra luna (para cambiar a oscuro)
            themeToggle.textContent = theme === 'dark' ? '☀️' : '🌙';
            themeToggle.setAttribute('aria-label', theme === 'dark'
                ? 'Cambiar a modo claro'
                : 'Cambiar a modo oscuro');
            themeToggle.title = theme === 'dark' ? 'Modo oscuro activado - Cambiar a claro' : 'Modo claro activado - Cambiar a oscuro';
        }

        // Detectar preferencia del sistema (solo si no hay tema guardado)
        if (!savedTheme) {
            const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');

            // Si el sistema prefiere oscuro y no hay tema guardado, mantener oscuro
            // Si prefiere claro, podríamos cambiarlo, pero mantenemos oscuro por defecto
            if (!prefersDarkScheme.matches) {
                console.log('El sistema prefiere modo claro, pero mantenemos oscuro por defecto');
            }
        }

        // Efecto de animación al cargar
        document.addEventListener('DOMContentLoaded', () => {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.5s ease';

            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);
        });
    </script>
</body>
</html>
