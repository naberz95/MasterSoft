@extends('layouts.auth')

@section('content')
<div class="auth-header">
    <div class="logo-container">
        <i class="fas fa-user-plus"></i>
    </div>
    <h1>MASTERSOFT</h1>
    <p>Crear Nueva Cuenta</p>
</div>

<div class="auth-body">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="form-label">
                <i class="fas fa-user me-1"></i> Nombre Completo
            </label>
            <input id="name" type="text" 
                   class="form-control @error('name') is-invalid @enderror" 
                   name="name" value="{{ old('name') }}" 
                   required autocomplete="name" autofocus
                   placeholder="Tu nombre completo">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-1"></i> Correo Electrónico
            </label>
            <input id="email" type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" 
                   required autocomplete="email"
                   placeholder="correo@ejemplo.com">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-1"></i> Contraseña
            </label>
            <input id="password" type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   name="password" required autocomplete="new-password"
                   placeholder="Contraseña">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password-confirm" class="form-label">
                <i class="fas fa-lock me-1"></i> Confirmar Contraseña
            </label>
            <input id="password-confirm" type="password" 
                   class="form-control" name="password_confirmation" 
                   required autocomplete="new-password"
                   placeholder="Confirma tu contraseña">
        </div>

        <!-- Submit Button -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-login">
                <i class="fas fa-user-plus me-2"></i>
                Registrarse
            </button>
        </div>
    </form>
</div>

<div class="auth-footer">
    <small class="text-muted">
        ¿Ya tienes cuenta?
        <a href="{{ route('login') }}" class="text-decoration-none ms-1">
            Iniciar Sesión
        </a>
    </small>
</div>
@endsection