@extends('layouts.app')
@section('content')

<div class='container'>
    <h1>Editar Cliente</h1>
    <form action="{{ route('clientes.update', $cliente) }}" method="POST">
        @csrf
        @method('PUT')
        @include('clientes.form')
        <button class="btn btn-primary">Actualizar Cliente</button>
    </form>
</div>
@endsection