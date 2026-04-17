@extends('layouts.app')

@section('title', 'Agregar Fotos - ' . $trabajo->titulo)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0">
                    <i class="fas fa-camera me-2"></i>Agregar Fotos
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('trabajos.index') }}">Trabajos</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('trabajos.show', $trabajo) }}">{{ $trabajo->codigo_trabajo }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('trabajos.fotos.index', $trabajo) }}">Fotos</a></li>
                        <li class="breadcrumb-item active">Agregar</li>
                    </ol>
                </nav>
            </div>
            <a href="{{ route('trabajos.fotos.index', $trabajo) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">Subir Fotos</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('trabajos.fotos.store', $trabajo) }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                    @csrf

                    <div class="mb-3">
                        <label for="fotos" class="form-label">Seleccionar Fotos *</label>
                        <input type="file" 
                               name="fotos[]" 
                               id="fotos" 
                               class="form-control @error('fotos') is-invalid @enderror" 
                               multiple 
                               accept="image/*"
                               required>
                        <div class="form-text">
                            Puedes seleccionar múltiples fotos (máximo 10). Formatos permitidos: JPEG, PNG, JPG, GIF. Tamaño máximo por archivo: 10MB.
                        </div>
                        @error('fotos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="fase" class="form-label">Fase del Trabajo *</label>
                        <select name="fase" id="fase" class="form-select @error('fase') is-invalid @enderror" required>
                            <option value="">Seleccionar fase...</option>
                            <option value="antes" {{ old('fase') == 'antes' ? 'selected' : '' }}>Antes del Trabajo</option>
                            <option value="durante" {{ old('fase') == 'durante' ? 'selected' : '' }}>Durante el Trabajo</option>
                            <option value="despues" {{ old('fase') == 'despues' ? 'selected' : '' }}>Después del Trabajo</option>
                        </select>
                        @error('fase')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="titulo" class="form-label">Título (Opcional)</label>
                        <input type="text" 
                               name="titulo" 
                               id="titulo" 
                               class="form-control @error('titulo') is-invalid @enderror" 
                               value="{{ old('titulo') }}" 
                               placeholder="Título para las fotos...">
                        @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                        <textarea name="descripcion" 
                                  id="descripcion" 
                                  class="form-control @error('descripcion') is-invalid @enderror" 
                                  rows="3" 
                                  placeholder="Descripción de las fotos...">{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="es_principal" 
                                   id="es_principal" 
                                   value="1" 
                                   {{ old('es_principal') ? 'checked' : '' }}>
                            <label class="form-check-label" for="es_principal">
                                Marcar como foto principal
                            </label>
                        </div>
                        <div class="form-text">
                            La foto principal se mostrará como portada del trabajo.
                        </div>
                    </div>

                    <!-- Vista previa de fotos -->
                    <div class="mb-3">
                        <label class="form-label">Vista Previa</label>
                        <div id="preview-container" class="row g-2 d-none">
                            <!-- Las vistas previas se insertarán aquí -->
                        </div>
                        <div id="no-preview" class="text-center py-4 border rounded">
                            <i class="fas fa-images fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Las vistas previas aparecerán aquí</p>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6 class="alert-heading">💡 Consejos para las fotos</h6>
                        <ul class="mb-0 small">
                            <li>Usa buena iluminación para fotos más claras</li>
                            <li>Toma fotos desde diferentes ángulos</li>
                            <li>Incluye detalles importantes y vistas generales</li>
                            <li>Las fotos se comprimirán automáticamente para ahorrar espacio</li>
                        </ul>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('trabajos.fotos.index', $trabajo) }}" class="btn btn-secondary me-md-2">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-upload me-2"></i>Subir Fotos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Información del Trabajo -->
    <div class="col-lg-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Información del Trabajo
                </h5>
            </div>
            <div class="card-body">
                <h6>{{ $trabajo->titulo }}</h6>
                <p class="text-muted small mb-3">{{ $trabajo->codigo_trabajo }}</p>
                
                <div class="mb-3">
                    <strong>Cliente:</strong><br>
                    <a href="{{ route('clientes.show', $trabajo->cliente) }}" class="text-decoration-none">
                        {{ $trabajo->cliente->nombre_completo }}
                    </a>
                </div>
                
                <div class="mb-3">
                    <strong>Estado:</strong><br>
                    <span class="badge bg-{{ $trabajo->estado_color }}">{{ $trabajo->estado }}</span>
                </div>
                
                <div class="mb-3">
                    <strong>Tipo:</strong><br>
                    <span class="badge bg-secondary">{{ $trabajo->tipo }}</span>
                </div>

                <hr>

                <h6>Fotos Existentes</h6>
                @if($trabajo->fotos->count() > 0)
                    <div class="row g-1">
                        @foreach($trabajo->fotos->take(4) as $foto)
                            <div class="col-3">
                                <img src="{{ $foto->url_miniatura }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $foto->titulo }}"
                                     style="height: 60px; width: 100%; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-2">
                        <a href="{{ route('trabajos.fotos.index', $trabajo) }}" class="btn btn-sm btn-outline-primary">
                            Ver todas ({{ $trabajo->fotos->count() }})
                        </a>
                    </div>
                @else
                    <p class="text-muted small mb-0">Este trabajo no tiene fotos aún.</p>
                @endif
            </div>
        </div>

        <!-- Fases del Trabajo -->
        <div class="card shadow mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-project-diagram me-2"></i>Fases del Trabajo
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-camera text-primary me-2"></i>
                            <span>Antes del Trabajo</span>
                        </div>
                        <span class="badge bg-primary">{{ $trabajo->fotos->where('fase', 'antes')->count() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-tools text-warning me-2"></i>
                            <span>Durante el Trabajo</span>
                        </div>
                        <span class="badge bg-warning">{{ $trabajo->fotos->where('fase', 'durante')->count() }}</span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <span>Después del Trabajo</span>
                        </div>
                        <span class="badge bg-success">{{ $trabajo->fotos->where('fase', 'despues')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('fotos');
        const previewContainer = document.getElementById('preview-container');
        const noPreview = document.getElementById('no-preview');
        const submitBtn = document.getElementById('submitBtn');

        fileInput.addEventListener('change', function(e) {
            const files = e.target.files;
            
            if (files.length > 0) {
                previewContainer.classList.remove('d-none');
                noPreview.classList.add('d-none');
                previewContainer.innerHTML = '';
                
                // Validar número de archivos
                if (files.length > 10) {
                    alert('Máximo 10 archivos permitidos. Se seleccionaron: ' + files.length);
                    fileInput.value = '';
                    previewContainer.classList.add('d-none');
                    noPreview.classList.remove('d-none');
                    return;
                }
                
                // Mostrar vistas previas
                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-6 col-md-4 col-lg-3';
                            
                            col.innerHTML = `
                                <div class="card">
                                    <img src="${e.target.result}" 
                                         class="card-img-top" 
                                         alt="Preview"
                                         style="height: 100px; object-fit: cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted d-block text-truncate">${file.name}</small>
                                        <small class="text-muted">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                                    </div>
                                </div>
                            `;
                            
                            previewContainer.appendChild(col);
                        };
                        
                        reader.readAsDataURL(file);
                    }
                });
                
                // Actualizar texto del botón
                submitBtn.innerHTML = `<i class="fas fa-upload me-2"></i>Subir ${files.length} Foto(s)`;
            } else {
                previewContainer.classList.add('d-none');
                noPreview.classList.remove('d-none');
                submitBtn.innerHTML = `<i class="fas fa-upload me-2"></i>Subir Fotos`;
            }
        });

        // Validación antes de enviar
        const form = document.getElementById('uploadForm');
        form.addEventListener('submit', function(e) {
            const files = fileInput.files;
            const fase = document.getElementById('fase').value;
            
            if (files.length === 0) {
                e.preventDefault();
                alert('Por favor selecciona al menos una foto.');
                return;
            }
            
            if (!fase) {
                e.preventDefault();
                alert('Por favor selecciona la fase del trabajo.');
                return;
            }
            
            // Mostrar loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading-spinner me-2"></span> Subiendo...';
        });
    });
</script>

<style>
    .preview-image {
        max-height: 150px;
        object-fit: cover;
    }
    
    #preview-container .card {
        transition: transform 0.2s;
    }
    
    #preview-container .card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush