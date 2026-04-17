@extends('layouts.app')

@section('title', 'Nuevo Trabajo - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-plus me-2"></i>Nuevo Trabajo
            </h1>
            <a href="{{ route('trabajos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">Información del Trabajo</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('trabajos.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="cliente_id" class="form-label">Cliente *</label>
                                <select name="cliente_id" id="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                                    <option value="">Seleccionar cliente...</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" 
                                                {{ old('cliente_id', request('cliente_id')) == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->nombre_completo }} - {{ $cliente->codigo_cliente }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('cliente_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo de Trabajo *</label>
                                <select name="tipo" id="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="silla" {{ old('tipo') == 'silla' ? 'selected' : '' }}>Silla</option>
                                    <option value="sofa" {{ old('tipo') == 'sofa' ? 'selected' : '' }}>Sofá</option>
                                    <option value="sillon" {{ old('tipo') == 'sillon' ? 'selected' : '' }}>Sillón</option>
                                    <option value="cabecero" {{ old('tipo') == 'cabecero' ? 'selected' : '' }}>Cabecero</option>
                                    <option value="butaca" {{ old('tipo') == 'butaca' ? 'selected' : '' }}>Butaca</option>
                                    <option value="personalizado" {{ old('tipo') == 'personalizado' ? 'selected' : '' }}>Personalizado</option>
                                </select>
                                @error('tipo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título del Trabajo *</label>
                        <input type="text" 
                               name="titulo" 
                               id="titulo" 
                               class="form-control @error('titulo') is-invalid @enderror" 
                               value="{{ old('titulo') }}" 
                               placeholder="Ej: Tapizado de sofá de 3 plazas" 
                               required>
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea name="descripcion" 
                                  id="descripcion" 
                                  class="form-control @error('descripcion') is-invalid @enderror" 
                                  rows="3" 
                                  placeholder="Descripción detallada del trabajo a realizar...">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado *</label>
                                <select name="estado" id="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                    <option value="presupuesto" {{ old('estado') == 'presupuesto' ? 'selected' : '' }}>Presupuesto</option>
                                    <option value="en_proceso" {{ old('estado') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                    <option value="completado" {{ old('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                                    <option value="entregado" {{ old('estado') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                    <option value="cancelado" {{ old('estado') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                                @error('estado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="costo_estimado" class="form-label">Costo Estimado (€) *</label>
                                <input type="number" 
                                       name="costo_estimado" 
                                       id="costo_estimado" 
                                       class="form-control @error('costo_estimado') is-invalid @enderror" 
                                       value="{{ old('costo_estimado', 0) }}" 
                                       step="0.01" 
                                       min="0" 
                                       required>
                                @error('costo_estimado')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="prioridad" class="form-label">Prioridad *</label>
                                <select name="prioridad" id="prioridad" class="form-select @error('prioridad') is-invalid @enderror" required>
                                    <option value="1" {{ old('prioridad') == '1' ? 'selected' : '' }}>⭐ Muy Baja</option>
                                    <option value="2" {{ old('prioridad') == '2' ? 'selected' : '' }}>⭐⭐ Baja</option>
                                    <option value="3" {{ old('prioridad', 3) == '3' ? 'selected' : '' }}>⭐⭐⭐ Media</option>
                                    <option value="4" {{ old('prioridad') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ Alta</option>
                                    <option value="5" {{ old('prioridad') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ Muy Alta</option>
                                </select>
                                @error('prioridad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                <input type="date" 
                                       name="fecha_inicio" 
                                       id="fecha_inicio" 
                                       class="form-control @error('fecha_inicio') is-invalid @enderror" 
                                       value="{{ old('fecha_inicio') }}">
                                @error('fecha_inicio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fecha_fin_estimada" class="form-label">Fecha Fin Estimada</label>
                                <input type="date" 
                                       name="fecha_fin_estimada" 
                                       id="fecha_fin_estimada" 
                                       class="form-control @error('fecha_fin_estimada') is-invalid @enderror" 
                                       value="{{ old('fecha_fin_estimada') }}">
                                @error('fecha_fin_estimada')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="urgente" class="form-label">¿Es urgente?</label>
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           name="urgente" 
                                           id="urgente" 
                                           value="1" 
                                           {{ old('urgente') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="urgente">Marcar como urgente</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="observaciones_cliente" class="form-label">Observaciones del Cliente</label>
                        <textarea name="observaciones_cliente" 
                                  id="observaciones_cliente" 
                                  class="form-control @error('observaciones_cliente') is-invalid @enderror" 
                                  rows="2" 
                                  placeholder="Observaciones o requisitos específicos del cliente...">{{ old('observaciones_cliente') }}</textarea>
                        @error('observaciones_cliente')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notas_internas" class="form-label">Notas Internas</label>
                        <textarea name="notas_internas" 
                                  id="notas_internas" 
                                  class="form-control @error('notas_internas') is-invalid @enderror" 
                                  rows="3" 
                                  placeholder="Notas internas para el equipo de trabajo...">{{ old('notas_internas') }}</textarea>
                        @error('notas_internas')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('trabajos.index') }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Trabajo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar con Materiales -->
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-boxes me-2"></i>Materiales (Opcional)
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-3">
                    Puedes asignar materiales a este trabajo. Los materiales se pueden agregar o modificar después de crear el trabajo.
                </p>

                @if($materiales->count() > 0)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Seleccionar Materiales:</label>
                        @foreach($materiales as $material)
                            <div class="mb-2">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-light" style="min-width: 150px;">
                                        {{ $material->nombre }}
                                        <small class="text-muted d-block">{{ $material->tipo }}</small>
                                    </span>
                                    <input type="number" 
                                           name="materiales[{{ $material->id }}]" 
                                           class="form-control" 
                                           placeholder="Cantidad" 
                                           min="0" 
                                           step="0.01"
                                           value="{{ old('materiales.' . $material->id) }}">
                                    <span class="input-group-text">metros</span>
                                </div>
                                <small class="text-muted">
                                    Stock: {{ $material->stock_actual }} | 
                                    Precio: {{ number_format($material->precio_metro, 2) }} €/m
                                </small>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-box-open fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No hay materiales registrados</p>
                        <small class="text-muted">Puedes agregar materiales desde el menú de configuración</small>
                    </div>
                @endif
            </div>
        </div>

        <!-- Información de Ayuda -->
        <div class="card shadow mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Información
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6 class="alert-heading">Tipos de Trabajo:</h6>
                    <ul class="small mb-0">
                        <li><strong>Silla:</strong> Trabajos en sillas individuales</li>
                        <li><strong>Sofá:</strong> Sofás de 2 o más plazas</li>
                        <li><strong>Sillón:</strong> Sillones individuales</li>
                        <li><strong>Cabecero:</strong> Cabeceros de cama</li>
                        <li><strong>Butaca:</strong> Butacas y reclinables</li>
                        <li><strong>Personalizado:</strong> Trabajos especiales</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Establecer fecha mínima para fechas futuras
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_inicio').min = today;
        document.getElementById('fecha_fin_estimada').min = today;

        // Validar que fecha fin sea posterior a fecha inicio
        const fechaInicio = document.getElementById('fecha_inicio');
        const fechaFin = document.getElementById('fecha_fin_estimada');
        
        fechaInicio.addEventListener('change', function() {
            fechaFin.min = this.value;
        });
    });
</script>
@endpush