@extends('layouts.app')
@section('content')

<div class="container">
    <h1>Productos</h1>
    <a href="{{ route('productos.create') }}" class="btn btn-primary">Agregar Producto</a>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Formulario de búsqueda -->
    <form action="{{ route('productos.index') }}" method="GET" class="mb-4 mt-3 p-3 bg-light rounded shadow-sm">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="nombre" class="form-label fw-bold">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Buscar por nombre..." value="{{ request('nombre') }}">
                @error('nombre') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label for="categoria" class="form-label fw-bold">Categoría</label>
                <input type="text" name="categoria" id="categoria" class="form-control" placeholder="Ej: Electrónica" value="{{ request('categoria') }}">
            </div>
            <div class="col-md-2">
                <label for="precio_min" class="form-label fw-bold">Precio Mín</label>
                <input type="number" name="precio_min" id="precio_min" class="form-control" placeholder="0" min="0" value="{{ request('precio_min') }}">
            </div>
            <div class="col-md-2">
                <label for="precio_max" class="form-label fw-bold">Precio Máx</label>
                <input type="number" name="precio_max" id="precio_max" class="form-control" placeholder="Max" min="0" value="{{ request('precio_max') }}">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </div>
        
    </form>

    <!-- Card Opcional para mostrar resultados -->
    @if($productos->count() > 0)
        <div class="d-none d-md-block"> <!-- Ocultar en móviles si se prefiere tabla -->
            <table class="table table-striped table-hover mt-3 shadow-sm rounded">
                <thead class="table-dark">

            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Categoría</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->precio }}</td>
                    <td>{{ $producto->categoria }}</td>
                    <td>
                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este producto?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    <!-- Vista de Cards para móviles (Opcional) -->
    <div class="d-md-none">
        @foreach ($productos as $producto)
            <div class="card mb-3 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title text-primary fw-bold">{{ $producto->nombre }}</h5>
                    <p class="card-text mb-1"><strong>Precio:</strong> ${{ number_format($producto->precio, 2) }}</p>
                    <p class="card-text text-muted"><strong>Categoría:</strong> {{ $producto->categoria }}</p>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm">Editar</a>
                        <form action="{{ route('productos.destroy', $producto) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @else
        <div class="alert alert-info text-center mt-4">No se encontraron productos.</div>
    @endif
</div>

@endsection