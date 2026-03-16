@extends('app')

@section('contenido')
    <h1 class="text-center text-black">Formulario de Saludo</h1>

    @if(session('resultado'))
        <div class="alert alert-info mt-3">
            {{ session('resultado') }}
        </div>
    @endif
    
    <div class="container mt-4">
        <form method="POST" action="/saludo">
            @csrf
            <div class="form-group">
                <label for="nombre">Ingrese su nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ingrese su nombre" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Enviar</button>
            <a href="/panel" class="btn btn-secondary mt-3">Regresar</a>
        </form>
    </div>
@endsection