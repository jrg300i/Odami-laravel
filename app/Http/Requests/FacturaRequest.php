<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FacturaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'trabajo_id' => 'nullable|exists:trabajos,id',
            'serie' => 'required|string|max:1',
            'fecha_emision' => 'required|date',
            'fecha_vencimiento' => 'nullable|date|after:fecha_emision',
            'iva' => 'required|numeric|min:0|max:100',
            'concepto' => 'required|string|max:500',
            'observaciones' => 'nullable|string',
            'forma_pago' => 'nullable|string|max:50',
            'incluir_clausulas' => 'boolean',
            'lineas' => 'nullable|array',
            'lineas.*.descripcion' => 'required|string|max:255',
            'lineas.*.cantidad' => 'required|numeric|min:0.01',
            'lineas.*.precio' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'cliente_id.required' => 'El cliente es obligatorio',
            'serie.required' => 'La serie de facturación es obligatoria',
            'fecha_emision.required' => 'La fecha de emisión es obligatoria',
            'fecha_vencimiento.after' => 'La fecha de vencimiento debe ser posterior a la fecha de emisión',
            'iva.required' => 'El IVA es obligatorio',
            'concepto.required' => 'El concepto es obligatorio',
        ];
    }
}