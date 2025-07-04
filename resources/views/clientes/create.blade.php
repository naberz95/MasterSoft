<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow border-0 rounded-4">
                <div class="card-body p-5">

                    <h2 class="text-center mb-4">Crear Cliente</h2>

                    <form action="{{ route('clientes.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf

                        <!-- NIT -->
                        <div class="mb-3">
                            <label for="nit" class="form-label">NIT</label>
                            <input type="text" name="nit" id="nit" class="form-control @error('nit') is-invalid @enderror" value="{{ old('nit') }}">
                            @error('nit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dígito de verificación -->
                        <div class="mb-3">
                            <label for="digito_verificacion" class="form-label">Dígito de Verificación</label>
                            <input type="text" name="digito_verificacion" id="digito_verificacion" class="form-control @error('digito_verificacion') is-invalid @enderror" value="{{ old('digito_verificacion') }}">
                            @error('digito_verificacion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Nombre -->
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" name="direccion" id="direccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}">
                            @error('direccion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Logo -->
                        <div class="mb-4">
                            <label for="logo_cliente" class="form-label">Logo (opcional)</label>
                            <input type="file" name="logo_cliente" id="logo_cliente" class="form-control">
                        </div>

                        <!-- Botón -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg rounded-pill">
                                Guardar Cliente
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
