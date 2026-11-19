<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ColectivoController;
use App\Http\Controllers\ChoferController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\BoletoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\PasajeroController;
use App\Http\Controllers\HistorialController;
 

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
            Route::get('',              'index')->name('choferes.index');
            Route::get('create',        'create')->name('choferes.create');
            Route::post('',             'store')->name('choferes.store');
            Route::get('{chofer}',      'show')->name('choferes.show');
            Route::get('{chofer}/edit', 'edit')->name('choferes.edit');
            Route::put('{chofer}',      'update')->name('choferes.update');
            Route::delete('{chofer}',   'destroy')->name('choferes.destroy');
        });
    });

    // 🚌 PANEL OPERATIVO DEL CONDUCTOR (Embarque en ruta)
    Route::get('/chofer/mis-viajes', [ChoferController::class, 'Embarquechofer'])
        ->name('chofer.viajes');
    Route::patch('/chofer/boleto/{boleto}/{estado}', [ChoferController::class, 'marcarEmbarque'])
        ->name('chofer.embarque');

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
        Route::controller(BoletoController::class)->group(function () {
            Route::get('',                'index')->name('boleteria.index')->middleware('permission:venta.index','permission:venta.store');
            Route::get('buscar-dni/{dni}', 'buscarPorDni')->name('boleteria.buscarDni');
            Route::get('viaje/{viaje}',   'create')->name('boleteria.create')->middleware('permission:venta.store');
            Route::post('vender',         'store')->name('boleteria.store')->middleware('permission:venta.store');
            Route::get('boleto/{boleto}', 'show')->name('boleteria.show')->middleware('permission:venta.show');
            Route::delete('anular/{id}',  'destroy')->name('boleteria.destroy')->middleware('permission:venta.destroy');
        });
    });

           // 📊 MODULO: REPORTES GERENCIALES
        Route::get('/gerencia/reportes', [ReporteController::class, 'index'])
            ->name('gerencia.reportes.index')
            ->middleware('permission:reporte.recaudacion');

// 🎟️ MODULO: HISTORIAL DEL PASAJERO
Route::get('/mis-boletos', [PasajeroController::class, 'index'])->name('pasajero.boletos.index');
Route::get('/mis-boletos/{boleto}', [PasajeroController::class, 'show'])->name('pasajero.boletos.show');
Route::patch('/mis-boletos/{boleto}/ausencia', [PasajeroController::class, 'reportarAusencia'])->name('mis-boletos.ausencia'); 


});

require __DIR__.'/auth.php';