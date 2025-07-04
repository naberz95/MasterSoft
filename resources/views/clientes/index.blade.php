<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Lista de Clientes</h2>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm">
                ⬅️ Volver al Dashboard
            </a>
        <a href="{{ route('clientes.create') }}" class="btn btn-success rounded-pill">
            ➕ Adicionar Cliente
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @forelse ($clientes as $cliente)
        <div class="card mb-4 shadow-sm border-0 rounded-4">
            <div class="card-body">
                <h5 class="card-title mb-2">{{ $cliente->nombre }}</h5>
                <p class="card-text mb-2"><strong>NIT:</strong> {{ $cliente->nit }}</p>

                @if ($cliente->logo_cliente)
                    <div class="mb-3">
                        <img src="{{ asset('storage/logos/' . $cliente->logo_cliente) }}"
                             class="img-thumbnail"
                             style="max-height: 100px;"
                             alt="Logo de {{ $cliente->nombre }}">
                    </div>
                @endif

                <div class="d-flex gap-2">

                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-outline-primary btn-sm">
                        ✏️ Editar
                    </a>

                    <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este cliente?')">
                        @csrf
                        @method('DELETE')
                        <!--
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            🗑️ Eliminar
                        </button>-->
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            No hay clientes registrados aún.
        </div>
    @endforelse
</div>

</body>
</html>
