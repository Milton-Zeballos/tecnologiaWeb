@extends('app')

@section('contenido')
    <h1 class="text-center text-black" >Bienvenido a mi proyecto Laravel</h1>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-4">
                <a href="/formulario" class="btn btn-primary w-100 text-white">Ir al formulario</a>
            </div>
            <div class="col-md-4">
                <a href="/mayor" class="btn btn-success w-100 text-white">Calcular el mayor de tres números</a>
            </div>
            <div class="col-md-4">
                <a href="/primo" class="btn btn-warning w-100 text-dark">Verificar si un número es primo</a>
            </div>
        </div>
    </div>
@endsection