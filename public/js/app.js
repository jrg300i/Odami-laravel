// JavaScript principal para Tapicería Odami

document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

function initializeApp() {
    // Inicializar tooltips de Bootstrap
    initializeTooltips();
    
    // Inicializar confirmaciones
    initializeConfirmations();
    
    // Inicializar formularios
    initializeForms();
    
    // Inicializar búsquedas
    initializeSearch();
    
    // Inicializar galerías
    initializeGalleries();
}

// Tooltips
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// Confirmaciones para acciones destructivas
function initializeConfirmations() {
    const confirmButtons = document.querySelectorAll('[data-confirm]');
    
    confirmButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || '¿Está seguro de realizar esta acción?';
            if (!confirm(message)) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
}

// Manejo de formularios
function initializeForms() {
    // Validación de fechas
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            validateDateRange(this);
        });
    });
    
    // Auto-submit en selects de filtros
    const autoSubmitSelects = document.querySelectorAll('select[onchange*="submit"]');
    autoSubmitSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
    
    // Contadores de caracteres
    const textareas = document.querySelectorAll('textarea[data-max-length]');
    textareas.forEach(textarea => {
        initializeCharacterCounter(textarea);
    });
}

// Validación de rangos de fecha
function validateDateRange(dateInput) {
    const form = dateInput.closest('form');
    const startDateInput = form.querySelector('input[name="fecha_inicio"]');
    const endDateInput = form.querySelector('input[name="fecha_fin"]');
    
    if (startDateInput && endDateInput) {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        if (startDate && endDate && startDate > endDate) {
            alert('La fecha de inicio no puede ser posterior a la fecha de fin.');
            dateInput.value = '';
        }
    }
}

// Contador de caracteres para textareas
function initializeCharacterCounter(textarea) {
    const maxLength = textarea.getAttribute('data-max-length');
    if (!maxLength) return;
    
    const counter = document.createElement('div');
    counter.className = 'form-text text-end character-counter';
    updateCharacterCounter(textarea, counter, maxLength);
    
    textarea.parentNode.appendChild(counter);
    
    textarea.addEventListener('input', function() {
        updateCharacterCounter(this, counter, maxLength);
    });
}

function updateCharacterCounter(textarea, counter, maxLength) {
    const currentLength = textarea.value.length;
    const remaining = maxLength - currentLength;
    
    counter.textContent = `${currentLength}/${maxLength} caracteres`;
    
    if (remaining < 0) {
        counter.classList.add('text-danger');
    } else if (remaining < 50) {
        counter.classList.add('text-warning');
    } else {
        counter.classList.remove('text-danger', 'text-warning');
    }
}

// Búsquedas en tiempo real
function initializeSearch() {
    const searchInputs = document.querySelectorAll('input[type="search"], input[name="search"]');
    
    searchInputs.forEach(input => {
        let timeout = null;
        
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            
            timeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    });
}

// Galerías de imágenes
function initializeGalleries() {
    // Lightbox para galerías
    const galleryLinks = document.querySelectorAll('a[data-lightbox]');
    
    galleryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (window.innerWidth < 768) {
                // En móviles, abrir imagen en nueva pestaña
                e.preventDefault();
                window.open(this.href, '_blank');
            }
        });
    });
}

// Funciones utilitarias
class TapiceriaUtils {
    // Formatear moneda
    static formatCurrency(amount, currency = '€') {
        return new Intl.NumberFormat('es-ES', {
            style: 'currency',
            currency: 'EUR'
        }).format(amount);
    }
    
    // Formatear fecha
    static formatDate(date, format = 'es-ES') {
        return new Intl.DateTimeFormat(format).format(new Date(date));
    }
    
    // Descargar archivo
    static downloadFile(url, filename) {
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    
    // Mostrar notificación
    static showNotification(message, type = 'info') {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Agregar al contenedor de alertas o crear uno
        let alertContainer = document.querySelector('.alert-container');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.className = 'alert-container position-fixed top-0 end-0 p-3';
            alertContainer.style.zIndex = '9999';
            document.body.appendChild(alertContainer);
        }
        
        alertContainer.appendChild(alert);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 5000);
    }
}

// API calls
class TapiceriaAPI {
    static async get(endpoint) {
        try {
            const response = await fetch(endpoint);
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }
    
    static async post(endpoint, data) {
        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });
            return await response.json();
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    }
}

// Componentes específicos
class BackupManager {
    static init() {
        this.initializeBackupProgress();
        this.initializeAutoRefresh();
    }
    
