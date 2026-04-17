@extends('layouts.app')

@section('title', 'Reporte de Pagos - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-credit-card me-2"></i>Reporte de Pagos
            </h1>
            <div class="btn-group">
                <a href="{{ route('reportes.exportar-pagos', request()->all()) }}" 
                   class="btn btn-success">
                    <i class="fas fa-file-excel me-2"></i>Exportar Excel
                </a>
                <a href="{{ route('reportes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver a Reportes
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card shadow mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>Filtros del Reporte
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('reportes.pagos') }}" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" 
                               name="fecha_inicio" 
                               id="fecha_inicio" 
                               class="form-control" 
                               value="{{ $fechaInicio }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" 
                               name="fecha_fin" 
                               id="fecha_fin" 
                               class="form-control" 
                               value="{{ $fechaFin }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="metodo_pago" class="form-label">Método de Pago</label>
                        <select name="metodo_pago" id="metodo_pago" class="form-select">
                            <option value="todos" {{ $metodoPago == 'todos' ? 'selected' : '' }}>Todos</option>
                            <option value="efectivo" {{ $metodoPago == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                            <option value="tarjeta" {{ $metodoPago == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                            <option value="transferencia" {{ $metodoPago == 'transferencia' ? 'selected' : '' }}>Transferencia</option>
                            <option value="bizum" {{ $metodoPago == 'bizum' ? 'selected' : '' }}>Bizum</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $reporte['pagos']->count() }}</h4>
                <small>Total Pagos</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ number_format($reporte['totales']['monto_total'], 2) }} €</h4>
                <small>Monto Total Cobrado</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">
                    {{ $reporte['pagos']->count() > 0 ? number_format($reporte['totales']['monto_total'] / $reporte['pagos']->count(), 2) : 0 }} €
                </h4>
                <small>Promedio por Pago</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Pagos por Método
                </h5>
            </div>
            <div class="card-body">
                @if(count($reporte['por_metodo']) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Método</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Monto Total</th>
                                    <th class="text-end">Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reporte['por_metodo'] as $metodo => $datos)
                                    <tr>
                                        <td>
                                            <strong class="text-capitalize">{{ $metodo }}</strong>
                                        </td>
                                        <td class="text-center">{{ $datos['cantidad'] }}</td>
                                        <td class="text-end">{{ number_format($datos['monto_total'], 2) }} €</td>
                                        <td class="text-end">
                                            {{ number_format(($datos['monto_total'] / $reporte['totales']['monto_total']) * 100, 1) }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-pie fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No hay datos para mostrar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-trophy me-2"></i>Top 10 Clientes
                </h5>
            </div>
            <div class="card-body">
                @if(count($reporte['clientes_top']) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th class="text-center">Pagos</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reporte['clientes_top'] as $item)
                                    <tr>
                                        <td>{{ $item['cliente']->nombre_completo }}</td>
                                        <td class="text-center">{{ $item['cantidad_pagos'] }}</td>
                                        <td class="text-end">{{ number_format($item['monto_total'], 2) }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-trophy fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No hay datos para mostrar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Detalle de Pagos
            <span class="badge bg-primary">{{ $reporte['pagos']->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($reporte['pagos']->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Factura</th>
                            <th>Monto</th>
                            <th>Fecha</th>
                            <th>Método</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reporte['pagos'] as $pago)
                            <tr>
                                <td>{{ $pago->id }}</td>
                                <td>
                                    <a href="{{ route('clientes.show', $pago->cliente) }}" class="text-decoration-none">
                                        {{ $pago->cliente->nombre_completo }}
                                    </a>
                                </td>
                                <td>
                                    @if($pago->factura)
                                        <a href="{{ route('facturas.show', $pago->factura) }}" class="text-decoration-none">
                                            {{ $pago->factura->numero_completo }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold">{{ number_format($pago->monto, 2) }} €</td>
                                <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                <td>
                                    <span class="badge bg-secondary text-capitalize">{{ $pago->metodo_pago }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ estado_color($pago->estado) }}">
                                        {{ $pago->estado }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('pagos.show', $pago) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-success">
                            <td colspan="3" class="text-end fw-bold">TOTAL:</td>
                            <td class="text-end fw-bold">{{ number_format($reporte['totales']['monto_total'], 2) }} €</td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No hay pagos en el período seleccionado</h4>
                <p class="text-muted">Intenta ajustar los filtros.</p>
            </div>
        @endif
    </div>
</div>

<div class="card shadow mt-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Información del Reporte</h6>
                <ul class="list-unstyled">
                    <li><strong>Período:</strong> {{ $reporte['periodo']['inicio'] }} al {{ $reporte['periodo']['fin'] }}</li>
                    <li><strong>Total Pagos:</strong> {{ $reporte['pagos']->count() }}</li>
                    <li><strong>Monto Total:</strong> {{ number_format($reporte['totales']['monto_total'], 2) }} €</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Resumen por Método</h6>
                <ul class="list-unstyled">
                    @foreach($reporte['por_metodo'] as $metodo => $datos)
                        <li>
                            <strong class="text-capitalize">{{ $metodo }}:</strong> 
                            {{ $datos['cantidad'] }} pagos - 
                            {{ number_format($datos['monto_total'], 2) }} €
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fechaInicio = document.getElementById('fecha_inicio');
        const fechaFin = document.getElementById('fecha_fin');
        
        if (!fechaInicio.value) {
            const firstDay = new Date();
            firstDay.setDate(1);
            fechaInicio.value = firstDay.toISOString().split('T')[0];
        }
        
        if (!fechaFin.value) {
            fechaFin.value = new Date().toISOString().split('T')[0];
        }
    });
</script>
@endpush
