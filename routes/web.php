<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ColectivoController;
use App\Http\Controllers\ChoferController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\BoleteriaController;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// 📄 Rutas Públicas / Landing
Route::get('/', function () {
    return view('landing.guri.home');
})->name('home');

// 🔐 Panel de Control General
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 👥 Rutas de Autenticación y Perfil (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // =========================================================================
    // 🚌 MODULO: COLECTIVOS
    // =========================================================================
    Route::prefix('admin/colectivos/')->group(function () {
        Route::controller(ColectivoController::class)->group(function () {
            // Listado
            Route::get('',              'index')->name('colectivos.index')->middleware('permission:colectivo.index');
            // Crear
            Route::get('create',        'create')->name('colectivos.create')->middleware('permission:colectivo.create');
            // Guardar
            Route::post('',             'store')->name('colectivos.store')->middleware('permission:colectivo.create');
            // Mostrar asientos y detalles
            Route::get('{colectivo}',   'show')->name('colectivos.show')->middleware('permission:colectivo.index');
            // Editar
            Route::get('{colectivo}/edit', 'edit')->name('colectivos.edit')->middleware('permission:colectivo.edit');
            // Actualizar
            Route::put('{colectivo}',   'update')->name('colectivos.update')->middleware('permission:colectivo.edit');
            // Eliminar
            Route::delete('{colectivo}', 'destroy')->name('colectivos.destroy')->middleware('permission:colectivo.destroy');
        });
    });

    // =========================================================================
    // 🪪 MODULO: CHOFERES
    // =========================================================================
    Route::prefix('admin/choferes/')->group(function () {
        Route::controller(ChoferController::class)->group(function () {
            // Listado
            Route::get('',              'index')->name('choferes.index');
            // Crear / Promover usuario
            Route::get('create',        'create')->name('choferes.create');
            // Guardar perfil de chofer
            Route::post('',             'store')->name('choferes.store');
            // Mostrar legajo e historial
            Route::get('{chofer}',      'show')->name('choferes.show');
            // Editar carnet / estado
            Route::get('{chofer}/edit', 'edit')->name('choferes.edit');
            // Actualizar
            Route::put('{chofer}',      'update')->name('choferes.update');
            // Eliminar
            Route::delete('{chofer}',   'destroy')->name('choferes.destroy');
        });
    });

    // =========================================================================
    // 🗺️ MODULO: RUTAS COMERCIALES
    // =========================================================================
    Route::prefix('admin/rutas/')->group(function () {
        Route::controller(RutaController::class)->group(function () {
            // Listado
            Route::get('',              'index')->name('rutas.index');
            // Crear
            Route::get('create',        'create')->name('rutas.create');
            // Guardar
            Route::post('',             'store')->name('rutas.store');
            // Mostrar
            Route::get('{ruta}',        'show')->name('rutas.show');
            // Editar
            Route::get('{ruta}/edit',   'edit')->name('rutas.edit');
            // Actualizar
            Route::put('{ruta}',        'update')->name('rutas.update');
            // Eliminar
            Route::delete('{ruta}',     'destroy')->name('rutas.destroy');
        });
    });

    // =========================================================================
    // ⏳ MODULO: VIAJES (PROGRAMACIÓN)
    // =========================================================================
    Route::prefix('admin/viajes/')->group(function () {
        Route::controller(ViajeController::class)->group(function () {
            // Listado de cronogramas
            Route::get('',              'index')->name('viajes.index');
            // Crear nueva salida
            Route::get('create',        'create')->name('viajes.create');
            // Guardar
            Route::post('',             'store')->name('viajes.store');
            // Mostrar estado del viaje y boleto acumulado
            Route::get('{viaje}',       'show')->name('viajes.show');
            // Editar fecha / hora / precio
            Route::get('{viaje}/edit',  'edit')->name('viajes.edit');
            // Actualizar
            Route::put('{viaje}',       'update')->name('viajes.update');
            // Eliminar
            Route::delete('{viaje}',    'destroy')->name('viajes.destroy');
        });
    });

    // =========================================================================
    // 🎫 MODULO: BOLETERÍA (VENTAS Y EMISIÓN)
    // =========================================================================
    Route::prefix('boleteria/')->group(function () {
        Route::controller(BoleteriaController::class)->group(function () {
            // Panel de control de la boletería (Buscar viajes disponibles)
            Route::get('',              'index')->name('boleteria.index');
            // Pantalla para elegir asientos libres de un viaje
            Route::get('viaje/{viaje}', 'create')->name('boleteria.create');
            // Procesar y registrar la Venta + Boleto (Transaccional)
            Route::post('vender',       'store')->name('boleteria.store');
            // Ver comprobante / Boleto emitido
            Route::get('boleto/{boleto}', 'show')->name('boleteria.show');
            // Anular una operación de venta
            Route::delete('anular/{id}', 'destroy')->name('boleteria.destroy');
        });
    });
});

require __DIR__.'/auth.php';