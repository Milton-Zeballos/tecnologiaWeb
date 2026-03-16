<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\LoginController;

Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/panel', function () {
    return view('welcome');
})->middleware('auth');

Route::get('/formulario', function () {
    return view('formulario');
});

Route::post('/saludo', function (Request $request) {
    return back()->with('resultado', "Hola, " . $request->nombre);
});


Route::get('/mayor', function () {
    return view('mayor');
});

Route::post('/mayor', function (Request $request) {
    $num1 = $request->num1;
    $num2 = $request->num2;
    $num3 = $request->num3;

    $mayor = max($num1, $num2, $num3);

    return back()->with('resultado', "El número mayor es: " . $mayor);
});


Route::get('/primo', function () {
    return view('primo');
});

Route::post('/primo', function (Request $request) {
    $numero = $request->numero;

    if ($numero < 2) {
        return back()->with('resultado', "El número no es primo.");
    }

    for ($i = 2; $i <= sqrt($numero); $i++) {
        if ($numero % $i == 0) {
            return back()->with('resultado', "El número no es primo.");
        }
    }

    return back()->with('resultado', "El número es primo.");
});