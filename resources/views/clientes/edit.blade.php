<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-5">

                    <h2 class="text-center mb-4">Editar Cliente</h2>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('clientes.update', $cliente) }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')

                        <!-- NIT -->
                        <div class="mb-3">
                            <label for="nit" class="form-label">NIT</label>
                            <input type="text" name="nit" id="nit" class="form-control @error('nit') is-invalid @enderror" value="{{ old('nit', $cliente->nit) }}">
                            @error('nit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dígito -->
                        <div class="mb-3">
                            <label for="digito_verificacion" class="form-label">Dígito de Verificación</label>
                            <input type="text" name="digito_verificacion" id="digito_verificacion" class="form-control @error('digito_verificacion') is-invalid @enderror" value="{{ old('digito_verificacion', $cliente->digito_verificacion) }}">
                            @error('digito_verificacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $cliente->nombre) }}">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" name="direccion" id="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion', $cliente->direccion) }}">
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Logo actual -->
                        <div class="mb-3">
                            <label class="form-label">Logo actual:</label><br>
                            @if ($cliente->logo_cliente)
                                <img src="{{ asset('storage/logos/' . $cliente->logo_cliente) }}" class="img-thumbnail mb-2" style="max-height: 100px;">
                            @else
                                <p class="text-muted"><em>No hay logo subido</em></p>
                            @endif
                        </div>

                        <!-- Nuevo logo -->
                        <div class="mb-4">
                            <label for="logo_cliente" class="form-label">Nuevo Logo (opcional)</label>
                            <input type="file" name="logo_cliente" id="logo_cliente" class="form-control">
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Actualizar Cliente
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
