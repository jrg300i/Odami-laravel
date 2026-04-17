<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrabajoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'cliente_id' => 'required|exists:clientes,id',
            'titulo' => 'required|string|max:200',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|in:silla,sofa,sillon,cabecero,butaca,personalizado',
            'estado' => 'required|in:presupuesto,en_proceso,completado,entregado,cancelado',
            'costo_estimado' => 'required|numeric|min:0',
            'costo_final' => 'nullable|numeric|min:0',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin_estimada' => 'nullable|date|after_or_equal:fecha_inicio',
            'fecha_fin_real' => 'nullable|date|after_or_equal:fecha_inicio',
            'prioridad' => 'required|integer|min:1|max:5',
            'notas_internas' => 'nullable|string',
            'observaciones_cliente' => 'nullable|string',
            'urgente' => 'boolean',
            'materiales' => 'nullable|array',
            'materiales.*' => 'exists:materiales,id',
        ];
    }

    public function messages()
    {
        return [
            'cliente_id.required' => 'El cliente es obligatorio',
            'cliente_id.exists' => 'El cliente seleccionado no existe',
            'titulo.required' => 'El título del trabajo es obligatorio',
            'tipo.required' => 'El tipo de trabajo es obligatorio',
            'costo_estimado.required' => 'El costo estimado es obligatorio',
            'costo_estimado.numeric' => 'El costo estimado debe ser un número',
            'fecha_fin_estimada.after_or_equal' => 'La fecha de fin estimada debe ser posterior a la fecha de inicio',
        ];
    }
}