@extends('layouts.app')
@section('content')

<div class='container'>
    <h1>Editar Producto</h1>
    <form action="{{ route('productos.update', $producto) }}" method="POST">
        @csrf
        @method('PUT')
        @include('productos.form')
        <button class="btn btn-primary">Actualizar Producto</button>
    </form>
</div>
@endsection