<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PagoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'factura_id' => 'required|exists:facturas,id',
            'cliente_id' => 'required|exists:clientes,id',
            'monto' => 'required|numeric|min:0.01',
            'fecha_pago' => 'required|date',
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta,cheque',
            'referencia' => 'nullable|string|max:100',
            'observaciones' => 'nullable|string',
            'estado' => 'required|in:pendiente,completado,fallido,reembolsado',
            'comprobante' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB
        ];
    }

    public function messages()
    {
        return [
            'factura_id.required' => 'La factura es obligatoria',
            'cliente_id.required' => 'El cliente es obligatorio',
            'monto.required' => 'El monto es obligatorio',
            'monto.min' => 'El monto debe ser mayor a 0',
            'fecha_pago.required' => 'La fecha de pago es obligatoria',
            'metodo_pago.required' => 'El método de pago es obligatorio',
        ];
    }
}