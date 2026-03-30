@extends('layouts.app')

@section('title', 'Publicar | Ganatelo.com')

@section('content')
<h1 class="h3 mb-3">Nueva subasta</h1>
<p class="text-muted small">Datos del artículo, archivo, precios y tiempos.</p>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
@endif

<form action="{{ route('subastas.store') }}" method="POST" enctype="multipart/form-data" class="card border-0 shadow-sm">
    @csrf
    <div class="card-body row g-3">
        <div class="col-12 col-md-6">
            <label class="form-label">Título del anuncio</label>
            <input type="text" name="titulo_subasta" class="form-control" required value="{{ old('titulo_subasta') }}"
                   maxlength="255">
        </div>
        <div class="col-12 col-md-6">
            <label class="form-label">Nombre del producto</label>
            <input type="text" name="nombre_producto" class="form-control" required value="{{ old('nombre_producto') }}"
                   maxlength="255">
        </div>
        <div class="col-12">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4" required maxlength="5000">{{ old('descripcion') }}</textarea>
        </div>
        <div class="col-12">
            <label class="form-label">Foto o video</label>
            <input type="file" name="media" class="form-control" required accept="image/*,video/mp4,video/webm">
            <div class="form-text">JPG, PNG, GIF, WEBP o MP4/WEBM · máx. 50 MB</div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Precio inicial (Bs)</label>
            <input type="number" name="precio_inicial" class="form-control" step="0.01" min="0" required value="{{ old('precio_inicial') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Precio mínimo de venta (Bs)</label>
            <input type="number" name="precio_minimo" class="form-control" step="0.01" min="0" required value="{{ old('precio_minimo') }}">
            <div class="form-text">Si al cerrar la puja más alta queda por debajo, la venta no se concreta.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Inicio</label>
            <input type="datetime-local" name="empieza_en" class="form-control" required value="{{ old('empieza_en') }}">
            <div class="form-text">Incluye hora. Si la hora ya pasó, cuenta desde ahora.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Duración inicial (minutos)</label>
            <input type="number" name="duracion_minutos" class="form-control" min="1" required value="{{ old('duracion_minutos', 60) }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Minutos extra por cada puja</label>
            <input type="number" name="extension_por_oferta_minutos" class="form-control" min="0" required value="{{ old('extension_por_oferta_minutos', 5) }}">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-warning fw-semibold px-4">Publicar</button>
            <a href="{{ route('subastas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
        </div>
    </div>
</form>
@endsection
