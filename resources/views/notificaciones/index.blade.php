@extends('layouts.app')

@section('title', 'Avisos | Ganatelo.com')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Avisos</h1>
    @if(auth()->user()->unreadNotifications->isNotEmpty())
        <form action="{{ route('notificaciones.leidas-todas') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-secondary btn-sm">Marcar todo leído</button>
        </form>
    @endif
</div>

@if($notificaciones->isEmpty())
    <p class="text-muted">Nada nuevo por acá.</p>
@else
    <ul class="list-group shadow-sm">
        @foreach($notificaciones as $n)
            @php $data = $n->data; @endphp
            <li class="list-group-item d-flex flex-column flex-md-row justify-content-between gap-2 {{ $n->read_at ? '' : 'bg-light' }}">
                <div>
                    <p class="mb-1">{{ $data['mensaje'] ?? 'Movimiento en una subasta.' }}</p>
                    <p class="small text-muted mb-0">{{ $n->created_at->diffForHumans() }}</p>
                    @if(!empty($data['subasta_id']))
                        <a href="{{ route('subastas.show', $data['subasta_id']) }}" class="small">Abrir</a>
                    @endif
                </div>
                @if(!$n->read_at)
                    <form action="{{ route('notificaciones.leida', $n->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-primary">Marcar leída</button>
                    </form>
                @endif
            </li>
        @endforeach
    </ul>
    <div class="mt-3">{{ $notificaciones->links() }}</div>
@endif
@endsection
