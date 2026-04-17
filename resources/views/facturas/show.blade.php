@extends('layouts.app')

@section('title', 'Factura ' . $factura->numero_completo . ' - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-file-invoice me-2"></i>Factura {{ $factura->numero_completo }}
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('facturas.index') }}">Facturas</a></li>
                        <li class="breadcrumb-item active">{{ $factura->numero_completo }}</li>
                    </ol>
                </nav>
            </div>
            <div class="btn-group">
                <a href="{{ route('facturas.pdf', $factura) }}" 
                   class="btn btn-info" 
                   target="_blank">
                    <i class="fas fa-download me-2"></i>Descargar PDF
                </a>
                @if($factura->estado == 'borrador')
                    <a href="{{ route('facturas.edit', $factura) }}" class="btn btn-secondary">
                        <i class="fas fa-edit me-2"></i>Editar
                    </a>
                    <form action="{{ route('facturas.emitir', $factura) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success"
                                onclick="return confirm('¿Emitir esta factura? Esta acción no se puede deshacer.')">
                            <i class="fas fa-paper-plane me-2"></i>Emitir
                        </button>
                    </form>
                @endif
                @if(in_array($factura->estado, ['emitida', 'borrador']))
                    <form action="{{ route('facturas.cancelar', $factura) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger"
                                onclick="return confirm('¿Cancelar esta factura? Esta acción no se puede deshacer.')">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Información de la Factura -->
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Detalles de la Factura</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Número de Factura:</label>
                            <p class="mb-0">{{ $factura->numero_completo }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cliente:</label>
                            <p class="mb-0">
                                <a href="{{ route('clientes.show', $factura->cliente) }}">
                                    {{ $factura->cliente->nombre_completo }}
                                </a>
                                <br>
                                <small class="text-muted">
                                    {{ $factura->cliente->dni_cif }}<br>
                                    {{ $factura->cliente->direccion }}<br>
                                    {{ $factura->cliente->ciudad }} {{ $factura->cliente->codigo_postal }}
                                </small>
                            </p>
                        </div>
                        
                        @if($factura->trabajo)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Trabajo Relacionado:</label>
                            <p class="mb-0">
                                <a href="{{ route('trabajos.show', $factura->trabajo) }}">
                                    {{ $factura->trabajo->titulo }} ({{ $factura->trabajo->codigo_trabajo }})
                                </a>
                            </p>
                        </div>
                        @endif
                    </div>
                    
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Estado:</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $factura->estado_color }} fs-6">
                                    {{ $factura->estado }}
                                </span>
                            </p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fecha de Emisión:</label>
                            <p class="mb-0">{{ $factura->fecha_emision->format('d/m/Y') }}</p>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fecha de Vencimiento:</label>
                            <p class="mb-0 {{ $factura->esta_vencida ? 'text-danger fw-bold' : '' }}">
                                {{ $factura->fecha_vencimiento ? $factura->fecha_vencimiento->format('d/m/Y') : 'No especificada' }}
                                @if($factura->esta_vencida)
                                    <br><small class="text-danger">Factura vencida</small>
                                @endif
                            </p>
                        </div>
                        
                        @if($factura->fecha_pago)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Fecha de Pago:</label>
                            <p class="mb-0 text-success">{{ $factura->fecha_pago->format('d/m/Y') }}</p>
                        </div>
                        @endif
                        
                        @if($factura->forma_pago)
                        <div class="mb-3">
                            <label class="form-label fw-bold">Forma de Pago:</label>
                            <p class="mb-0">{{ $factura->forma_pago }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Concepto:</label>
                    <p class="mb-0">{{ $factura->concepto }}</p>
                </div>

                @if($factura->observaciones)
                <div class="mb-3">
                    <label class="form-label fw-bold">Observaciones:</label>
                    <p class="mb-0 text-muted">{{ $factura->observaciones }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Líneas de Factura -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Líneas de Factura</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio Unitario</th>
                                <th class="text-end">IVA</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($factura->lineas && count($factura->lineas) > 0)
                                @foreach($factura->lineas as $index => $linea)
                                    <tr>
                                        <td>{{ $linea['descripcion'] }}</td>
                                        <td class="text-center">{{ $linea['cantidad'] }}</td>
                                        <td class="text-end">{{ number_format($linea['precio'], 2) }} €</td>
                                        <td class="text-end">{{ $linea['iva'] }}%</td>
                                        <td class="text-end">{{ number_format($linea['total'], 2) }} €</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-3">
                                        No hay líneas de factura definidas
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="border-0"></td>
                                <td class="text-end fw-bold">Subtotal:</td>
                                <td class="text-end fw-bold">{{ number_format($factura->subtotal, 2) }} €</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="border-0"></td>
                                <td class="text-end fw-bold">IVA ({{ $factura->iva }}%):</td>
                                <td class="text-end fw-bold">{{ number_format($factura->total - $factura->subtotal, 2) }} €</td>
                            </tr>
                            <tr class="table-primary">
                                <td colspan="3" class="border-0"></td>
                                <td class="text-end fw-bold fs-5">TOTAL:</td>
                                <td class="text-end fw-bold fs-5">{{ number_format($factura->total, 2) }} €</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagos Registrados -->
        @if($factura->pagos->count() > 0)
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">Pagos Registrados</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Método</th>
                                <th>Referencia</th>
                                <th>Monto</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($factura->pagos as $pago)
                                <tr>
                                    <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $pago->metodo_pago }}</span>
                                    </td>
                                    <td>{{ $pago->referencia ?? '-' }}</td>
                                    <td>{{ number_format($pago->monto, 2) }} €</td>
                                    <td>
                                        <span class="badge bg-{{ $pago->estado == 'completado' ? 'success' : 'warning' }}">
                                            {{ $pago->estado }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('pagos.show', $pago) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="3" class="text-end fw-bold">Total Pagado:</td>
                                <td class="fw-bold text-success">
                                    {{ number_format($factura->pagos->where('estado', 'completado')->sum('monto'), 2) }} €
                                </td>
                                <td colspan="2"></td>
                            </tr>
                            @if($factura->saldo_pendiente > 0)
                            <tr class="table-warning">
                                <td colspan="3" class="text-end fw-bold">Saldo Pendiente:</td>
                                <td class="fw-bold text-danger">
                                    {{ number_format($factura->saldo_pendiente, 2) }} €
                                </td>
                                <td colspan="2"></td>
                            </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar con Resumen y Acciones -->
    <div class="col-lg-4">
        <!-- Resumen Financiero -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Resumen Financiero
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Subtotal:</label>
                    <p class="mb-0 fs-5">{{ number_format($factura->subtotal, 2) }} €</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">IVA ({{ $factura->iva }}%):</label>
                    <p class="mb-0 fs-6">{{ number_format($factura->total - $factura->subtotal, 2) }} €</p>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Total Factura:</label>
                    <p class="mb-0 fs-4 text-primary fw-bold">{{ number_format($factura->total, 2) }} €</p>
                </div>

                <hr>

                <div class="mb-3">
                    <label class="form-label fw-bold">Total Pagado:</label>
                    <p class="mb-0 fs-5 text-success">
                        {{ number_format($factura->pagos->where('estado', 'completado')->sum('monto'), 2) }} €
                    </p>
                </div>
                
                @if($factura->saldo_pendiente > 0)
                <div class="mb-3">
                    <label class="form-label fw-bold">Saldo Pendiente:</label>
                    <p class="mb-0 fs-5 text-danger">
                        {{ number_format($factura->saldo_pendiente, 2) }} €
                    </p>
                </div>
                @else
                <div class="mb-3">
                    <label class="form-label fw-bold">Estado de Pago:</label>
                    <p class="mb-0">
                        <span class="badge bg-success fs-6">COMPLETAMENTE PAGADA</span>
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Acciones Rápidas -->
        <div class="card shadow mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('facturas.pdf', $factura) }}" 
                       class="btn btn-outline-primary" 
                       target="_blank">
                        <i class="fas fa-download me-2"></i>Descargar PDF
                    </a>
                    
                    @if($factura->estado == 'emitida' && $factura->saldo_pendiente > 0)
                        <a href="{{ route('pagos.create') }}?factura_id={{ $factura->id }}" 
                           class="btn btn-outline-success">
                            <i class="fas fa-money-bill me-2"></i>Registrar Pago
                        </a>
                    @endif
                    
                    @if($factura->estado == 'borrador')
                        <a href="{{ route('facturas.edit', $factura) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-edit me-2"></i>Editar Factura
                        </a>
                        
                        <form action="{{ route('facturas.emitir', $factura) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-outline-success"
                                    onclick="return confirm('¿Emitir esta factura? Esta acción no se puede deshacer.')">
                                <i class="fas fa-paper-plane me-2"></i>Emitir Factura
                            </button>
                        </form>
                    @endif
                    
                    @if(in_array($factura->estado, ['emitida', 'borrador']))
                        <form action="{{ route('facturas.cancelar', $factura) }}" method="POST" class="d-grid">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger"
                                    onclick="return confirm('¿Cancelar esta factura? Esta acción no se puede deshacer.')">
                                <i class="fas fa-times me-2"></i>Cancelar Factura
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Información de la Empresa -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-building me-2"></i>Información de la Empresa
                </h5>
            </div>
            <div class="card-body">
                <h6 class="fw-bold">{{ config('app.name', 'Tapicería Odami') }}</h6>
                <p class="small mb-1">
                    {{ \App\Models\Configuracion::obtener('empresa_direccion', 'Calle Principal 123') }}<br>
                    {{ \App\Models\Configuracion::obtener('empresa_ciudad', 'Ciudad') }}<br>
                    Telf: {{ \App\Models\Configuracion::obtener('empresa_telefono', '+34 912 345 678') }}<br>
                    Email: {{ \App\Models\Configuracion::obtener('empresa_email', 'info@tapiceria.com') }}<br>
                    CIF: {{ \App\Models\Configuracion::obtener('empresa_cif', 'B12345678') }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection