<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PagosSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar que existen facturas y clientes
        $facturas = DB::table('facturas')
            ->select('id', 'cliente_id', 'total', 'estado', 'fecha_emision', 'forma_pago', 'fecha_pago')
            ->get()
            ->toArray();
        
        $clientes = DB::table('clientes')->pluck('id')->toArray();
        
        if (empty($facturas) || empty($clientes)) {
            $this->command->error('Primero ejecuta los seeders de facturas y clientes!');
            return;
        }

        $pagos = [];
        $referenciasUsadas = [];

        foreach ($facturas as $factura) {
            // Solo crear pagos para facturas pagadas o con pagos parciales
            if (in_array($factura->estado, ['pagada', 'emitida', 'vencida'])) {
                
                // Decidir si la factura tiene pago completo, parcial o múltiples pagos
                $tipoPago = rand(1, 10);
                
                if ($factura->estado === 'pagada') {
                    // Factura pagada - pago completo
                    $this->agregarPagoCompleto($pagos, $factura, $referenciasUsadas);
                    
                } elseif ($factura->estado === 'emitida') {
                    // Factura emitida - puede tener pago parcial, completo o ninguno
                    if ($tipoPago <= 3) { // 30% pagadas completamente
                        $this->agregarPagoCompleto($pagos, $factura, $referenciasUsadas);
                        // Actualizar estado de factura a pagada
                        DB::table('facturas')->where('id', $factura->id)->update(['estado' => 'pagada']);
                    } elseif ($tipoPago <= 6) { // 30% pago parcial
                        $this->agregarPagoParcial($pagos, $factura, $referenciasUsadas);
                    }
                    // 40% sin pago (quedan como emitidas)
                    
                } elseif ($factura->estado === 'vencida') {
                    // Factura vencida - puede tener pago parcial, completo o ninguno
                    if ($tipoPago <= 2) { // 20% pagadas completamente
                        $this->agregarPagoCompleto($pagos, $factura, $referenciasUsadas);
                        DB::table('facturas')->where('id', $factura->id)->update(['estado' => 'pagada']);
                    } elseif ($tipoPago <= 5) { // 30% pago parcial
                        $this->agregarPagoParcial($pagos, $factura, $referenciasUsadas);
                    }
                    // 50% sin pago (quedan como vencidas)
                }
            }
        }

        // Insertar pagos en lotes
        $chunks = array_chunk($pagos, 50);
        foreach ($chunks as $chunk) {
            DB::table('pagos')->insert($chunk);
        }

        $this->command->info('Seeder de pagos ejecutado exitosamente!');
        $this->command->info('Total de pagos insertados: ' . count($pagos));
        
        // Estadísticas
        $this->command->info("\nResumen por estado:");
        $estados = array_column($pagos, 'estado');
        $counts = array_count_values($estados);
        foreach ($counts as $estado => $cantidad) {
            $this->command->info("- $estado: $cantidad");
        }
        
        $this->command->info("\nResumen por método de pago:");
        $metodos = array_column($pagos, 'metodo_pago');
        $metodosCount = array_count_values($metodos);
        foreach ($metodosCount as $metodo => $cantidad) {
            $this->command->info("- $metodo: $cantidad");
        }
        
        $totalRecaudado = array_sum(array_column($pagos, 'monto'));
        $this->command->info("\nTotal recaudado: " . number_format($totalRecaudado, 2) . " €");
    }
    
    private function agregarPagoCompleto(&$pagos, $factura, &$referenciasUsadas)
    {
        $metodoPago = $factura->forma_pago ?? $this->obtenerMetodoPagoAleatorio();
        $fechaPago = $factura->fecha_pago ?? Carbon::parse($factura->fecha_emision)->addDays(rand(1, 30));
        
        $pago = [
            'factura_id' => $factura->id,
            'cliente_id' => $factura->cliente_id,
            'monto' => $factura->total,
            'fecha_pago' => $fechaPago instanceof \DateTime ? $fechaPago->format('Y-m-d') : $fechaPago,
            'metodo_pago' => $metodoPago,
            'referencia' => $this->generarReferenciaUnica($metodoPago, $referenciasUsadas),
            'observaciones' => $this->generarObservacionesPago($metodoPago, $factura->total),
            'estado' => 'completado',
            'comprobante_path' => $this->generarRutaComprobante($metodoPago, $factura->id),
            'created_at' => $fechaPago instanceof \DateTime ? $fechaPago : Carbon::parse($fechaPago),
            'updated_at' => now(),
        ];
        
        $pagos[] = $pago;
    }
    
    private function agregarPagoParcial(&$pagos, $factura, &$referenciasUsadas)
    {
        // Crear 1-3 pagos parciales
        $numPagos = rand(1, 3);
        $montoRestante = $factura->total;
        $fechaBase = Carbon::parse($factura->fecha_emision);
        
        for ($i = 0; $i < $numPagos && $montoRestante > 0; $i++) {
            if ($i === $numPagos - 1) {
                // Último pago: el resto
                $monto = $montoRestante;
                $estado = 'completado';
            } else {
                // Pago parcial
                $monto = min($montoRestante * rand(30, 70) / 100, $montoRestante * 0.8);
                $monto = round($monto, 2);
                $estado = rand(0, 1) ? 'completado' : 'pendiente';
            }
            
            $metodoPago = $this->obtenerMetodoPagoAleatorio();
            $fechaPago = $fechaBase->copy()->addDays(rand(1, 60));
            
            $pago = [
                'factura_id' => $factura->id,
                'cliente_id' => $factura->cliente_id,
                'monto' => $monto,
                'fecha_pago' => $fechaPago->format('Y-m-d'),
                'metodo_pago' => $metodoPago,
                'referencia' => $this->generarReferenciaUnica($metodoPago, $referenciasUsadas),
                'observaciones' => $this->generarObservacionesPago($metodoPago, $monto, $i + 1, $numPagos),
                'estado' => $estado,
                'comprobante_path' => $estado === 'completado' ? $this->generarRutaComprobante($metodoPago, $factura->id) : null,
                'created_at' => $fechaPago,
                'updated_at' => now(),
            ];
            
            $pagos[] = $pago;
            $montoRestante -= $monto;
            $montoRestante = round($montoRestante, 2);
        }
    }
    
    private function obtenerMetodoPagoAleatorio()
    {
        $metodos = ['efectivo', 'transferencia', 'tarjeta'];
        $probabilidades = [30, 50, 20]; // Probabilidades en porcentaje
        
        $rand = rand(1, 100);
        $acumulado = 0;
        
        for ($i = 0; $i < count($metodos); $i++) {
            $acumulado += $probabilidades[$i];
            if ($rand <= $acumulado) {
                return $metodos[$i];
            }
        }
        
        return 'transferencia';
    }
    
    private function generarReferenciaUnica($metodoPago, &$referenciasUsadas)
    {
        do {
            switch ($metodoPago) {
                case 'transferencia':
                    $referencia = 'TRF-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
                    break;
                case 'tarjeta':
                    $referencia = 'TARJ-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) . '-' . rand(1000, 9999);
                    break;
                case 'efectivo':
                    $referencia = 'EFECT-' . date('dmY') . '-' . rand(100, 999);
                    break;
                default:
                    $referencia = 'REF-' . uniqid();
            }
        } while (in_array($referencia, $referenciasUsadas));
        
        $referenciasUsadas[] = $referencia;
        return $referencia;
    }
    
    private function generarObservacionesPago($metodoPago, $monto, $numeroPago = 1, $totalPagos = 1)
    {
        $observaciones = [];
        
        if ($totalPagos > 1) {
            $observaciones[] = "Pago {$numeroPago} de {$totalPagos}";
        }
        
        switch ($metodoPago) {
            case 'transferencia':
                $observaciones[] = 'Transferencia bancaria confirmada';
                $observaciones[] = 'Concepto: Pago factura tapicería';
                if (rand(0, 1)) {
                    $observaciones[] = 'Comisión bancaria incluida';
                }
                break;
                
            case 'tarjeta':
                $observaciones[] = 'Pago con tarjeta de crédito';
                $observaciones[] = 'Terminal: TPV-' . rand(100, 999);
                $observaciones[] = 'Operación autorizada';
                break;
                
            case 'efectivo':
                $observaciones[] = 'Pago en efectivo';
                $observaciones[] = 'Recibido en caja';
                if ($monto > 1000) {
                    $observaciones[] = 'Se emitió recibo de caja';
                }
                break;
        }
        
        // Agregar observaciones aleatorias
        $obsExtra = [
            'Cliente puntual',
            'Pago anticipado',
            'Con descuento por pronto pago',
            'Incluye IVA',
            'Factura adjuntada',
            'Confirmado por email',
            'Comprobante digital enviado',
            'Pago programado',
        ];
        
        if (rand(0, 1)) {
            $observaciones[] = $obsExtra[array_rand($obsExtra)];
        }
        
        return implode('. ', $observaciones);
    }
    
    private function generarRutaComprobante($metodoPago, $facturaId)
    {
        $mes = date('m');
        $anio = date('Y');
        
        switch ($metodoPago) {
            case 'transferencia':
                return "comprobantes/{$anio}/{$mes}/transferencia_factura_{$facturaId}.pdf";
            case 'tarjeta':
                return "comprobantes/{$anio}/{$mes}/ticket_tarjeta_{$facturaId}.pdf";
            case 'efectivo':
                return "comprobantes/{$anio}/{$mes}/recibo_efectivo_{$facturaId}.pdf";
            default:
                return "comprobantes/{$anio}/{$mes}/comprobante_{$facturaId}.pdf";
        }
    }
}