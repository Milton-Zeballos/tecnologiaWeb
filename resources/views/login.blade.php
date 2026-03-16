@extends('app')

@section('contenido')
<h1 class="text-center">Tecnologia Web</h1>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5"> <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-body p-4 p-md-5"> <h2 class="text-center text-black mb-4 fw-bold">Iniciar Sesión</h2>

                        @if($errors->any())
                            <div class="alert alert-danger p-2 mb-4">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="/" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label text-muted fw-semibold">Correo electrónico</label>
                                <input type="email" class="form-control form-control-lg bg-light" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@gmail.com">
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label text-muted fw-semibold">Contraseña</label>
                                <input type="password" class="form-control form-control-lg bg-light" id="password" name="password" required placeholder="••••••••">
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">Ingresar al Sistema</button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection