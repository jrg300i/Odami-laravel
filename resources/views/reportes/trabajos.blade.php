@extends('layouts.app')

@section('title', 'Reporte de Trabajos - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-tools me-2"></i>Reporte de Trabajos
            </h1>
            <div class="btn-group">
                <a href="{{ route('reportes.exportar-trabajos', request()->all()) }}" 
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
        <form action="{{ route('reportes.trabajos') }}" method="GET">
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
                        <label for="tipo" class="form-label">Tipo de Trabajo</label>
                        <select name="tipo" id="tipo" class="form-select">
                            <option value="todos">Todos los tipos</option>
                            <option value="silla" {{ $tipo == 'silla' ? 'selected' : '' }}>Sillas</option>
                            <option value="sofa" {{ $tipo == 'sofa' ? 'selected' : '' }}>Sofás</option>
                            <option value="sillon" {{ $tipo == 'sillon' ? 'selected' : '' }}>Sillones</option>
                            <option value="cabecero" {{ $tipo == 'cabecero' ? 'selected' : '' }}>Cabeceros</option>
                            <option value="butaca" {{ $tipo == 'butaca' ? 'selected' : '' }}>Butacas</option>
                            <option value="personalizado" {{ $tipo == 'personalizado' ? 'selected' : '' }}>Personalizados</option>
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

<!-- Resumen General -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $reporte['totales']['cantidad'] }}</h4>
                <small>Total Trabajos</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ number_format($reporte['totales']['costo_estimado'], 2) }} €</h4>
                <small>Costo Estimado</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ number_format($reporte['totales']['costo_final'] ?? 0, 2) }} €</h4>
                <small>Costo Final</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">
                    @php
                        $completados = $reporte['por_estado']['completado']['cantidad'] ?? 0;
                        $total = $reporte['totales']['cantidad'];
                        $porcentaje = $total > 0 ? ($completados / $total) * 100 : 0;
                    @endphp
                    {{ number_format($porcentaje, 1) }}%
                </h4>
                <small>Tasa de Completación</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Trabajos por Estado -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Trabajos por Estado
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
                                    <th class="text-end">Costo Estimado</th>
                                    <th class="text-end">Costo Final</th>
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
                                        <td class="text-end">{{ number_format($datos['costo_estimado'], 2) }} €</td>
                                        <td class="text-end">{{ number_format($datos['costo_final'], 2) }} €</td>
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

    <!-- Trabajos por Tipo -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Trabajos por Tipo
                </h5>
            </div>
            <div class="card-body">
                @if(count($reporte['por_tipo']) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tipo</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-end">Costo Estimado</th>
                                    <th class="text-end">Promedio</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reporte['por_tipo'] as $tipo => $datos)
                                    <tr>
                                        <td>
                                            <span class="badge bg-secondary text-capitalize">{{ $tipo }}</span>
                                        </td>
                                        <td class="text-center">{{ $datos['cantidad'] }}</td>
                                        <td class="text-end">{{ number_format($datos['costo_estimado'], 2) }} €</td>
                                        <td class="text-end">
                                            {{ number_format($datos['cantidad'] > 0 ? $datos['costo_estimado'] / $datos['cantidad'] : 0, 2) }} €
                                        </td>
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

<!-- Lista Detallada de Trabajos -->
<div class="card shadow">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Trabajos del Período
            <span class="badge bg-primary">{{ $reporte['trabajos']->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @if($reporte['trabajos']->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th class="text-end">Costo Estimado</th>
                            <th class="text-end">Costo Final</th>
                            <th>Fecha Inicio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reporte['trabajos'] as $trabajo)
                            <tr>
                                <td>
                                    <a href="{{ route('trabajos.show', $trabajo) }}" class="text-decoration-none">
                                        {{ $trabajo->codigo_trabajo }}
                                    </a>
                                </td>
                                <td>{{ $trabajo->cliente->nombre_completo }}</td>
                                <td>{{ Str::limit($trabajo->titulo, 30) }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $trabajo->tipo }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $trabajo->estado_color }}">
                                        {{ $trabajo->estado }}
                                    </span>
                                    @if($trabajo->urgente)
                                        <span class="badge bg-danger">Urgente</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($trabajo->costo_estimado, 2) }} €</td>
                                <td class="text-end">
                                    @if($trabajo->costo_final)
                                        <span class="text-success">{{ number_format($trabajo->costo_final, 2) }} €</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($trabajo->fecha_inicio)
                                        {{ $trabajo->fecha_inicio->format('d/m/Y') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('trabajos.show', $trabajo) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="table-primary">
                            <td colspan="5" class="text-end fw-bold">TOTALES:</td>
                            <td class="text-end fw-bold">{{ number_format($reporte['totales']['costo_estimado'], 2) }} €</td>
                            <td class="text-end fw-bold">{{ number_format($reporte['totales']['costo_final'], 2) }} €</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No hay trabajos en el período seleccionado</h4>
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
                    <li><strong>Total Trabajos:</strong> {{ $reporte['totales']['cantidad'] }}</li>
                    <li><strong>Costo Estimado Total:</strong> {{ number_format($reporte['totales']['costo_estimado'], 2) }} €</li>
                    <li><strong>Costo Final Total:</strong> {{ number_format($reporte['totales']['costo_final'], 2) }} €</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Resumen por Estados</h6>
                <ul class="list-unstyled">
                    @foreach($reporte['por_estado'] as $estado => $datos)
                        <li>
                            <span class="badge bg-{{ estado_color($estado) }} me-1">
                                {{ $estado }}
                            </span>
                            {{ $datos['cantidad'] }} trabajos - 
                            {{ number_format($datos['costo_estimado'], 2) }} €
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
            firstDay.setMonth(firstDay.getMonth() - 1);
            fechaInicio.value = firstDay.toISOString().split('T')[0];
        }
        
        if (!fechaFin.value) {
            fechaFin.value = new Date().toISOString().split('T')[0];
        }
    });
</script>
@endpush