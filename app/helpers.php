<?php

// Funciones helper globales para Tapicería Odami

if (!function_exists('estado_color')) {
    /**
     * Obtener el color CSS para un estado
     */
    function estado_color($estado): string
    {
        return match($estado) {
            'presupuesto', 'borrador' => 'warning',
            'en_proceso', 'emitida' => 'info',
            'completado', 'pagada', 'entregado' => 'success',
            'cancelado', 'cancelada' => 'danger',
            'vencida' => 'dark',
            default => 'secondary'
        };
    }
}

if (!function_exists('formatear_moneda')) {
    /**
     * Formatear cantidad como moneda
     */
    function formatear_moneda($cantidad, $moneda = '€'): string
    {
        return number_format($cantidad, 2, ',', '.') . ' ' . $moneda;
    }
}

if (!function_exists('dias_restantes')) {
    /**
     * Calcular días restantes hasta una fecha
     */
    function dias_restantes($fecha): ?int
    {
        if (!$fecha) {
            return null;
        }

        $fecha = $fecha instanceof \Carbon\Carbon ? $fecha : \Carbon\Carbon::parse($fecha);
        return now()->diffInDays($fecha, false);
    }
}

if (!function_exists('obtener_configuracion')) {
    /**
     * Obtener valor de configuración
     */
    function obtener_configuracion($clave, $default = null)
    {
        return \App\Models\Configuracion::obtener($clave, $default);
    }
}

if (!function_exists('tamanio_archivo_formateado')) {
    /**
     * Formatear tamaño de archivo en formato legible
     */
    function tamanio_archivo_formateado($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}

if (!function_exists('generar_codigo_unico')) {
    /**
     * Generar código único basado en prefijo y secuencia
     */
    function generar_codigo_unico($modelo, $prefijo, $campo = 'codigo'): string
    {
        $ultimo = $modelo::orderBy('id', 'desc')->first();
        $numero = $ultimo ? intval(substr($ultimo->$campo, strlen($prefijo))) + 1 : 1;
        
        return $prefijo . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}

if (!function_exists('validar_imagen')) {
    /**
     * Validar archivo de imagen
     */
    function validar_imagen($archivo): bool
    {
        $extensionesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
        $mimeTypesPermitidos = ['image/jpeg', 'image/png', 'image/gif'];
        
        $extension = strtolower($archivo->getClientOriginalExtension());
        $mimeType = $archivo->getMimeType();
        
        return in_array($extension, $extensionesPermitidas) && 
               in_array($mimeType, $mimeTypesPermitidos);
    }
}

if (!function_exists('obtener_estadisticas_rapidas')) {
    /**
     * Obtener estadísticas rápidas para el dashboard
     */
    function obtener_estadisticas_rapidas(): array
    {
        return [
            'clientes' => \App\Models\Cliente::count(),
            'trabajos_activos' => \App\Models\Trabajo::whereIn('estado', ['presupuesto', 'en_proceso'])->count(),
            'facturas_pendientes' => \App\Models\Factura::where('estado', 'emitida')->count(),
            'ingresos_mes' => \App\Models\Pago::where('estado', 'completado')
                ->whereMonth('fecha_pago', now()->month)
                ->sum('monto'),
        ];
    }
}