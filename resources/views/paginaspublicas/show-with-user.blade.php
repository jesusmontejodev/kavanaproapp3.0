<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- META TAGS MEJORADOS --}}
    <meta name="description" content="{{ $proyecto->descripcion_personalizada ?? $proyecto->descripcion }}">
    <meta property="og:title" content="{{ $proyecto->nombre }} - {{ $usuario->name }}">
    <meta property="og:description" content="{{ $proyecto->descripcion_personalizada ?? $proyecto->descripcion }}">
    <meta property="og:image" content="{{ asset($proyecto->url_imagen) }}">
    <meta name="robots" content="index, follow">

    {{-- ESTILOS EXTERNOS --}}
    <link rel="stylesheet" href="{{ asset('styles/leaflet.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="{{ asset('styles/inmuebleTienda.css') }}">

    {{-- FAVICON --}}
    <link rel="icon" href="{{ asset($proyecto->url_imagen) }}" type="image/png">

    {{-- TÍTULO --}}
    <title>{{ $proyecto->nombre }} - {{ $usuario->name }} | Kavana</title>

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

        .hero__subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-top: 10px;
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

        /* LAYOUT PRINCIPAL */
        .main-content {
            display: flex;
            gap: var(--spacing-lg);
            margin: var(--spacing-lg) auto;
            width: 90%;
            max-width: 1100px;
        }

        /* FORMULARIO - Estilo modo oscuro */
        .lead-form {
            background: var(--color-surface);
            padding: var(--spacing-md);
            border-radius: var(--border-radius);
            width: 32%;
            box-shadow: var(--box-shadow);
            position: sticky;
            top: 120px;
            align-self: flex-start;
            border: var(--card-border);
        }

        .lead-form__header {
            text-align: center;
            margin-bottom: var(--spacing-md);
            padding-bottom: var(--spacing-sm);
            border-bottom: 1px solid var(--color-border);
        }

        .lead-form__image {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: var(--spacing-sm);
            border: 3px solid var(--color-primary);
            box-shadow: 0 0 15px rgba(138, 106, 255, 0.3);
        }

        .form-group {
            margin-bottom: var(--spacing-sm);
        }

        .form-label {
            display: block;
            margin-bottom: var(--spacing-xs);
            font-weight: 600;
            color: var(--color-text);
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 14px;
            border-radius: 10px;
            border: 1px solid var(--color-border);
            font-family: inherit;
            font-size: 1rem;
            transition: all 0.2s ease;
            background: rgba(0, 0, 0, 0.2);
            color: var(--color-text);
        }

        [data-theme="light"] .form-input {
            background: rgba(255, 255, 255, 0.9);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(138, 106, 255, 0.2);
            background: rgba(0, 0, 0, 0.3);
        }

        .form-input::placeholder {
            color: var(--color-text-light);
            opacity: 0.6;
        }

        .btn {
            width: 100%;
            padding: 14px;
            border: none;
            color: white;
            font-weight: bold;
            background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.25s ease;
            font-size: 1rem;
            border: 2px solid transparent;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn:hover,
        .btn:focus {
            background: linear-gradient(135deg, var(--color-primary-dark), var(--color-primary));
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(138, 106, 255, 0.4);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ReCaptcha container */
        .g-recaptcha {
            margin-bottom: var(--spacing-sm);
            display: flex;
            justify-content: center;
        }

        /* Mensajes de error/success */
        .alert {
            padding: var(--spacing-sm);
            border-radius: 10px;
            margin-bottom: var(--spacing-sm);
            border: 2px solid;
        }

        .alert-success {
            background-color: rgba(46, 204, 113, 0.1);
            border-color: #2ecc71;
            color: #2ecc71;
        }

        .alert-error {
            background-color: rgba(231, 76, 60, 0.1);
            border-color: #e74c3c;
            color: #e74c3c;
        }

        /* CONTENIDO DEL PROYECTO */
        .project-content {
            width: 68%;
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

        .card__footer {
            margin-top: var(--spacing-sm);
            padding-top: var(--spacing-sm);
            border-top: 1px solid var(--color-border);
            font-size: 0.9rem;
            color: var(--color-text-light);
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .main-content {
                flex-direction: column;
            }

            .lead-form,
            .project-content {
                width: 100%;
                position: static;
            }

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

            .hero__subtitle {
                font-size: 1rem;
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
        .lead-form,
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
                <li><a class="nav__link" href="/register">REGÍSTRATE</a></li>
                <li><a class="nav__link" href="/login">INICIAR SESIÓN</a></li>
            </ul>
        </nav>
    </header>

    {{-- HERO SECTION --}}
    <section class="hero" role="region" aria-label="Información del proyecto">
        <img class="hero__image" src="{{ asset($usuario->foto_perfil ?? $proyecto->url_imagen) }}" alt="{{ $usuario->name ?? $proyecto->nombre }}" width="100" height="100">
        <h1 class="hero__title">{{ $proyecto->nombre }}</h1>
        @if($usuario)
        <p class="hero__subtitle">Presentado por: {{ $usuario->name }} - {{ $usuario->empresa }}</p>
        @endif
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

    {{-- CONTENIDO PRINCIPAL --}}
    <main class="main-content">
        {{-- FORMULARIO DE CONTACTO CON RECAPTCHA --}}
        <aside class="lead-form" role="complementary">
            <div class="lead-form__header">
                <img class="lead-form__image"
                     src="{{ asset($usuario->foto_perfil ?? $proyecto->url_imagen) }}"
                     alt="{{ $usuario->name ?? $proyecto->nombre }}"
                     width="90" height="90">
                @if($usuario)
                <h2>Contacta a: {{ $usuario->name }}</h2>
                <p>{{ $usuario->empresa }}</p>
                @else
                <h2>Proyecto: {{ $proyecto->nombre }}</h2>
                @endif
            </div>

            <form id="leadForm" role="form" aria-label="Formulario de contacto">
                @csrf
                <input type="hidden" name="id_usuario" value="{{ $usuario->id ?? '' }}">
                <input type="hidden" name="id_proyecto" value="{{ $proyecto->id ?? '' }}">
                <input type="hidden" name="nombre_proyecto" value="{{ $proyecto->nombre ?? '' }}">

                {{-- Mensajes de estado --}}
                <div id="formMessage" class="alert" style="display: none;"></div>

                <div class="form-group">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text"
                        id="nombre"
                        name="nombre"
                        class="form-input"
                        required
                        placeholder="Tu nombre completo"
                        aria-required="true"
                        minlength="2"
                        maxlength="100">
                </div>

                <div class="form-group">
                    <label for="correo" class="form-label">Email:</label>
                    <input type="email"
                        id="correo"
                        name="correo"
                        class="form-input"
                        required
                        placeholder="ejemplo@correo.com"
                        aria-required="true"
                        pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                </div>

                <div class="form-group">
                    <label for="numero_telefono" class="form-label">Teléfono:</label>
                    <input type="tel"
                        id="numero_telefono"
                        name="numero_telefono"
                        class="form-input"
                        required
                        placeholder="Ej: 555-123-4567"
                        aria-required="true"
                        pattern="[0-9\s\-\+\(\)]{10,20}"
                        title="Mínimo 10 dígitos">
                    <small class="form-text" style="color: var(--color-text-light); font-size: 0.8rem;">
                        Ej: 555-123-4567 o +1 (555) 123-4567
                    </small>
                </div>

                {{-- ReCaptcha --}}
                <div class="form-group">
                    <div class="g-recaptcha"
                        data-sitekey="{{ env('RECAPTCHA_PUBLIC', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI') }}"
                        data-callback="enableSubmitBtn"
                        data-expired-callback="disableSubmitBtn"
                        data-error-callback="disableSubmitBtn"
                        data-size="normal">
                    </div>
                </div>

                <button type="submit" class="btn" id="submitBtn" disabled>
                    <span id="btnText">Solicitar información</span>
                    <span id="btnSpinner" style="display: none;">
                        <svg style="width: 20px; height: 20px; margin-right: 8px;" class="animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Enviando...
                    </span>
                </button>

                <div class="privacy-notice" style="margin-top: 15px; font-size: 0.8rem; color: var(--color-text-light);">
                    <small>Al enviar este formulario, aceptas nuestra <a href="/politica-privacidad" style="color: var(--color-primary);">Política de Privacidad</a></small>
                </div>
            </form>
        </aside>

        {{-- INFORMACIÓN DETALLADA DEL PROYECTO --}}
        <article class="project-content" role="article">
            <section class="card" aria-labelledby="proyecto-descripcion">
                <div class="card__header">
                    <img class="card__avatar"
                        src="{{ asset($usuario->foto_perfil ?? $proyecto->url_imagen) }}"
                        alt="{{ $usuario->name ?? $proyecto->nombre }}"
                        width="60" height="60">
                    <div>
                        <h3 id="proyecto-descripcion" class="card__title">{{ $proyecto->nombre }}</h3>
                        <p class="card__description">{{ $proyecto->descripcion_personalizada ?? $proyecto->descripcion }}</p>
                        @if($usuario)
                        <div class="card__footer">
                            <small>Publicado por: {{ $usuario->name }} - {{ $usuario->empresa }}</small>
                        </div>
                        @endif
                    </div>
                </div>
            </section>

            {{-- GALERÍA DE MEDIA --}}
            @foreach ($medias as $media)
                <section class="card" aria-labelledby="media-{{ $loop->index }}">
                    <div class="card__header">
                        <img class="card__avatar"
                             src="{{ asset($usuario->foto_perfil ?? $proyecto->url_imagen) }}"
                             alt="{{ $usuario->name ?? $proyecto->nombre }}"
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
    {{-- ReCaptcha --}}
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

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

    // ========== RECAPTCHA Y FORMULARIO ==========
    // Función para habilitar el botón de submit
    function enableSubmitBtn() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = false;
        console.log('ReCaptcha completado - Botón habilitado');
    }

    // Función para deshabilitar el botón de submit
    function disableSubmitBtn() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        console.log('ReCaptcha expirado o error - Botón deshabilitado');
    }

    // Manejo del formulario con AJAX
    document.getElementById('leadForm').addEventListener('submit', async function(event) {
        event.preventDefault();

        const form = event.target;
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnSpinner = document.getElementById('btnSpinner');
        const formMessage = document.getElementById('formMessage');

        // Ocultar mensaje anterior
        formMessage.style.display = 'none';

        // Mostrar spinner y deshabilitar botón
        btnText.style.display = 'none';
        btnSpinner.style.display = 'inline-flex';
        btnSpinner.style.alignItems = 'center';
        btnSpinner.style.justifyContent = 'center';
        submitBtn.disabled = true;

        // Validación básica del teléfono
        const phoneInput = document.getElementById('numero_telefono');
        const phoneRegex = /^[0-9\s\-\+\(\)]{10,20}$/;

        if (!phoneRegex.test(phoneInput.value)) {
            showMessage('Por favor, ingresa un número de teléfono válido (mínimo 10 dígitos)', 'error');
            resetFormButton(btnText, btnSpinner, submitBtn);
            phoneInput.focus();
            return;
        }

        // Validación de email
        const emailInput = document.getElementById('correo');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(emailInput.value)) {
            showMessage('Por favor, ingresa un email válido', 'error');
            resetFormButton(btnText, btnSpinner, submitBtn);
            emailInput.focus();
            return;
        }

        // Validación de nombre
        const nombreInput = document.getElementById('nombre');
        if (nombreInput.value.trim().length < 2) {
            showMessage('El nombre debe tener al menos 2 caracteres', 'error');
            resetFormButton(btnText, btnSpinner, submitBtn);
            nombreInput.focus();
            return;
        }

        // Verificar ReCaptcha
        const recaptchaResponse = grecaptcha.getResponse();
        if (!recaptchaResponse) {
            showMessage('Por favor, completa la verificación "No soy un robot"', 'error');
            resetFormButton(btnText, btnSpinner, submitBtn);
            return;
        }

        // Agregar token ReCaptcha al formulario
        const formData = new FormData(form);
        formData.append('recaptcha_token', recaptchaResponse);

        try {
            const response = await fetch('/api/leadsusuarios', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                showMessage(data.message || '¡Gracias! Tu información ha sido enviada correctamente.', 'success');

                // Opción 1: Resetear formulario
                form.reset();
                grecaptcha.reset();
                disableSubmitBtn();

                // Opción 2: Redireccionar a página de éxito (descomentar si quieres)
                /*
                setTimeout(() => {
                    window.location.href = '/lead-success/' + data.lead_id;
                }, 2000);
                */
            } else {
                showMessage(data.message || 'Hubo un error al enviar el formulario. Por favor, intenta nuevamente.', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showMessage('Error de conexión. Por favor, verifica tu internet e intenta nuevamente.', 'error');
        } finally {
            // Restaurar botón
            resetFormButton(btnText, btnSpinner, submitBtn);
        }
    });

    // Función para resetear estado del botón
    function resetFormButton(btnText, btnSpinner, submitBtn) {
        btnText.style.display = 'inline';
        btnSpinner.style.display = 'none';
        submitBtn.disabled = false;
    }

    // Función para mostrar mensajes
    function showMessage(message, type = 'success') {
        const formMessage = document.getElementById('formMessage');
        formMessage.textContent = message;
        formMessage.className = `alert alert-${type}`;
        formMessage.style.display = 'block';

        // Scroll suave al mensaje
        formMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        // Auto-ocultar mensaje después de 5 segundos (solo éxito)
        if (type === 'success') {
            setTimeout(() => {
                formMessage.style.display = 'none';
            }, 5000);
        }
    }

    // Agregar animación de spinner al CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .animate-spin {
            animation: spin 1s linear infinite;
        }
    `;
    document.head.appendChild(style);

    // Validación en tiempo real del formulario
    document.addEventListener('DOMContentLoaded', function() {
        const phoneInput = document.getElementById('numero_telefono');
        const emailInput = document.getElementById('correo');

        if (phoneInput) {
            phoneInput.addEventListener('input', function() {
                const phoneRegex = /^[0-9\s\-\+\(\)]{10,20}$/;
                if (this.value && !phoneRegex.test(this.value)) {
                    this.style.borderColor = '#e74c3c';
                } else {
                    this.style.borderColor = '';
                }
            });
        }

        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (this.value && !emailRegex.test(this.value)) {
                    this.style.borderColor = '#e74c3c';
                } else {
                    this.style.borderColor = '';
                }
            });
        }
    });
</script>
</body>
</html>
