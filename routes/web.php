<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ProyectoController;
use App\Http\Controllers\GrupoTareaController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\AdminUsuariosController;
use App\Http\Controllers\MisTareasController;
use App\Http\Controllers\MisClientesController;
use App\Http\Controllers\UsuarioTareaController;
use App\Http\Controllers\AdminLeadToClienteController;
use App\Http\Controllers\AdminEmbudoController;
use App\Http\Controllers\AdminEtapaController;
use App\Http\Controllers\EmbudoController;
use App\Http\Controllers\SolicitudClienteController;
use App\Http\Controllers\ReferidoController;
use App\Http\Controllers\MediaProyectoController;
use App\Http\Controllers\LinkProyectoController;
use App\Http\Controllers\ProyectoUsuarioController;
use App\Http\Controllers\PaginaPublicasController;
use App\Http\Controllers\AdminUsuariosCrudController;
use App\Http\Controllers\PaginaUsuarioController;
use App\Http\Controllers\ClienteArchivoController;
use App\Http\Controllers\ClienteUsuarioAdminController;
use App\Http\Controllers\AnalistaController;


use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Ruta PÚBLICA (para usuarios NO logueados)
Route::get('/', function () {
    if (!Auth::check()) {
        $proyectos = \App\Models\Proyecto::with(['mediaProyectos', 'linkProyectos'])
            ->latest()
            ->get();
        return view('welcome', compact('proyectos'));
    }
    return redirect()->route('home');
})->name('home.public');

// ==============================================
// RUTAS PÚBLICAS (accesibles sin autenticación)
// ==============================================

// Ruta para ver todos los proyectos públicos
Route::get('open', [PaginaPublicasController::class, 'index'])
    ->name('public.projects.index');

// Ruta para ver un proyecto público individual
Route::get('open/{id}', [PaginaPublicasController::class, 'show'])
    ->name('public.projects.show');

// Ruta para ver un proyecto público con un usuario específico
Route::get('open/{proyecto}/usuario/{usuario}', [PaginaPublicasController::class, 'showWithUser'])
    ->name('public.projects.withUser');

// Ruta para ver perfil público de un usuario
Route::get('usuario/{id}', [PaginaPublicasController::class, 'showUserProfile'])
    ->name('public.users.profile');

// API para obtener datos de contacto (para formularios AJAX)
Route::get('open/{proyecto}/contact-info/{usuario?}', [PaginaPublicasController::class, 'getContactInfo'])
    ->name('public.projects.contactInfo');

Route::get('lead-success/{leadId?}', [PaginaPublicasController::class, 'leadSuccess'])
    ->name('public.lead.success');

// ==============================================
// RUTAS PRIVADAS
// ==============================================

