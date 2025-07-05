@extends('layouts.auth')

@section('content')
<div class="auth-header">
    <div class="logo-container">
        <i class="fas fa-file-alt"></i>
    </div>
    <h1>MASTERSOFT</h1>
    <p>Sistema de Gestión de Actas</p>
</div>

<div class="auth-body">
    <!-- Session Status -->
    @if (session('status'))
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle me-2"></i>{{ session('status') }}
        </div>
    @endif

    <!-- Errores de validación -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Error:</strong> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">
                <i class="fas fa-envelope me-1"></i> Correo Electrónico
            </label>
            <input id="email" type="email" 
                   class="form-control @error('email') is-invalid @enderror" 
                   name="email" value="{{ old('email') }}" 
                   required autocomplete="email" autofocus
                   placeholder="correo@ejemplo.com">
            @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">
                <i class="fas fa-lock me-1"></i> Contraseña
            </label>
            <input id="password" type="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   name="password" required autocomplete="current-password"
                   placeholder="Contraseña">
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="mb-4 form-check">
            <input class="form-check-input" type="checkbox" name="remember" 
                   id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">
                Recordarme
            </label>
        </div>

        <!-- Submit Button -->
        <div class="d-grid mb-3">
            <button type="submit" class="btn btn-primary btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>
                Iniciar Sesión
            </button>
        </div>

        <!-- Forgot Password -->
        @if (Route::has('password.request'))
            <div class="text-center">
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    <i class="fas fa-key me-1"></i>
                    ¿Olvidaste tu contraseña?
                </a>
            </div>
        @endif
    </form>
</div>

<div class="auth-footer">
    <small class="text-muted">
        <i class="fas fa-shield-alt me-1"></i>
        Sistema seguro y confiable
    </small>
</div>
@endsection