    static initializeBackupProgress() {
        const backupButtons = document.querySelectorAll('[data-backup-action]');
        
        backupButtons.forEach(button => {
            button.addEventListener('click', function() {
                const action = this.getAttribute('data-backup-action');
                this.classList.add('disabled');
                this.innerHTML = '<span class="loading-spinner me-2"></span> Procesando...';
                
                // Simular progreso (en producción esto vendría del servidor)
                this.simulateProgress();
            });
        });
    }
    
    static initializeAutoRefresh() {
        // Auto-refresh para logs de backup
        if (window.location.pathname.includes('/backups/logs')) {
            setInterval(() => {
                window.location.reload();
            }, 30000); // Refresh cada 30 segundos
        }
    }
}

class FacturaManager {
    static init() {
        this.initializeLineaCalculations();
        this.initializeIVAUpdate();
    }
    
    static initializeLineaCalculations() {
        const lineasContainer = document.querySelector('#lineas-factura');
        if (!lineasContainer) return;
        
        lineasContainer.addEventListener('input', function(e) {
            if (e.target.matches('[name*="cantidad"], [name*="precio"]')) {
                const linea = e.target.closest('.linea-factura');
                FacturaManager.calcularLinea(linea);
            }
        });
    }
    
    static calcularLinea(linea) {
        const cantidad = parseFloat(linea.querySelector('[name*="cantidad"]').value) || 0;
        const precio = parseFloat(linea.querySelector('[name*="precio"]').value) || 0;
        const iva = parseFloat(document.querySelector('[name="iva"]').value) || 21;
        
        const subtotal = cantidad * precio;
        const totalIVA = subtotal * (iva / 100);
        const total = subtotal + totalIVA;
        
        // Actualizar campos (si existen)
        const totalElement = linea.querySelector('.linea-total');
        if (totalElement) {
            totalElement.textContent = TapiceriaUtils.formatCurrency(total);
        }
    }
    
    static initializeIVAUpdate() {
        const ivaInput = document.querySelector('[name="iva"]');
        if (ivaInput) {
            ivaInput.addEventListener('change', function() {
                // Recalcular todas las líneas
                document.querySelectorAll('.linea-factura').forEach(linea => {
                    FacturaManager.calcularLinea(linea);
                });
            });
        }
    }
}

class TrabajoManager {
    static init() {
        this.initializeMaterialCalculations();
        this.initializePriorityUpdates();
    }
    
    static initializeMaterialCalculations() {
        const materialesContainer = document.querySelector('#materiales-trabajo');
        if (!materialesContainer) return;
        
        materialesContainer.addEventListener('input', function(e) {
            if (e.target.matches('[name*="materiales"]')) {
                TrabajoManager.calcularCostoMateriales();
            }
        });
    }
    
    static calcularCostoMateriales() {
        let total = 0;
        
        document.querySelectorAll('[name*="materiales"]').forEach(input => {
            const cantidad = parseFloat(input.value) || 0;
            const materialId = input.name.match(/\[(\d+)\]/)[1];
            const precioElement = document.querySelector(`[data-precio-material="${materialId}"]`);
            
            if (precioElement) {
                const precio = parseFloat(precioElement.textContent) || 0;
                total += cantidad * precio;
            }
        });
        
        const totalElement = document.querySelector('#costo-materiales-total');
        if (totalElement) {
            totalElement.textContent = TapiceriaUtils.formatCurrency(total);
        }
    }
    
    static initializePriorityUpdates() {
        const prioritySelects = document.querySelectorAll('select[name="prioridad"]');
        
        prioritySelects.forEach(select => {
            select.addEventListener('change', function() {
                const starsContainer = this.parentNode.querySelector('.priority-stars');
                if (starsContainer) {
                    const priority = parseInt(this.value);
                    starsContainer.innerHTML = '';
                    
                    for (let i = 1; i <= 5; i++) {
                        const star = document.createElement('i');
                        star.className = `fas fa-star ${i <= priority ? 'text-warning' : 'text-muted'}`;
                        starsContainer.appendChild(star);
                    }
                }
            });
            
            // Disparar evento change para inicializar
            select.dispatchEvent(new Event('change'));
        });
    }
}

// Inicializar componentes cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    BackupManager.init();
    FacturaManager.init();
    TrabajoManager.init();
});

// Exportar para uso global
window.TapiceriaUtils = TapiceriaUtils;
window.TapiceriaAPI = TapiceriaAPI;