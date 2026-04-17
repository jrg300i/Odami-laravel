@extends('layouts.app')

@section('title', 'Facturas - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-file-invoice me-2"></i>Facturas
            </h1>
            <a href="{{ route('facturas.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nueva Factura
            </a>
        </div>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $estadisticas['total'] }}</h4>
                <small>Total Facturas</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $estadisticas['emitidas'] }}</h4>
                <small>Emitidas</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $estadisticas['pagadas'] }}</h4>
                <small>Pagadas</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $estadisticas['vencidas'] }}</h4>
                <small>Vencidas</small>
            </div>
        </div>
    </div>
</div>

<!-- Filtros y Búsqueda -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="{{ route('facturas.index') }}" method="GET">
            <div class="row">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control" 
                               placeholder="Buscar facturas..." 
                               value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="estado" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los estados</option>
                        <option value="borrador" {{ request('estado') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                        <option value="emitida" {{ request('estado') == 'emitida' ? 'selected' : '' }}>Emitida</option>
                        <option value="pagada" {{ request('estado') == 'pagada' ? 'selected' : '' }}>Pagada</option>
                        <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="serie" class="form-select" onchange="this.form.submit()">
                        <option value="">Todas las series</option>
                        <option value="A" {{ request('serie') == 'A' ? 'selected' : '' }}>Serie A</option>
                        <option value="B" {{ request('serie') == 'B' ? 'selected' : '' }}>Serie B</option>
                        <option value="C" {{ request('serie') == 'C' ? 'selected' : '' }}>Serie C</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('facturas.index') }}" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-refresh"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Lista de Facturas -->
<div class="card shadow">
    <div class="card-body">
        @if($facturas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Número</th>
                            <th>Cliente</th>
                            <th>Fecha Emisión</th>
                            <th>Vencimiento</th>
                            <th>Concepto</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facturas as $factura)
                            <tr class="{{ $factura->esta_vencida ? 'table-warning' : '' }}">
                                <td>
                                    <strong>{{ $factura->numero_completo }}</strong>
                                    @if($factura->trabajo)
                                        <br>
                                        <small class="text-muted">{{ $factura->trabajo->codigo_trabajo }}</small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('clientes.show', $factura->cliente) }}" class="text-decoration-none">
                                        {{ $factura->cliente->nombre_completo }}
                                    </a>
                                </td>
                                <td>{{ $factura->fecha_emision->format('d/m/Y') }}</td>
                                <td>
                                    @if($factura->fecha_vencimiento)
                                        <span class="{{ $factura->esta_vencida ? 'text-danger fw-bold' : '' }}">
                                            {{ $factura->fecha_vencimiento->format('d/m/Y') }}
                                        </span>
                                        @if($factura->esta_vencida)
                                            <br><small class="text-danger">Vencida</small>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($factura->concepto, 40) }}</td>
                                <td>
                                    <strong>{{ number_format($factura->total, 2) }} €</strong>
                                    @if($factura->estado == 'emitida' && $factura->saldo_pendiente > 0)
                                        <br>
                                        <small class="text-warning">
                                            Pendiente: {{ number_format($factura->saldo_pendiente, 2) }} €
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $factura->estado_color }}">
                                        {{ $factura->estado }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('facturas.show', $factura) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('facturas.pdf', $factura) }}" 
                                           class="btn btn-sm btn-outline-info" 
                                           title="Descargar PDF"
                                           target="_blank">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        @if($factura->estado == 'borrador')
                                            <a href="{{ route('facturas.edit', $factura) }}" 
                                               class="btn btn-sm btn-outline-secondary" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('facturas.emitir', $factura) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-success"
                                                        title="Emitir factura"
                                                        onclick="return confirm('¿Emitir esta factura? Esta acción no se puede deshacer.')">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if(in_array($factura->estado, ['emitida', 'borrador']))
                                            <form action="{{ route('facturas.cancelar', $factura) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-sm btn-outline-danger"
                                                        title="Cancelar factura"
                                                        onclick="return confirm('¿Cancelar esta factura? Esta acción no se puede deshacer.')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Mostrando {{ $facturas->firstItem() }} - {{ $facturas->lastItem() }} de {{ $facturas->total() }} facturas
                </div>
                {{ $facturas->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No se encontraron facturas</h4>
                <p class="text-muted">
                    @if(request()->hasAny(['search', 'estado', 'serie']))
                        Intenta ajustar los filtros de búsqueda
                    @else
                        Comienza creando tu primera factura
                    @endif
                </p>
                <a href="{{ route('facturas.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Crear Factura
                </a>
            </div>
        @endif
    </div>
</div>
@endsection