// Ruta PRIVADA (para usuarios logueados)
Route::get('/home', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('home');

// RUTAS PARA TODOS LOS USUARIOS AUTENTICADOS
Route::middleware('auth')->group(function () {
    // Perfil (todos)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::put('/profile/photo', [ProfileController::class, 'updateProfilePhoto'])
        ->name('profile.photo.update');

    // HERRAMIENTAS BÁSICAS (disponible para todos los autenticados)
    Route::get('/lead', [LeadController::class, 'index'])->name('lead.index');
    Route::resource('leads', LeadController::class);

    Route::resource('mistareas', MisTareasController::class);
    Route::resource('misclientes', MisClientesController::class);
    Route::resource('proyectos', ProyectoUsuarioController::class);

    Route::resource('paginasusuario', PaginaUsuarioController::class);
    Route::resource('embudos', EmbudoController::class);

    Route::resource('solicitudcliente', SolicitudClienteController::class);

});

// RUTAS PARA COORDINADORES
Route::middleware(['auth', 'role:coordinador'])->group(function () {
    // COORDINACIÓN
    Route::resource('referidos', ReferidoController::class);

    // ESTA RUTA NO ES EL ADMIN SOLO SIRVE PARA LAS TAREAS
    Route::resource('adminusuarios', AdminUsuariosController::class);
    Route::resource('usuariotarea', UsuarioTareaController::class);

    Route::get('clienteusuarioadmin/show/{id}', [ClienteUsuarioAdminController::class, 'show']);
    Route::get('clienteusuarioadmin/archivo-usuario', [ClienteUsuarioAdminController::class, 'archivoUsuario']);


});

// RUTAS PARA ADMINISTRADORES
Route::middleware(['auth', 'role:administrador'])->group(function () {
    // ADMINISTRACIÓN
    Route::resource('adminleadtocliente', AdminLeadToClienteController::class);

    Route::resource('adminproyectos', ProyectoController::class);
    Route::resource('adminembudos', AdminEmbudoController::class);
    Route::resource('adminetapas', AdminEtapaController::class);
    Route::resource('linkproyecto', LinkProyectoController::class);
    Route::resource('adminmediaproyectos', MediaProyectoController::class);


    // GESTIÓN DE TAREAS
    Route::resource('grupotareas', GrupoTareaController::class);
    Route::resource('tareas', TareaController::class);
    // Rutas específicas de admin
    Route::post('/solicitud-cliente', [SolicitudClienteController::class, 'store'])->name('solicitudcliente.store');
    Route::post('/solicitudes-clientes/{id}/aprobar', [AdminLeadToClienteController::class, 'aprobarSolicitud'])->name('adminleadtocliente.aprobar');
    Route::post('/solicitudes-clientes/{id}/rechazar', [AdminLeadToClienteController::class, 'rechazarSolicitud'])->name('adminleadtocliente.rechazar');

    // Rutas con prefijo admin
    Route::prefix('admin')->group(function () {
        Route::get('/solicitudes-clientes', [AdminLeadToClienteController::class, 'index'])->name('adminleadtocliente.index');
        Route::get('/solicitudes-clientes/historial', [AdminLeadToClienteController::class, 'historial'])->name('adminleadtocliente.historial');
    });

    // ADMIN USUARIOS CRUD - Gestión completa
    // ADMIN USUARIOS MAESTRO - Gestión completa de usuarios
    Route::prefix('adminusuariosmaestro')->name('adminusuariosmaestro.')->group(function () {
        // Lista principal de usuarios
        Route::get('/', [AdminUsuariosCrudController::class, 'index'])
            ->name('index');

        // Ver información detallada
        Route::get('/detalle/{id}', [AdminUsuariosCrudController::class, 'show'])
            ->name('show');

        // Cambiar contraseña
        Route::get('/{id}/cambiar-password', [AdminUsuariosCrudController::class, 'cambiarPasswordForm'])
            ->name('cambiarPasswordForm');

        // RUTAS ESPECÍFICAS
        Route::post('/{id}/cambiar-passwordfunction', [AdminUsuariosCrudController::class, 'cambiarPasswordfunction'])
            ->name('cambiarPassword');

        Route::post('/{id}/toggle-estado', [AdminUsuariosCrudController::class, 'toggleEstado'])
            ->name('toggleEstado');

        // RUTAS DE ROLES (SIN DUPLICADOS)
        Route::post('/{id}/asignar-rol', [AdminUsuariosCrudController::class, 'asignarRol'])
            ->name('asignarRol');

        Route::delete('/{userId}/remover-rol/{roleId}', [AdminUsuariosCrudController::class, 'removerRol'])
            ->name('removerRol');

        Route::post('/{id}/actualizar-roles', [AdminUsuariosCrudController::class, 'actualizarRoles'])
            ->name('actualizarRoles');

        // Generar contraseña aleatoria (para AJAX)
        Route::get('/generar-contraseña', [AdminUsuariosCrudController::class, 'generarContraseñaAleatoria'])
            ->name('generarContraseña');

        // Editar información del usuario
        Route::get('/{id}/edit', [AdminUsuariosCrudController::class, 'edit'])
            ->name('edit');

        // RUTA DE ACTUALIZACIÓN (debe ir al final)
        Route::put('/{id}', [AdminUsuariosCrudController::class, 'update'])
            ->name('update');

        // Crear nuevo usuario (si lo necesitas)
        // Route::get('/crear', [AdminUsuariosCrudController::class, 'create'])->name('create');
        // Route::post('/', [AdminUsuariosCrudController::class, 'store'])->name('store');

        // Eliminar usuario permanentemente (si lo necesitas)
        // Route::delete('/{id}', [AdminUsuariosCrudController::class, 'destroy'])->name('destroy');
    });


    Route::get('/analytics/lead-rankings', [AnalistaController::class, 'UserLeadGraficas'])
    ->name('analista.lead-rankings');

    Route::get('/analytics/cliente-rankings', [AnalistaController::class, 'UserClienteGraficas'])
    ->name('analista.cliente-rankings');

    Route::get('/analista/global-rankings', [AnalistaController::class, 'UserGlobalGraficas'])
    ->name('analista.global-rankings');

});





// PENDIENTE POR AGREGAR routes/web.php o routes/api.php
// Rutas para archivos de clientes (dentro del grupo auth si es necesario)
Route::middleware(['auth'])->group(function () {
    // Rutas para archivos de clientes
    Route::prefix('cliente')->group(function () {
        // Subir archivo (POST)
        Route::post('/archivos/store', [ClienteArchivoController::class, 'store'])
            ->name('cliente.archivos.store');

        // Obtener archivos de un cliente (GET)
        Route::get('/{clienteId}/archivos', [ClienteArchivoController::class, 'index'])
            ->name('cliente.archivos.index');

        // Descargar archivo
        Route::get('/archivos/{archivo}/download', [ClienteArchivoController::class, 'download'])
            ->name('cliente.archivos.download');

        // Ver archivo en navegador
        Route::get('/archivos/{archivo}/ver', [ClienteArchivoController::class, 'ver'])
            ->name('cliente.archivos.ver');

        // Eliminar archivo
        Route::delete('/archivos/{archivo}', [ClienteArchivoController::class, 'destroy'])
            ->name('cliente.archivos.destroy');
    });
});

// Rutas para clientes con autenticación
Route::middleware(['auth', 'role:administrador,coordinador'])->group(function () {
    // Vista principal del cliente
    Route::get('usuario/clientes/{id}', [ClienteUsuarioAdminController::class, 'show'])
        ->name('cliente.show');

    Route::get('usuario/cliente/{id}/archivos', [ClienteUsuarioAdminController::class, 'archivosclienteusuario'])
        ->name('cliente.archivosclienteusuario');

    // Rutas para archivos (agregar estas)
    Route::post('usuario/cliente/{id}/archivos/subir', [ClienteUsuarioAdminController::class, 'subirNuevoArchiv']);

    Route::get('usuario/cliente/archivos/descargar/{id}', [ClienteUsuarioAdminController::class, 'download']);

    Route::get('usuario/cliente/archivos/ver/{id}', [ClienteUsuarioAdminController::class, 'ver']);

    Route::delete('usuario/cliente/archivos/eliminar/{id}', [ClienteUsuarioAdminController::class, 'deleteArchivo']);
});



// Ruta del welcome original de Laravel (opcional)
Route::get('/welcome-original', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';
