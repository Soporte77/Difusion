@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Listado de Pagos</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Pagos</a></li>
                    <li class="breadcrumb-item active">Lista de Pagos</li>
                </ol>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Pagos</h5>
            </div>
            <div class="card-body">
                <a href="{{ route('cliente.reserva') }}" class="btn btn-primary waves-effect waves-light">Nueva Difusión</a>
                <br>
                <br>
                <table id="paymentsTable" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                    <thead>
                        <tr>
                            <th>Consultor</th>
                            <th>Fecha de Difusión</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
                            <th>Transaction ID</th>
                            <th>Payer ID</th>
                            <th>Payer Email</th>
                            <th>Monto Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                        <tr>
                            <td>{{ $payment->reservation->consultant->nombres }} {{ $payment->reservation->consultant->apellidos }}</td>
                            <td>{{ $payment->reservation->reservation_date }}</td>
                            <td>{{ $payment->reservation->start_time }}</td>
                            <td>{{ $payment->reservation->end_time }}</td>
                            <td>{{ $payment->transaction_id }}</td>
                            <td>{{ $payment->payer_id }}</td>
                            <td>{{ $payment->payer_email }}</td>
                            <td>{{ $payment->reservation->total_amount }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        $('#paymentsTable').DataTable();
    });
</script>
@endpush
