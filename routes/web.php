<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\SubastaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'Index'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/registro', [LoginController::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/subastas', [SubastaController::class, 'index'])->name('subastas.index');
    Route::get('/subastas/crear', [SubastaController::class, 'create'])->name('subastas.create');
    Route::post('/subastas', [SubastaController::class, 'store'])->name('subastas.store');
    Route::get('/subastas/{id}', [SubastaController::class, 'show'])->name('subastas.show');
    Route::post('/subastas/{id}/pujar', [SubastaController::class, 'pujar'])->name('subastas.pujar');

    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::post('/notificaciones/{id}/leida', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.leida');
    Route::post('/notificaciones/leidas-todas', [NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.leidas-todas');

    /* Módulos originales del curso (siguen disponibles por URL directa) */
    Route::resource('clientes', ClienteController::class);
    Route::resource('productos', ProductoController::class);

    Route::get('/panel', function () {
        return redirect()->route('subastas.index');
    });
});

Route::get('/formulario', function () {
    return view('formulario');
});

Route::post('/saludo', function (Request $request) {
    return back()->with('resultado', 'Hola, '.$request->nombre);
});

Route::get('/mayor', function () {
    return view('mayor');
});

Route::post('/mayor', function (Request $request) {
    $num1 = $request->num1;
    $num2 = $request->num2;
    $num3 = $request->num3;
    $mayor = max($num1, $num2, $num3);

    return back()->with('resultado', 'El número mayor es: '.$mayor);
});

Route::get('/primo', function () {
    return view('primo');
});

Route::post('/primo', function (Request $request) {
    $numero = $request->numero;

    if ($numero < 2) {
        return back()->with('resultado', 'El número no es primo.');
    }

    for ($i = 2; $i <= sqrt($numero); $i++) {
        if ($numero % $i == 0) {
            return back()->with('resultado', 'El número no es primo.');
        }
    }

    return back()->with('resultado', 'El número es primo.');
});
