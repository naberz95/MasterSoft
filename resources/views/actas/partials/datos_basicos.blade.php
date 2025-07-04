<form action="{{ route('actas.store') }}" method="POST">
    @csrf

    <div class="mb-3">
        <label for="tipo_id" class="form-label">Tipo de Acta</label>
        <select name="tipo_id" id="tipo_id" class="form-select" required>
            <option value="">Seleccione un tipo</option>
            @foreach($tipos as $tipo)
                <option value="{{ $tipo->id }}" {{ old('tipo_id') == $tipo->id ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
            @endforeach
        </select>
        @error('tipo_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="numero" class="form-label">Número</label>
        <input type="text" name="numero" id="numero" class="form-control" value="{{ $numeroAutomatico }}" readonly>
    </div>

    <div class="mb-3">
        <label for="fecha" class="form-label">Fecha</label>
        <input type="date" name="fecha" id="fecha" class="form-control" value="{{ old('fecha', date('Y-m-d')) }}" required>
        @error('fecha')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3 row">
        <div class="col">
            <label for="hora_inicio" class="form-label">Hora Inicio</label>
            <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" value="{{ old('hora_inicio') }}" required>
            @error('hora_inicio')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col">
            <label for="hora_fin" class="form-label">Hora Fin</label>
            <input type="time" name="hora_fin" id="hora_fin" class="form-control" value="{{ old('hora_fin') }}" required>
            @error('hora_fin')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="lugar" class="form-label">Lugar</label>
        <input type="text" name="lugar" id="lugar" class="form-control" value="{{ old('lugar') }}" required>
        @error('lugar')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="ciudad_id" class="form-label">Ciudad</label>
        <select name="ciudad_id" id="ciudad_id" class="form-select" required>
            <option value="">Seleccione una ciudad</option>
            @foreach($ciudades as $ciudad)
                <option value="{{ $ciudad->id }}" {{ old('ciudad_id') == $ciudad->id ? 'selected' : '' }}>
                    {{ $ciudad->nombre }} / {{ $ciudad->pais->nombre ?? '' }}
                </option>
            @endforeach
        </select>
        @error('ciudad_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="cliente_id" class="form-label">Cliente</label>
        <select name="cliente_id" id="cliente_id" class="form-select" required>
            <option value="">Seleccione un cliente</option>
            @foreach($clientes as $cliente)
                <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                    {{ $cliente->nombre }} – Nit: {{ $cliente->nit }}
                </option>
            @endforeach
        </select>
        @error('cliente_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="facturable" class="form-label">Facturable</label>
        <select name="facturable" id="facturable" class="form-select" required>
            <option value="1" {{ old('facturable') == '1' ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ old('facturable') == '0' ? 'selected' : '' }}>No</option>
        </select>
        @error('facturable')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="proyecto_id" class="form-label">Proyecto</label>
        <select name="proyecto_id" id="proyecto_id" class="form-select" required>
            <option value="">Seleccione un proyecto</option>
            @foreach($proyectos as $proyecto)
                <option value="{{ $proyecto->id }}" {{ old('proyecto_id') == $proyecto->id ? 'selected' : '' }}>
                    {{ $proyecto->nombre }}
                </option>
            @endforeach
        </select>
        @error('proyecto_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="firmante_cliente_id" class="form-label">Firmante Cliente</label>
        <select name="firmante_cliente_id" id="firmante_cliente_id" class="form-select" required>
            <option value="">Seleccione un firmante</option>
            @foreach($personas as $persona)
                <option value="{{ $persona->id }}" {{ old('firmante_cliente_id') == $persona->id ? 'selected' : '' }}>
                    {{ $persona->nombre }}
                </option>
            @endforeach
        </select>
        @error('firmante_cliente_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="firmante_gp_id" class="form-label">Firmante Gestión y Proyectos</label>
        <select name="firmante_gp_id" id="firmante_gp_id" class="form-select" required>
            <option value="">Seleccione un firmante</option>
            @foreach($personas as $persona)
                <option value="{{ $persona->id }}" {{ old('firmante_gp_id') == $persona->id ? 'selected' : '' }}>
                    {{ $persona->nombre }}
                </option>
            @endforeach
        </select>
        @error('firmante_gp_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Guardar y Continuar</button>
</form>
