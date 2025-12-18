<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\EmbudoController;
use App\Http\Controllers\PublicLeadController; // ← Agregar este import
use App\Http\Controllers\PaginaPublicasController; // ← Si necesitas las rutas públicas

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ======================== RUTAS PÚBLICAS (SIN AUTENTICACIÓN) ========================
// Leads desde páginas públicas (para show-with-user.blade.php)
Route::post('/leadsusuarios', [PublicLeadController::class, 'store'])
    ->name('api.public.leads.store');

// Estadísticas de leads (opcional)
Route::get('/leadsusuarios/estadisticas/{userId}', [PublicLeadController::class, 'estadisticas'])
    ->name('api.public.leads.stats');

// API para datos de contacto (desde PaginaPublicasController)
Route::get('/open/{proyecto}/contact-info/{usuario?}', [PaginaPublicasController::class, 'getContactInfo'])
    ->name('public.projects.contactInfo');







// ======================== RUTAS PROTEGIDAS (CON AUTENTICACIÓN) ========================
Route::middleware('auth:sanctum')->group(function () {
    // Rutas de Leads
    Route::get('/leads', [LeadController::class, 'index']);
    Route::post('/leads', [LeadController::class, 'store']);
    Route::get('/leads/{id}', [LeadController::class, 'show']);
    Route::put('/leads/{id}', [LeadController::class, 'update']);
    Route::delete('/leads/{id}', [LeadController::class, 'destroy']);

    // ✅ Obtener leads por embudo
    Route::get('/embudos/{embudo}/leads', [LeadController::class, 'getLeadsByEmbudo']);

    // ✅ Mover leads entre etapas
    Route::post('/leads/actualizaretapayorden', [LeadController::class, 'leadToNewEtapa']);

    // ✅ Actualizar orden interno
    Route::post('/leads/actualizar-orden-interno', [LeadController::class, 'actualizarOrdenInterno']);


    //RUTAS PARA USUARIOS
    //Leads, fecha_creado, id_user, data_user
});

