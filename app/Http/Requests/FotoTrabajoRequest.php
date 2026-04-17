<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FotoTrabajoRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'fotos' => 'required|array|min:1|max:10',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max
            'fase' => 'required|in:antes,durante,despues',
            'titulo' => 'nullable|string|max:200',
            'descripcion' => 'nullable|string',
            'es_principal' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'fotos.required' => 'Debe seleccionar al menos una foto',
            'fotos.array' => 'Las fotos deben ser un array',
            'fotos.min' => 'Debe seleccionar al menos una foto',
            'fotos.max' => 'No puede subir más de 10 fotos a la vez',
            'fotos.*.image' => 'Cada archivo debe ser una imagen válida',
            'fotos.*.mimes' => 'Solo se permiten imágenes JPEG, PNG, JPG y GIF',
            'fotos.*.max' => 'Cada imagen no puede pesar más de 10MB',
            'fase.required' => 'La fase del trabajo es obligatoria',
        ];
    }
}