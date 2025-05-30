@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Editar Asignación</h2>

    <form action="{{ route('cordinador_usuarios.update', $asignacion->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="area-select" class="form-label">Área</label>
            <select id="area-select" name="area_id" class="form-select" required>
                <option value="">Seleccione un área</option>
                @foreach($areas as $area)
                    <option value="{{ $area->id }}" {{ $asignacion->area_id == $area->id ? 'selected' : '' }}>
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
                @foreach($coordinadores as $coord)
                    <option value="{{ $coord->id }}" {{ $asignacion->cordinador_id == $coord->id ? 'selected' : '' }}>
                        {{ $coord->nombres }} {{ $coord->apellidos }}
                    </option>
                @endforeach
            </select>
            @error('cordinador_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="user_id" class="form-label">Usuario</label>
            <select name="user_id" class="form-select" required>
                <option value="">Seleccione un usuario</option>
                @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}" {{ $asignacion->user_id == $usuario->id ? 'selected' : '' }}>
                        {{ $usuario->nombres }} {{ $usuario->apellidos }}
                    </option>
                @endforeach
            </select>
            @error('user_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('cordinador_usuarios.index') }}" class="btn btn-secondary ms-2">Cancelar</a>
    </form>
</div>

<script>
function cargarCoordinadores(areaId, selectedId = null) {
    const select = document.getElementById('coordinador-select');
    select.innerHTML = '<option>Cargando coordinadores...</option>';

    if(!areaId) {
        select.innerHTML = '<option value="">Seleccione un coordinador</option>';
        return;
    }

    fetch(`/coordinadores-por-area/${areaId}`)
        .then(res => res.json())
        .then(data => {
            select.innerHTML = '<option value="">Seleccione un coordinador</option>';
            data.forEach(coord => {
                const selected = selectedId == coord.id ? 'selected' : '';
                select.innerHTML += `<option value="${coord.id}" ${selected}>${coord.nombres} ${coord.apellidos}</option>`;
            });
        })
        .catch(() => {
            select.innerHTML = '<option value="">Error al cargar coordinadores</option>';
        });
}

document.getElementById('area-select').addEventListener('change', function() {
    cargarCoordinadores(this.value);
});

// Al cargar la página, carga coordinadores con el valor actual
window.onload = function() {
    const areaId = document.getElementById('area-select').value;
    const selectedCoordId = "{{ $asignacion->cordinador_id }}";
    if (areaId) {
        cargarCoordinadores(areaId, selectedCoordId);
    }
};
</script>
@endsection
