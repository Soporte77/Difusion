@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Asignaciones</h2>
    <a href="{{ route('cordinador_usuarios.create') }}" class="btn btn-primary mb-3">Nueva Asignación</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Área</th>
                <th>Coordinador</th>
                <th>Usuario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($asignaciones as $a)
            <tr>
                <td>{{ $a->area->nombre_area }}</td>
                <td>{{ $a->cordinador->nombres ?? 'N/A' }} {{ $a->cordinador->apellidos ?? '' }}</td>
                <td>{{ $a->user->nombres ?? 'N/A' }} {{ $a->user->apellidos ?? '' }}</td>
                <td>
                    <a href="{{ route('cordinador_usuarios.edit', $a->id) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('cordinador_usuarios.destroy', $a->id) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button onclick="return confirm('¿Eliminar esta asignación?')" class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4">No hay asignaciones.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
