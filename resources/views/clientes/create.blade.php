@extends('layouts.app')
@section('content')

<div class='container'>
    <h1>Crear Cliente</h1>
    <form action=" {{route('clientes.store')}}" method="POST">
        @csrf
        @include('clientes.form')
        <button class="btn btn-success">Crear Cliente</button>
    </form>
</div>
@endsection