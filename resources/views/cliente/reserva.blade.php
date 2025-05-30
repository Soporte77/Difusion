@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Nueva Difusión</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Difusión</a></li>
                    <li class="breadcrumb-item active">Nueva Difusión</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">Crear Nueva Difusión</h4>
            </div>
            <div class="card-body">
                <form class="row gy-1" id="reservationForm" method="POST" action="{{ route('reservations.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="col-xxl-3 col-md-6">
                        <div>
                            <label for="user" class="form-label">{{ __('Usuario') }}</label>
                            <input id="user" type="text" class="form-control" value="{{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}" readonly>
                            <input type="hidden" id="user_id" name="user_id" value="{{ Auth::user()->id }}">
                        </div>
                    </div>

                    {{-- Área --}}
                    <div class="col-md-4">
                        <label for="area_id" class="form-label">Área</label>
                        <select id="area_id" name="area_id" class="form-select" required>
                            <option value="">Seleccionar área</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->nombre_area }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Coordinador --}}
                    <div class="col-md-4">
                        <label for="consulta_id" class="form-label">Coordinador</label>
                        <select id="consulta_id" name="consulta_id" class="form-select" required>
                            <option value="">Seleccionar coordinador</option>
                        </select>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div>
                            <label for="reservation_date" class="form-label">{{ __('Fecha de Reserva') }}</label>
                            <input type="date" class="form-control @error('reservation_date') is-invalid @enderror" id="reservation_date" name="reservation_date" value="{{ old('reservation_date') }}" required>
                            @error('reservation_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div>
                            <label for="start_time" class="form-label">{{ __('Hora de Inicio') }}</label>
                            <select class="form-select @error('start_time') is-invalid @enderror" id="start_time" name="start_time" required>
                                <option value="">Seleccionar una hora</option>
                                <option value="09:00">09:00</option>
                                <option value="10:00">10:00</option>
                                <option value="11:00">11:00</option>
                                <option value="12:00">12:00</option>
                                <option value="13:00">13:00</option>
                                <option value="14:00">14:00</option>
                                <option value="15:00">15:00</option>
                                <option value="16:00">16:00</option>
                            </select>
                            @error('start_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div>
                            <label for="end_time" class="form-label">{{ __('Hora Fin') }}</label>
                            <input type="text" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" readonly>
                            @error('end_time')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div>
                            <label for="foto_evidencia" class="form-label">{{ __('Foto Evidencia') }}</label>
                            <input type="file" id="foto_evidencia" name="foto_evidencia" class="form-control pe-5 @error('foto_evidencia') is-invalid @enderror">
                            @error('foto_evidencia')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message}}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div style="margin-top: 27px">
                            <button type="submit" class="btn btn-primary">Crear Difusión</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Establecer fecha mínima en campo de reserva
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('reservation_date').setAttribute('min', today);

    // Calcular hora de fin
    document.getElementById('start_time').addEventListener('change', function () {
        const startTime = this.value;
        if (startTime) {
            const startDate = new Date(`1970-01-01T${startTime}:00`);
            startDate.setHours(startDate.getHours() + 1);
            const endTime = startDate.toTimeString().slice(0, 5);
            document.getElementById('end_time').value = endTime;
        } else {
            document.getElementById('end_time').value = "";
        }
    });

    // Cargar coordinadores por área vía AJAX
    document.getElementById('area_id').addEventListener('change', function () {
        const areaId = this.value;
        const coordinadorSelect = document.getElementById('consulta_id');
        coordinadorSelect.innerHTML = '<option value="">Cargando coordinadores...</option>';

        if (areaId) {
            fetch(`/coordinadores/${areaId}`)
                .then(response => response.json())
                .then(data => {
                    coordinadorSelect.innerHTML = '<option value="">Seleccionar coordinador</option>';
                    data.forEach(coord => {
                        const option = document.createElement('option');
                        option.value = coord.id;
                        option.textContent = `${coord.nombres} ${coord.apellidos}`;
                        coordinadorSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error al cargar coordinadores:', error);
                    coordinadorSelect.innerHTML = '<option value="">Error al cargar</option>';
                });
        } else {
            coordinadorSelect.innerHTML = '<option value="">Seleccionar coordinador</option>';
        }
    });
</script>
@endpush
