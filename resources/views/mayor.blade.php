@extends('app')
@section('contenido')
    <h2 class="text-center text-black">Calcular el mayor de tres numeros</h2>

    @if(session('resultado'))
        <div class="alert alert-info mt-3">
            {{ session('resultado') }}
        </div>
    @endif

    <div class="container mt-4">
        <form method="POST" action="/mayor">
            @csrf
            <div class="form-group">
                <label for="num1">Primer número:</label>
                <input type="number" class="form-control" id="num1" name="num1" placeholder="Primer número" required>
            </div>
            <div class="form-group">
                <label for="num2">Segundo número:</label>
                <input type="number" class="form-control" id="num2" name="num2" placeholder="Segundo número" required>
            </div>
            <div class="form-group">
                <label for="num3">Tercer número:</label>
                <input type="number" class="form-control" id="num3" name="num3" placeholder="Tercer número" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Calcular</button>
            <a href="/panel" class="btn btn-secondary mt-3">Regresar</a>
        </form>
    </div>
@endsection