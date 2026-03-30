@extends('layouts.app')

@section('title', 'Subastas | Ganatelo.com')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h1 class="h3 mb-0">Subastas</h1>
        <p class="text-muted small mb-0">Buscar u ordenar resultados.</p>
    </div>
    <a href="{{ route('subastas.create') }}" class="btn btn-warning fw-semibold">Publicar artículo</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="get" action="{{ route('subastas.index') }}" class="card shadow-sm border-0 mb-4">
    <div class="card-body row g-3 align-items-end">
        <div class="col-md-5">
            <label class="form-label fw-semibold">Buscar</label>
            <input type="text" name="q" class="form-control" placeholder="Nombre, título o texto del anuncio"
                   value="{{ request('q') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold">Ordenar por</label>
            <select name="orden" class="form-select">
                <option value="termina" @selected(($orden ?? '') === 'termina')>Por fecha de cierre</option>
                <option value="precio" @selected(($orden ?? '') === 'precio')>Por precio (mayor primero)</option>
                <option value="nombre" @selected(($orden ?? '') === 'nombre')>Por nombre (A–Z)</option>
            </select>
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-dark flex-grow-1">Buscar</button>
            <a href="{{ route('subastas.index') }}" class="btn btn-outline-secondary">Quitar filtros</a>
        </div>
    </div>
</form>

@if($subastas->isEmpty())
    <div class="alert alert-info">No hay resultados. Publica el primero.</div>
@else
    <div class="row g-4">
        @foreach($subastas as $s)
            @php
                $ahora = now();
                $activa = $s->enVentanaDeSubasta($ahora);
                $futura = $ahora->lessThan($s->empieza_en);
            @endphp
            <div class="col-md-6 col-xl-4">
                <a href="{{ route('subastas.show', $s->id) }}" class="text-decoration-none text-dark">
                    <div class="card h-100 shadow-sm border-0 hover-shadow">
                        @if($s->media_path)
                            <div class="ratio ratio-16x9 bg-light">
                                @if($s->esVideo())
                                    <video class="object-fit-cover rounded-top" muted playsinline preload="metadata" src="{{ $s->urlMedia() }}"></video>
                                @else
                                    <img src="{{ $s->urlMedia() }}" alt="{{ $s->nombre_producto }}" class="object-fit-cover rounded-top">
                                @endif
                            </div>
                        @endif
                        <div class="card-body">
                            <span class="badge {{ $activa ? 'bg-success' : ($futura ? 'bg-secondary' : 'bg-dark') }} mb-2">
                                @if($s->finalizada) Cerrada
                                @elseif($activa) Abierta
                                @elseif($futura) Próxima
                                @else Cerrada @endif
                            </span>
                            <h2 class="h6 card-title">{{ $s->nombre_producto }}</h2>
                            <p class="small text-muted mb-2">{{ Str::limit($s->titulo_subasta, 80) }}</p>
                            <p class="mb-0 fw-bold">{{ number_format((float) $s->precioActual(), 2, ',', '.') }} Bs</p>
                            <p class="small text-muted mb-0">
                                @if(!$s->finalizada)
                                    Cierra: {{ $s->termina_en->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                                @else
                                    Cerrada {{ optional($s->cerrada_en)->format('d/m/Y H:i') ?? '' }}
                                @endif
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
@endif

<style>
    .hover-shadow { transition: box-shadow .15s ease; }
    a:hover .hover-shadow { box-shadow: 0 .5rem 1rem rgba(0,0,0,.12) !important; }
    .object-fit-cover { object-fit: cover; width: 100%; height: 100%; }
</style>
@endsection
