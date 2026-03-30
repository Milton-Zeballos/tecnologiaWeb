@extends('layouts.app')

@section('title', 'Producto — Ganatelo.com')

@section('content')
<div class="card shadow-sm">
    <div class="card-body">
        <h1 class="h4">{{ $producto->nombre }}</h1>
        <p class="mb-0"><strong>Precio:</strong> {{ $producto->precio }}</p>
        <p class="mb-0"><strong>Categoría:</strong> {{ $producto->categoria }}</p>
        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary mt-3">Volver</a>
    </div>
</div>
@endsection
