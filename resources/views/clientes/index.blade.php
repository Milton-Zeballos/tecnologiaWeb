@extends('layouts.app')
@section('content')

<div class="container">
    <h1>Clientes</h1>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">Agregar Cliente</a>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <!-- Formulario de búsqueda -->
    <form action="{{ route('clientes.index') }}" method="GET" class="mb-4 mt-3 p-3 bg-light rounded shadow-sm">
        <div class="row g-3">
            <div class="col-md-4">
                <label for="nombre" class="form-label fw-bold">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Buscar por nombre..." value="{{ request('nombre') }}">
                @error('nombre') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label for="correo" class="form-label fw-bold">Correo</label>
                <input type="text" name="correo" id="correo" class="form-control" placeholder="Buscar por correo..." value="{{ request('correo') }}">
                @error('correo') <div class="text-danger small">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-search"></i> Buscar
                </button>
            </div>
        </div>
        
    </form>

    @if($clientes->count() > 0)
        <div class="d-none d-md-block">
            <table class="table table-striped table-hover mt-3 shadow-sm rounded">
                <thead class="table-dark">

            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($clientes as $cliente)
                <tr>
                    <td>{{ $cliente->id }}</td>
                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->correo }}</td>
                    <td>
                        <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm">Editar</a>

                        <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar este cliente?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    @endif
</div>

@endsection