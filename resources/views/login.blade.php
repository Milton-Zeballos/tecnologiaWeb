@extends('app')

@section('contenido')
<h1 class="text-center">Tecnologia Web</h1>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5"> <div class="card shadow-sm border-0 rounded-lg">
                    <div class="card-body p-4 p-md-5">
                        <ul class="nav nav-pills nav-fill mb-4" id="authTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active fw-bold" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">Iniciar Sesión</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link fw-bold" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="false">Registrarse</button>
                            </li>
                        </ul>

                        <div class="tab-content" id="authTabContent">
                            <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                                <h2 class="text-center text-black mb-4 fw-bold">Bienvenido</h2>
                                @if($errors->any())
                                    <div class="alert alert-danger p-2 mb-4">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('login') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label text-muted fw-semibold">Correo electrónico</label>
                                        <input type="email" class="form-control bg-light" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@gmail.com">
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="form-label text-muted fw-semibold">Contraseña</label>
                                        <input type="password" class="form-control bg-light" id="password" name="password" required placeholder="••••••••">
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 fw-bold">Ingresar</button>
                                </form>
                            </div>

                            <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                                <h2 class="text-center text-black mb-4 fw-bold">Crear Cuenta</h2>
                                <form action="{{ route('register') }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="name" class="form-label text-muted fw-semibold">Nombre</label>
                                        <input type="text" class="form-control bg-light" id="name" name="name" value="{{ old('name') }}" required placeholder="Tu Nombre">
                                    </div>
                                    <div class="mb-3">
                                        <label for="reg_email" class="form-label text-muted fw-semibold">Correo electrónico</label>
                                        <input type="email" class="form-control bg-light" id="reg_email" name="email" value="{{ old('email') }}" required placeholder="tucorreo@ejemplo.com">
                                    </div>
                                    <div class="mb-3">
                                        <label for="reg_password" class="form-label text-muted fw-semibold">Contraseña</label>
                                        <input type="password" class="form-control bg-light" id="reg_password" name="password" required placeholder="••••••••">
                                    </div>
                                    <div class="mb-4">
                                        <label for="password_confirmation" class="form-label text-muted fw-semibold">Confirmar</label>
                                        <input type="password" class="form-control bg-light" id="password_confirmation" name="password_confirmation" required placeholder="••••••••">
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 fw-bold">Registrarse</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection