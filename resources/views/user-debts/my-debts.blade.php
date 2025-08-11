@extends('adminlte::page')

@section('title', 'Mis Deudas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Mis Deudas</h1>
        @if($stats['total_pending'] > 0)
            <span class="badge badge-danger badge-lg">
                Total pendiente: ${{ number_format($stats['total_pending'], 2) }}
            </span>
        @endif
    </div>
@stop

@section('content')
    <!-- Estadísticas -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['pending_count'] }}</h3>
                    <p>Deudas Pendientes</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['overdue_count'] }}</h3>
                    <p>Deudas Vencidas</p>
                </div>
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>${{ number_format($stats['total_pending'], 2) }}</h3>
                    <p>Total a Pagar</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
    </div>

    @if($debts->count() > 0)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-list mr-2"></i>
                    Historial de Deudas
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Período</th>
                                <th>Monto</th>
                                <th>Fecha Vencimiento</th>
                                <th>Estado</th>
                                <th>Fecha Pago</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($debts as $debt)
                                <tr class="{{ $debt->status === 'overdue' ? 'table-danger' : '' }}">
                                    <td>
                                        <strong>{{ $debt->monthlyCut->name }}</strong><br>
                                        <small class="text-muted">{{ $debt->monthlyCut->description }}</small>
                                    </td>
                                    <td>
                                        <span class="font-weight-bold text-primary">
                                            ${{ number_format($debt->amount, 2) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="{{ $debt->status === 'overdue' ? 'text-danger' : '' }}">
                                            {{ $debt->due_date->format('d/m/Y') }}
                                        </span>
                                        @if($debt->status === 'overdue')
                                            <br><small class="text-danger">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Vencida hace {{ $debt->due_date->diffForHumans() }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($debt->status === 'pending')
                                            <span class="badge badge-warning">Pendiente</span>
                                        @elseif($debt->status === 'paid')
                                            <span class="badge badge-success">Pagada</span>
                                        @elseif($debt->status === 'overdue')
                                            <span class="badge badge-danger">Vencida</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($debt->paid_at)
                                            {{ $debt->paid_at->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($debt->status === 'pending' || $debt->status === 'overdue')
                                            <button type="button" class="btn btn-primary btn-sm" 
                                                    data-toggle="modal" data-target="#paymentModal{{ $debt->id }}">
                                                <i class="fas fa-credit-card"></i> Pagar
                                            </button>
                                        @elseif($debt->status === 'paid' && $debt->receipt_url)
                                            <a href="{{ $debt->receipt_url }}" target="_blank" class="btn btn-info btn-sm">
                                                <i class="fas fa-receipt"></i> Ver Comprobante
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info text-center">
            <h4><i class="icon fas fa-info"></i> Sin deudas</h4>
            No tienes deudas registradas en el sistema.
        </div>
    @endif

    <!-- Modales de pago -->
    @foreach($debts->where('status', '!=', 'paid') as $debt)
        <div class="modal fade" id="paymentModal{{ $debt->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('user-debts.pay', $debt) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <h4 class="modal-title">
                                <i class="fas fa-credit-card mr-2"></i>
                                Pagar Deuda - {{ $debt->monthlyCut->name }}
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <strong>Monto a pagar:</strong> ${{ number_format($debt->amount, 2) }}<br>
                                <strong>Vencimiento:</strong> {{ $debt->due_date->format('d/m/Y') }}
                            </div>
                            
                            <div class="form-group">
                                <label for="payment_method">Método de Pago</label>
                                <select name="payment_method" id="payment_method" class="form-control" required>
                                    <option value="">Seleccionar método</option>
                                    <option value="transferencia">Transferencia Bancaria</option>
                                    <option value="deposito">Depósito Bancario</option>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="receipt">Comprobante de Pago</label>
                                <input type="file" name="receipt" id="receipt" class="form-control-file" 
                                       accept=".jpg,.jpeg,.png,.pdf" required>
                                <small class="form-text text-muted">
                                    Formatos permitidos: JPG, PNG, PDF. Máximo 2MB.
                                </small>
                            </div>
                            
                            <div class="form-group">
                                <label for="notes">Notas (opcional)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" 
                                         placeholder="Información adicional sobre el pago..."></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload mr-2"></i>Subir Comprobante
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@stop

@section('css')
    <style>
        .table-danger {
            background-color: #f8d7da !important;
        }
        .small-box .icon {
            font-size: 50px;
        }
        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
    </style>
@stop

@section('js')
    <script>
        // Validación de archivo
        document.querySelectorAll('input[type="file"]').forEach(function(input) {
            input.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const fileSize = file.size / 1024 / 1024; // MB
                    if (fileSize > 2) {
                        alert('El archivo es demasiado grande. Máximo 2MB permitido.');
                        this.value = '';
                    }
                }
            });
        });
    </script>
@stop
