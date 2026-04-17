@extends('layouts.app')

@section('title', 'Sistema de Backup - Tapicería Odami')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-database me-2"></i>Sistema de Backup
            </h1>
            <div class="btn-group">
                <a href="{{ route('backups.logs') }}" class="btn btn-outline-primary">
                    <i class="fas fa-list me-2"></i>Ver Logs
                </a>
                <a href="{{ route('backups.configuracion') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-cog me-2"></i>Configuración
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $estadisticas['total_backups'] }}</h4>
                <small>Total Backups</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ number_format($estadisticas['tamanio_total'], 1) }} MB</h4>
                <small>Espacio Usado</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ $estadisticas['backups_30_dias'] }}</h4>
                <small>Últimos 30 días</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body text-center py-3">
                <h4 class="card-title mb-0">{{ number_format($estadisticas['tasa_exito'], 1) }}%</h4>
                <small>Tasa de Éxito</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Crear Backup -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-plus-circle me-2"></i>Crear Nuevo Backup
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('backups.crear') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Backup *</label>
                        <select name="tipo" id="tipo" class="form-select" required>
                            <option value="completo">Backup Completo</option>
                            <option value="base_datos">Solo Base de Datos</option>
                            <option value="archivos">Solo Archivos</option>
                        </select>
                        <div class="form-text">
                            <small>
                                <strong>Completo:</strong> Base de datos + archivos<br>
                                <strong>Base de datos:</strong> Solo estructura y datos<br>
                                <strong>Archivos:</strong> Solo documentos y fotos
                            </small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones (Opcional)</label>
                        <textarea name="observaciones" 
                                  id="observaciones" 
                                  class="form-control" 
                                  rows="2" 
                                  placeholder="Descripción del backup..."></textarea>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6 class="alert-heading">📋 Información importante</h6>
                        <ul class="small mb-0">
                            <li>El backup puede tomar varios minutos</li>
                            <li>No interrumpa el proceso una vez iniciado</li>
                            <li>Los backups se almacenan por 30 días</li>
                        </ul>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100" 
                            onclick="return confirm('¿Iniciar proceso de backup?')">
                        <i class="fas fa-database me-2"></i>Iniciar Backup
                    </button>
                </form>
            </div>
        </div>

        <!-- Configuración Actual -->
        <div class="card shadow mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-cog me-2"></i>Configuración Actual
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <strong>Backup Automático:</strong>
                    <span class="badge bg-{{ $configuracion['automatico'] ? 'success' : 'secondary' }}">
                        {{ $configuracion['automatico'] ? 'Activado' : 'Desactivado' }}
                    </span>
                </div>
                <div class="mb-2">
                    <strong>Frecuencia:</strong> 
                    <span class="text-capitalize">{{ $configuracion['frecuencia'] }}</span>
                </div>
                <div class="mb-2">
                    <strong>Retención:</strong> 
                    {{ $configuracion['retencion'] }} días
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('backups.configuracion') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit me-1"></i>Modificar Configuración
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Backups -->
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Backups Disponibles
                </h5>
                <span class="badge bg-primary">{{ count($backups) }} archivos</span>
            </div>
            <div class="card-body">
                @if(count($backups) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Archivo</th>
                                    <th>Tamaño</th>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($backups as $backup)
                                    <tr>
                                        <td>
                                            <i class="fas fa-file-archive text-warning me-2"></i>
                                            <strong>{{ $backup['nombre'] }}</strong>
                                        </td>
                                        <td>{{ $backup['tamanio'] }} MB</td>
                                        <td>
                                            {{ $backup['fecha']->format('d/m/Y H:i') }}
                                            <br>
                                            <small class="text-muted">{{ $backup['fecha']->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $backup['tipo'] }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('backups.descargar', $backup['nombre']) }}" 
                                                   class="btn btn-outline-primary"
                                                   title="Descargar backup">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-info"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#restoreModal"
                                                        data-backup-name="{{ $backup['nombre'] }}"
                                                        title="Restaurar backup">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-outline-danger"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal"
                                                        data-backup-name="{{ $backup['nombre'] }}"
                                                        title="Eliminar backup">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-database fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay backups disponibles</h4>
                        <p class="text-muted">Crea tu primer backup para empezar a proteger tus datos.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Información de Espacio -->
        <div class="card shadow mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-hdd me-2"></i>Información del Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Espacio en Disco:</label>
                            <div class="progress mb-2">
                                @php
                                    $totalSpace = disk_total_space(storage_path());
                                    $freeSpace = disk_free_space(storage_path());
                                    $usedSpace = $totalSpace - $freeSpace;
                                    $usedPercentage = ($usedSpace / $totalSpace) * 100;
                                @endphp
                                <div class="progress-bar 
                                    {{ $usedPercentage > 90 ? 'bg-danger' : ($usedPercentage > 80 ? 'bg-warning' : 'bg-success') }}" 
                                    role="progressbar" 
                                    style="width: {{ $usedPercentage }}%"
                                    aria-valuenow="{{ $usedPercentage }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                    {{ number_format($usedPercentage, 1) }}%
                                </div>
                            </div>
                            <small class="text-muted">
                                Usado: {{ number_format($usedSpace / 1024 / 1024 / 1024, 1) }} GB de 
                                {{ number_format($totalSpace / 1024 / 1024 / 1024, 1) }} GB
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Última Actividad:</label>
                            <p class="mb-1">
                                <i class="fas fa-clock me-2 text-muted"></i>
                                {{ \App\Models\BackupLog::latest()->first()?->created_at?->diffForHumans() ?? 'Nunca' }}
                            </p>
                            <small class="text-muted">
                                Total logs: {{ \App\Models\BackupLog::count() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Restaurar -->
<div class="modal fade" id="restoreModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Restaurar Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <h6 class="alert-heading">⚠️ ¡ADVERTENCIA!</h6>
                    <p class="mb-0">
                        La restauración de backup <strong>sobrescribirá todos los datos actuales</strong>. 
                        Esta acción no se puede deshacer. Asegúrese de tener un backup reciente antes de continuar.
                    </p>
                </div>
                <p>¿Está seguro de que desea restaurar el backup: <strong id="restoreBackupName"></strong>?</p>
                
                <form id="restoreForm" action="{{ route('backups.restaurar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="archivo" id="restoreBackupFile">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="confirmar" id="confirmRestore" required>
                            <label class="form-check-label" for="confirmRestore">
                                Confirmo que entiendo los riesgos y quiero proceder con la restauración
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="restoreForm" class="btn btn-danger">Restaurar Backup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Eliminar Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar permanentemente el backup: <strong id="deleteBackupName"></strong>?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Esta acción no se puede deshacer. El archivo de backup será eliminado permanentemente.
                </div>
                
                <form id="deleteForm" action="{{ route('backups.eliminar') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="archivo" id="deleteBackupFile">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="confirmar" id="confirmDelete" required>
                            <label class="form-check-label" for="confirmDelete">
                                Confirmo que quiero eliminar este backup permanentemente
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="deleteForm" class="btn btn-danger">Eliminar Backup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Modal de restauración
        const restoreModal = document.getElementById('restoreModal');
        restoreModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const backupName = button.getAttribute('data-backup-name');
            
            document.getElementById('restoreBackupName').textContent = backupName;
            document.getElementById('restoreBackupFile').value = backupName;
        });

        // Modal de eliminación
        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const backupName = button.getAttribute('data-backup-name');
            
            document.getElementById('deleteBackupName').textContent = backupName;
            document.getElementById('deleteBackupFile').value = backupName;
        });

        // Reset checkboxes cuando se cierran los modals
        const modals = [restoreModal, deleteModal];
        modals.forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function() {
                const checkboxes = this.querySelectorAll('input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            });
        });
    });
</script>
@endpush