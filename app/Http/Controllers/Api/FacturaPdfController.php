<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class FacturaPdfController extends Controller
{
    /**
     * Generar vista HTML para impresión de factura
     */
    public function imprimir($id)
    {
        $factura = Factura::with(['trabajo.cliente', 'emisor'])
            ->findOrFail($id);

        $data = [
            'factura' => $factura,
            'cliente' => $factura->trabajo?->cliente,
            'trabajo' => $factura->trabajo,
            'emisor' => $factura->emisor,
            'numero_factura' => $factura->numero_factura,
            'tipo' => $factura->tipo,
            'fecha_emision' => $factura->fecha_emision,
            'subtotal' => $factura->subtotal,
            'igv' => $factura->igv,
            'total' => $factura->total,
            'estado_pago' => $factura->estado_pago,
            'observaciones' => $factura->observaciones,
        ];

        return view('facturas.imprimir', $data);
    }

    /**
     * Generar PDF de factura
     */
    public function generarPdf($id)
    {
        $factura = Factura::with(['trabajo.cliente', 'emisor'])
            ->findOrFail($id);

        $pdf = \PDF::loadView('facturas.imprimir', [
            'factura' => $factura,
            'cliente' => $factura->trabajo?->cliente,
            'trabajo' => $factura->trabajo,
            'emisor' => $factura->emisor,
        ]);

        $nombreArchivo = "Factura-{$factura->numero_factura}-{$factura->tipo}.pdf";

        return $pdf->download($nombreArchivo);
    }
}
