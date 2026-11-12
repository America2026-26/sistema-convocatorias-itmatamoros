<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\AdminExpedienteController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ConvocatoriaPublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Dictaminador\ConvocatoriaController as DictaminadorConvocatoriaController;
use App\Http\Controllers\Dictaminador\PostulanteController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostulacionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExpedienteController;
use App\Http\Controllers\CalendarioController;
use App\Models\Convocatoria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

// Ruta Principal (Home)
Route::get('/', function (Request $request) {
    $filters = $request->validate([
        'tipo_concurso' => ['nullable', Rule::in(['Abierta', 'Cerrada'])],
        'tipo_personal' => ['nullable', Rule::in(['Docente', 'No docente'])],
    ]);

    $preferredPersonnelType = $filters['tipo_personal']
        ?? match ($request->user()?->tipo_usuario) {
            'aspirante_docente' => 'Docente',
            'aspirante_no_docente' => 'No docente',
            default => null,
        };

    $convocatorias = Convocatoria::query()
        ->where('estatus', 'Activa')
        ->whereDate('fecha_inicio', '<=', today())
        ->whereDate('fecha_fin', '>=', today())
        ->when(
            isset($filters['tipo_concurso']),
            fn ($query) => $query->where('tipo_concurso', $filters['tipo_concurso']),
        )
        ->when(
            isset($filters['tipo_personal']),
            fn ($query) => $query->where('tipo_personal', $filters['tipo_personal']),
        )
        ->when(
            $preferredPersonnelType !== null,
            fn ($query) => $query->orderByRaw(
                'CASE WHEN tipo_personal = ? THEN 0 ELSE 1 END',
                [$preferredPersonnelType],
            ),
        )
        ->orderBy('fecha_fin')
        ->orderByDesc('id')
        ->paginate(10)
        ->withQueryString();

    return view('welcome', [
        'convocatorias' => $convocatorias,
        'filters' => [
            'tipo_concurso' => $filters['tipo_concurso'] ?? '',
            'tipo_personal' => $filters['tipo_personal'] ?? '',
        ],
    ]);
})->name('home');

Route::get('/convocatorias/{convocatoria}', [ConvocatoriaPublicController::class, 'show'])
    ->name('convocatorias.show');

// Rutas de Autenticación (Invitados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::get('/registro', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/registro', [RegisteredUserController::class, 'store'])->name('register.store');
});

// Rutas Generales Autenticadas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Notificaciones
    Route::get('/notificaciones', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notificaciones/{notificationId}', [NotificationController::class, 'show'])->name('notifications.show');
    Route::post('/notificaciones/leer-todas', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
    
    // Perfil y Configuración
    Route::get('/mi-perfil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/mi-perfil/foto', [ProfileController::class, 'photo'])->name('profile.photo');
    Route::post('/mi-perfil/foto', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    Route::delete('/mi-perfil/foto', [ProfileController::class, 'destroyPhoto'])->name('profile.photo.destroy');
    Route::get('/configuracion', [ProfileController::class, 'settings'])->name('profile.settings');

    // Expediente del Aspirante y Calendario Institucional
    Route::get('/mi-expediente/estado', [ExpedienteController::class, 'estado'])->name('expediente.estado');
    Route::post('/mi-expediente/subir', [ExpedienteController::class, 'subirDocumento'])->name('expediente.subir');
    Route::get('/calendario-institucional', [CalendarioController::class, 'index'])->name('calendario.institucional');

    // Rutas para Aspirantes / Usuarios normales
    Route::middleware('role:'.User::ROLE_USER)->group(function () {
        Route::get('/mis-postulaciones', [PostulacionController::class, 'index'])->name('postulaciones.index');
        Route::get('/mis-postulaciones/{postulacion}', [PostulacionController::class, 'show'])->name('postulaciones.show');
        Route::get('/mis-postulaciones/{postulacion}/documento', [PostulacionController::class, 'document'])->name('postulaciones.documento');
        Route::post('/convocatorias/{convocatoria}/postular', [PostulacionController::class, 'store'])->name('postulaciones.store');
    });

    // Panel de Administración General, Expedientes y Usuarios
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
        
        // Gestión de Usuarios y Aspirantes
        Route::resource('users', AdminUserController::class)->except('show');
        
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        
        // Gestión de Expedientes Digitales
        Route::get('/expedientes', [AdminExpedienteController::class, 'index'])->name('expedientes.index');
        Route::post('/expedientes/{id}/estatus', [AdminExpedienteController::class, 'actualizarEstatus'])->name('expedientes.estatus');
    });

    // Panel de Dictaminadores
    Route::prefix('dictaminador')->name('dictaminador.')
        ->middleware('role:'.User::ROLE_REVIEWER)
        ->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'show'])->name('dashboard');
            Route::resource('convocatorias', DictaminadorConvocatoriaController::class)->except('show');
            Route::get('/postulantes', [PostulanteController::class, 'index'])->name('postulantes.index');
            Route::get('/postulantes/{postulacion}', [PostulanteController::class, 'show'])->name('postulantes.show');
            Route::patch('/postulantes/{postulacion}/dictamen', [PostulanteController::class, 'review'])->name('postulantes.review');
            Route::get('/postulantes/{postulacion}/documento', [PostulanteController::class, 'document'])
                ->middleware('role:'.User::ROLE_REVIEWER.','.User::ROLE_ADMIN)
                ->name('postulantes.document');
        });
});