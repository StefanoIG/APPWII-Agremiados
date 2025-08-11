@extends('adminlte::page')

@section('title', 'Gestión de Deudas de Usuarios')

@section('content_header')
    <div class="row">
        <div class="col-sm-6">
            <h1><i class="fas fa-money-bill-wave"></i> Gestión de Deudas de Usuarios</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Gestión de Deudas</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <!-- Tarjetas de resumen -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $pendingDebts }}</h3>
                    <p>Deudas Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $pendingApproval }}</h3>
                    <p>Pendientes de Aprobación</p>
                </div>
                <div class="icon">
                    <i class="fas fa-user-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $overdueDebts }}</h3>
                    <p>Deudas Vencidas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $paidDebts }}</h3>
                    <p>Deudas Pagadas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-list"></i> Todas las Deudas de Usuarios</h3>
                    <div class="card-tools">
                        <div class="btn-group" role="group">
                            <a href="{{ route('user-debts.admin') }}" 
                               class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                Todas
                            </a>
                            <a href="{{ route('user-debts.admin', ['status' => 'pending']) }}" 
                               class="btn {{ request('status') == 'pending' ? 'btn-warning' : 'btn-outline-warning' }} btn-sm">
                                Pendientes
                            </a>
                            <a href="{{ route('user-debts.admin', ['status' => 'pending_approval']) }}" 
                               class="btn {{ request('status') == 'pending_approval' ? 'btn-info' : 'btn-outline-info' }} btn-sm">
                                Por Aprobar
                            </a>
                            <a href="{{ route('user-debts.admin', ['status' => 'overdue']) }}" 
                               class="btn {{ request('status') == 'overdue' ? 'btn-danger' : 'btn-outline-danger' }} btn-sm">
                                Vencidas
                            </a>
                            <a href="{{ route('user-debts.admin', ['status' => 'paid']) }}" 
                               class="btn {{ request('status') == 'paid' ? 'btn-success' : 'btn-outline-success' }} btn-sm">
                                Pagadas
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    @if($debts->count() > 0)
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Usuario</th>
                                    <th>Período</th>
                                    <th>Monto</th>
                                    <th>Fecha Vencimiento</th>
                                    <th>Estado</th>
                                    <th>Fecha Pago</th>
                                    <th>Método Pago</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($debts as $debt)
                                    <tr>
                                        <td>
                                            <strong>{{ $debt->user->name }}</strong><br>
                                            <small class="text-muted">{{ $debt->user->email }}</small>
                                        </td>
                                        <td>{{ $debt->monthlyCut->description }}</td>
                                        <td>
                                            <span class="badge badge-info">${{ number_format($debt->amount, 2) }}</span>
                                        </td>
                                        <td>{{ $debt->due_date->format('d/m/Y') }}</td>
                                        <td>
                                            @switch($debt->status)
                                                @case('pending')
                                                    <span class="badge badge-warning">Pendiente</span>
                                                    @break
                                                @case('paid')
                                                    <span class="badge badge-success">Pagado</span>
                                                    @break
                                                @case('overdue')
                                                    <span class="badge badge-danger">Vencido</span>
                                                    @break
                                                @case('pending_approval')
                                                    <span class="badge badge-info">Por Aprobar</span>
                                                    @break
                                            @endswitch
                                        </td>
                                        <td>
                                            {{ $debt->paid_at ? $debt->paid_at->format('d/m/Y H:i') : '-' }}
                                        </td>
                                        <td>
                                            {{ $debt->payment_method ? ucfirst($debt->payment_method) : '-' }}
                                        </td>
                                        <td>
                                            @if($debt->status === 'pending_approval')
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-success btn-sm" 
                                                            onclick="approvePayment({{ $debt->id }})">
                                                        <i class="fas fa-check"></i> Aprobar
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm" 
                                                            onclick="rejectPayment({{ $debt->id }})">
                                                        <i class="fas fa-times"></i> Rechazar
                                                    </button>
                                                </div>
                                                @if($debt->payment_receipt)
                                                    <br><small>
                                                        <a href="{{ asset('storage/' . $debt->payment_receipt) }}" 
                                                           target="_blank" class="text-info">
                                                            <i class="fas fa-file"></i> Ver Comprobante
                                                        </a>
                                                    </small>
                                                @endif
                                            @elseif($debt->payment_receipt)
                                                <a href="{{ asset('storage/' . $debt->payment_receipt) }}" 
                                                   target="_blank" class="btn btn-info btn-sm">
                                                    <i class="fas fa-file"></i> Ver Comprobante
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center p-4">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h4>No hay deudas registradas</h4>
                            <p class="text-muted">No se encontraron deudas de usuarios en el sistema.</p>
                        </div>
                    @endif
                </div>
                
                @if($debts->hasPages())
                    <div class="card-footer clearfix">
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <div class="dataTables_info">
                                    Mostrando {{ $debts->firstItem() }} a {{ $debts->lastItem() }} de {{ $debts->total() }} resultados
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers float-right">
                                    {{ $debts->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function approvePayment(debtId) {
    Swal.fire({
        title: '¿Aprobar pago?',
        text: "Esta acción marcará la deuda como pagada",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, aprobar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/deudas/${debtId}/aprobar`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire(
                        'Aprobado',
                        'El pago ha sido aprobado exitosamente',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire(
                        'Error',
                        'Hubo un problema al aprobar el pago',
                        'error'
                    );
                }
            });
        }
    });
}

function rejectPayment(debtId) {
    Swal.fire({
        title: '¿Rechazar pago?',
        text: "Esta acción devolverá la deuda a estado pendiente",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, rechazar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/admin/deudas/${debtId}/rechazar`,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire(
                        'Rechazado',
                        'El pago ha sido rechazado',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire(
                        'Error',
                        'Hubo un problema al rechazar el pago',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
@stop
