@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Crear Nueva Asignación</h2>

    <form action="{{ route('cordinador_usuarios.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="area-select" class="form-label">Área</label>
            <select id="area-select" name="area_id" class="form-select" required>
                <option value="">Seleccione un área</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                        {{ $area->nombre_area }}
                    </option>
                @endforeach
            </select>
            @error('area_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="coordinador-select" class="form-label">Coordinador</label>
            <select id="coordinador-select" name="cordinador_id" class="form-select" required>
                <option value="">Seleccione un coordinador</option>
                <!-- Aquí se cargan coordinadores por JS -->
            </select>
            @error('cordinador_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">Usuario</label>
            <select name="user_id" class="form-select" required>
                <option value="">Seleccione un usuario</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                        {{ $usuario->nombres }} {{ $usuario->apellidos }}
                    </option>
                @endforeach
            </select>
            @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="{{ route('cordinador_usuarios.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>

<script>
document.getElementById('area-select').addEventListener('change', function() {
    let areaId = this.value;
    let coordinadorSelect = document.getElementById('coordinador-select');
    coordinadorSelect.innerHTML = '<option>Cargando coordinadores...</option>';

    if(!areaId) {
        coordinadorSelect.innerHTML = '<option value="">Seleccione un coordinador</option>';
        return;
    }

    fetch(`/coordinadores-por-area/${areaId}`)
        .then(response => response.json())
        .then(data => {
            coordinadorSelect.innerHTML = '<option value="">Seleccione un coordinador</option>';
            data.forEach(coord => {
                coordinadorSelect.innerHTML += `<option value="${coord.id}">${coord.nombres} ${coord.apellidos}</option>`;
            });
        })
        .catch(() => {
            coordinadorSelect.innerHTML = '<option value="">Error al cargar coordinadores</option>';
        });
});
</script>
@endsection
