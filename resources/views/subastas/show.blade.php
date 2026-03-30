@extends('layouts.app')

@section('title', $subasta->nombre_producto.' | Ganatelo.com')

@section('content')
@php
    $ahora = now();
    $activa = $subasta->enVentanaDeSubasta($ahora);
    $puedePujar = $subasta->puedeOfertarUsuario(auth()->id());
@endphp

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('subastas.index') }}">Subastas</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $subasta->nombre_producto }}</li>
    </ol>
</nav>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if ($errors->has('puja'))
    <div class="alert alert-danger">{{ $errors->first('puja') }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm overflow-hidden">
            @if($subasta->media_path)
                @if($subasta->esVideo())
                    <video class="w-100" controls src="{{ $subasta->urlMedia() }}"></video>
                @else
                    <img src="{{ $subasta->urlMedia() }}" class="w-100" alt="{{ $subasta->nombre_producto }}">
                @endif
            @else
                <div class="ratio ratio-4x3 bg-secondary-subtle d-flex align-items-center justify-content-center text-muted">Sin archivo</div>
            @endif
        </div>
    </div>
    <div class="col-lg-6">
        <span class="badge {{ $subasta->finalizada ? 'bg-dark' : ($activa ? 'bg-success' : 'bg-secondary') }} mb-2">
            @if($subasta->finalizada) Cerrada @elseif($activa) Abierta @else Fuera de plazo @endif
        </span>
        <h1 class="h3">{{ $subasta->nombre_producto }}</h1>
        <p class="lead text-muted mb-2">{{ $subasta->titulo_subasta }}</p>
        <p class="text-muted small">Vende: {{ $subasta->vendedor->name }}</p>

        <hr>

        <h2 class="h6 text-muted">Descripción</h2>
        <p class="mb-4" style="white-space: pre-wrap;">{{ $subasta->descripcion }}</p>

        <div class="p-3 bg-white rounded border mb-4">
            <p class="mb-1"><strong>Puja actual</strong></p>
            <p class="h4 mb-0">{{ number_format((float) $subasta->precioActual(), 2, ',', '.') }} Bs</p>
            @if(!$subasta->ultima_oferta_monto)
                <p class="small text-muted mb-0">Sin pujas todavía (se muestra el precio inicial).</p>
            @endif
        </div>

        <div class="row g-2 small text-muted mb-4">
            <div class="col-6"><strong>Inicio</strong><br>{{ $subasta->empieza_en->format('d/m/Y H:i') }}</div>
            <div class="col-6"><strong>Cierra</strong><br>{{ $subasta->termina_en->format('d/m/Y H:i') }}</div>
            <div class="col-6"><strong>Extra por puja</strong><br>+{{ $subasta->extension_por_oferta_minutos }} min</div>
            <div class="col-6"><strong>Piso de venta</strong><br>{{ number_format((float) $subasta->precio_minimo, 2, ',', '.') }} Bs</div>
        </div>

        @if($subasta->finalizada)
            <div class="alert alert-secondary">
                @if($subasta->ganador_user_id)
                    Ganó: {{ $subasta->ganador->name ?? 'Usuario #'.$subasta->ganador_user_id }}
                    @if($subasta->ultima_oferta_monto)
                        · {{ number_format((float) $subasta->ultima_oferta_monto, 2, ',', '.') }} Bs
                    @endif
                @else
                    Cerró sin comprador (no se llegó al precio mínimo acordado).
                @endif
            </div>
        @elseif($puedePujar)
            @if($demoSinBilletera)
                <p class="small text-muted mb-2">En esta versión las pujas no usan saldo ni pagos.</p>
            @endif
            <p class="fw-semibold mb-2">Sumar a la puja:</p>
            <div class="d-flex flex-wrap gap-2">
                <form action="{{ route('subastas.pujar', $subasta->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="incremento" value="10">
                    <button type="submit" class="btn btn-lg btn-outline-dark">+10 Bs</button>
                </form>
                <form action="{{ route('subastas.pujar', $subasta->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="incremento" value="50">
                    <button type="submit" class="btn btn-lg btn-warning">+50 Bs</button>
                </form>
                <form action="{{ route('subastas.pujar', $subasta->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="incremento" value="100">
                    <button type="submit" class="btn btn-lg btn-dark">+100 Bs</button>
                </form>
            </div>
        @elseif(auth()->id() === (int) $subasta->user_id)
            <p class="text-muted">Esta publicación es tuya; no aplican pujas propias.</p>
        @else
            <p class="text-muted">No se puede pujar (aún no abre o ya cerró).</p>
        @endif
    </div>
</div>
@endsection
