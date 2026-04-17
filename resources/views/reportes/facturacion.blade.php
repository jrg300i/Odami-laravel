@extends('layouts.app')

@section('title', 'Reporte de Facturación - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-chart-line me-2"></i>Reporte de Facturación
            </h1>
            <div class="btn-group">
                <a href="{{ route('reportes.exportar-facturacion', request()->all()) }}" 
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

<!-- Filtros -->
<div class="card shadow mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>Filtros del Reporte
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('reportes.facturacion') }}" method="GET">
            <div class="row">
                <div class="col-md-5">
                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                        <input type="date" 
                               name="fecha_inicio" 
                               id="fecha_inicio" 
                               class="form-control" 
                               value="{{ $fechaInicio }}">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="mb-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                        <input type="date" 
                               name="fecha_fin" 
                               id="fecha_fin" 
                               class="form-control" 
                               value="{{ $fechaFin }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i>Filtrar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Resumen General -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $reporte['facturas']->count() }}</h4>
                <small>Total Facturas</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ number_format($reporte['totales']['total'], 2) }} €</h4>
                <small>Facturación Total</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ number_format($reporte['totales']['subtotal'], 2) }} €</h4>
                <small>Base Imponible</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ number_format($reporte['totales']['iva'], 2) }} €</h4>
                <small>IVA Recaudado</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Facturas por Serie -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Facturación por Serie
                </h5>
            </div>
            <div class="card-body">
                @if(count($reporte['por_serie']) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Serie</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reporte['por_serie'] as $serie => $datos)
                                    <tr>
                                        <td>
                                            <strong>Serie {{ $serie }}</strong>
                                        </td>
                                        <td class="text-center">{{ $datos['cantidad'] }}</td>
                                        <td class="text-end">{{ number_format($datos['total'], 2) }} €</td>
                                        <td class="text-end">
                                            {{ number_format(($datos['total'] / $reporte['totales']['total']) * 100, 1) }}%
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

    <!-- Facturas por Estado -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Facturas por Estado
                </h5>
            </div>
            <div class="card-body">
                @if(count($reporte['por_estado']) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Estado</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reporte['por_estado'] as $estado => $datos)
                                    <tr>
                                        <td>
                                            <span class="badge bg-{{ estado_color($estado) }}">
                                                {{ $estado }}
                                            </span>
                                        </td>
                                        <td class="text-center">{{ $datos['cantidad'] }}</td>
                                        <td class="text-end">{{ number_format($datos['total'], 2) }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-bar fa-2x text-muted mb-3"></i>
                        <p class="text-muted mb-0">No hay datos para mostrar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Lista Detallada de Facturas -->
<div class="card shadow">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Facturas del Período
            <span class="badge bg-primary">{{ $reporte['facturas']->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($reporte['facturas']->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Cliente</th>
                            <th>Fecha</th>
                            <th>Concepto</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-end">IVA</th>
                            <th class="text-end">Total</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reporte['facturas'] as $factura)
                            <tr>
                                <td>
                                    <a href="{{ route('facturas.show', $factura) }}" class="text-decoration-none">
                                        {{ $factura->numero_completo }}
                                    </a>
                                </td>
                                <td>{{ $factura->cliente->nombre_completo }}</td>
                                <td>{{ $factura->fecha_emision->format('d/m/Y') }}</td>
                                <td>{{ Str::limit($factura->concepto, 40) }}</td>
                                <td class="text-end">{{ number_format($factura->subtotal, 2) }} €</td>
                                <td class="text-end">{{ number_format($factura->total - $factura->subtotal, 2) }} €</td>
                                <td class="text-end fw-bold">{{ number_format($factura->total, 2) }} €</td>
                                <td>
                                    <span class="badge bg-{{ $factura->estado_color }}">
                                        {{ $factura->estado }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-primary">
                            <td colspan="4" class="text-end fw-bold">TOTALES:</td>
                            <td class="text-end fw-bold">{{ number_format($reporte['totales']['subtotal'], 2) }} €</td>
                            <td class="text-end fw-bold">{{ number_format($reporte['totales']['iva'], 2) }} €</td>
                            <td class="text-end fw-bold">{{ number_format($reporte['totales']['total'], 2) }} €</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No hay facturas en el período seleccionado</h4>
                <p class="text-muted">Intenta ajustar las fechas del filtro.</p>
            </div>
        @endif
    </div>
</div>

<!-- Información del Período -->
<div class="card shadow mt-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Información del Reporte</h6>
                <ul class="list-unstyled">
                    <li><strong>Período:</strong> {{ $reporte['periodo']['inicio'] }} al {{ $reporte['periodo']['fin'] }}</li>
                    <li><strong>Total Facturas:</strong> {{ $reporte['facturas']->count() }}</li>
                    <li><strong>Facturación Total:</strong> {{ number_format($reporte['totales']['total'], 2) }} €</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Resumen por Series</h6>
                <ul class="list-unstyled">
                    @foreach($reporte['por_serie'] as $serie => $datos)
                        <li>
                            <strong>Serie {{ $serie }}:</strong> 
                            {{ $datos['cantidad'] }} facturas - 
                            {{ number_format($datos['total'], 2) }} €
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
        // Establecer fechas por defecto si no están definidas
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