@extends('layouts.app')

@section('title', 'Reporte de Clientes - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-users me-2"></i>Reporte de Clientes
            </h1>
            <div class="btn-group">
                <a href="{{ route('reportes.exportar-clientes') }}" 
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
            <i class="fas fa-filter me-2"></i>Ordenar Por
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('reportes.clientes') }}" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="ordenar_por" class="form-label">Ordenar por</label>
                        <select name="ordenar_por" id="ordenar_por" class="form-select">
                            <option value="facturacion" {{ $ordenarPor == 'facturacion' ? 'selected' : '' }}>Facturación</option>
                            <option value="trabajos" {{ $ordenarPor == 'trabajos' ? 'selected' : '' }}>Cantidad de Trabajos</option>
                            <option value="pagos" {{ $ordenarPor == 'pagos' ? 'selected' : '' }}>Total Pagado</option>
                            <option value="saldo" {{ $ordenarPor == 'saldo' ? 'selected' : '' }}>Saldo Pendiente</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="mb-3">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sort me-2"></i>Ordenar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $reporte['totales']['total_clientes'] }}</h4>
                <small>Total Clientes</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ number_format($reporte['totales']['total_facturado'], 2) }} €</h4>
                <small>Total Facturado</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ number_format($reporte['totales']['total_pagado'], 2) }} €</h4>
                <small>Total Pagado</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ number_format($reporte['totales']['total_trabajos'], 0) }}</h4>
                <small>Total Trabajos</small>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Resumen de Clientes (Ordenado por: {{ ucfirst(str_replace('_', ' ', $ordenarPor)) }})
                </h5>
            </div>
            <div class="card-body">
                @if($reporte['clientes']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th class="text-center">Trabajos</th>
                                    <th class="text-center">Completados</th>
                                    <th class="text-end">Facturas</th>
                                    <th class="text-end">Total Facturado</th>
                                    <th class="text-end">Total Pagado</th>
                                    <th class="text-end">Saldo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reporte['clientes'] as $item)
                                    @php
                                        $cliente = $item['cliente'];
                                        $stats = $item['estadisticas'];
                                    @endphp
                                    <tr>
                                        <td>
                                            <a href="{{ route('clientes.show', $cliente) }}" class="text-decoration-none fw-bold">
                                                {{ $cliente->nombre_completo }}
                                            </a>
                                        </td>
                                        <td>{{ $cliente->email ?? '-' }}</td>
                                        <td>{{ $cliente->telefono ?? '-' }}</td>
                                        <td class="text-center">{{ $stats['total_trabajos'] }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $stats['trabajos_completados'] }}</span>
                                        </td>
                                        <td class="text-center">{{ $stats['facturas_emitidas'] }}</td>
                                        <td class="text-end">{{ number_format($stats['total_facturado'], 2) }} €</td>
                                        <td class="text-end text-success">{{ number_format($stats['total_pagado'], 2) }} €</td>
                                        <td class="text-end">
                                            @if($stats['saldo_pendiente'] > 0)
                                                <span class="badge bg-danger">{{ number_format($stats['saldo_pendiente'], 2) }} €</span>
                                            @else
                                                <span class="badge bg-success">0.00 €</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-primary">
                                    <td colspan="6" class="text-end fw-bold">TOTALES:</td>
                                    <td class="text-end fw-bold">{{ number_format($reporte['totales']['total_facturado'], 2) }} €</td>
                                    <td class="text-end fw-bold">{{ number_format($reporte['totales']['total_pagado'], 2) }} €</td>
                                    <td class="text-end fw-bold">{{ number_format($reporte['totales']['saldo_total'], 2) }} €</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay clientes registrados</h4>
                        <p class="text-muted">Comienza agregando clientes al sistema.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-arrow-up me-2"></i>Top 5 Clientes con Mayor Facturación
                </h5>
            </div>
            <div class="card-body">
                @php
                    $topFacturacion = $reporte['clientes']->sortByDesc('estadisticas.total_facturado')->take(5);
                @endphp
                @if($topFacturacion->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th class="text-end">Total Facturado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topFacturacion as $item)
                                    <tr>
                                        <td>{{ $item['cliente']->nombre_completo }}</td>
                                        <td class="text-end fw-bold">{{ number_format($item['estadisticas']['total_facturado'], 2) }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No hay datos disponibles</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>Top 5 Clientes con Mayor Saldo Pendiente
                </h5>
            </div>
            <div class="card-body">
                @php
                    $topSaldo = $reporte['clientes']->filter(function($item) {
                        return $item['estadisticas']['saldo_pendiente'] > 0;
                    })->sortByDesc('estadisticas.saldo_pendiente')->take(5);
                @endphp
                @if($topSaldo->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th class="text-end">Saldo Pendiente</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topSaldo as $item)
                                    <tr>
                                        <td>{{ $item['cliente']->nombre_completo }}</td>
                                        <td class="text-end fw-bold text-danger">{{ number_format($item['estadisticas']['saldo_pendiente'], 2) }} €</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No hay saldos pendientes</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>Información del Reporte</h6>
                <ul class="list-unstyled">
                    <li><strong>Total Clientes:</strong> {{ $reporte['totales']['total_clientes'] }}</li>
                    <li><strong>Total Facturado:</strong> {{ number_format($reporte['totales']['total_facturado'], 2) }} €</li>
                    <li><strong>Total Pagado:</strong> {{ number_format($reporte['totales']['total_pagado'], 2) }} €</li>
                    <li><strong>Saldo Total Pendiente:</strong> {{ number_format($reporte['totales']['saldo_total'], 2) }} €</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Estadísticas Adicionales</h6>
                <ul class="list-unstyled">
                    <li><strong>Total Trabajos:</strong> {{ $reporte['totales']['total_trabajos'] }}</li>
                    <li><strong>Ticket Promedio:</strong> {{ $reporte['totales']['total_clientes'] > 0 ? number_format($reporte['totales']['total_facturado'] / $reporte['totales']['total_clientes'], 2) : 0 }} €</li>
                    <li><strong>Ordenado por:</strong> {{ ucfirst(str_replace('_', ' ', $ordenarPor)) }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
