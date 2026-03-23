@extends('layouts.app')
@section('content')

<div class='container'>
    <h1>Crear Producto</h1>
    <form action=" {{route('productos.store')}}" method="POST">
        @csrf
        @include('productos.form')
        <button class="btn btn-success">Crear Producto</button>
    </form>
</div>
@endsection