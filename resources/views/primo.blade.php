@extends('app')

@section('contenido')
    <h1 class="text-center text-black">Verifica si el numero es primo</h1>

    @if(session('resultado'))
        <div class="alert alert-info mt-3">
            {{ session('resultado') }}
        </div>
    @endif

    <div class="container mt-4">
        <form method="POST" action="/primo">
            @csrf
            <div class="form-group">
                <label for="numero">Ingrese un número:</label>
                <input type="number" class="form-control" id="numero" name="numero" placeholder="Ingrese un número" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Verificar</button>
            <a href="/panel" class="btn btn-secondary mt-3">Regresar</a>
        </form>
    </div>
@endsection