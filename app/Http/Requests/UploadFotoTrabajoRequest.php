<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\FotoTrabajo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request para validar la subida de fotos desde archivo
 */
final class UploadFotoTrabajoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Todos los usuarios autenticados pueden subir fotos
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trabajo_id' => [
                'required',
                'integer',
                'exists:trabajos,id',
            ],
            'tipo' => [
                'required',
                'string',
                Rule::in(FotoTrabajo::TIPOS_VALIDOS),
            ],
            'foto' => [
                'required',
                'file',
                'image', // Solo archivos de imagen
                'mimes:jpeg,jpg,png,webp', // Tipos permitidos
                'max:5120', // Máximo 5MB (en KB)
                'dimensions:min_width=100,min_height=100,max_width=4096,max_height=4096',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'trabajo_id.required' => 'El ID del trabajo es requerido',
            'trabajo_id.exists' => 'El trabajo especificado no existe',
            'tipo.required' => 'El tipo de foto es requerido',
            'tipo.in' => 'El tipo de foto debe ser: recepcion, proceso o final',
            'foto.required' => 'Debe seleccionar una imagen',
            'foto.file' => 'El archivo debe ser una imagen',
            'foto.image' => 'El archivo debe ser una imagen válida',
            'foto.mimes' => 'La imagen debe ser formato: jpeg, jpg, png o webp',
            'foto.max' => 'La imagen no debe superar los 5MB de peso',
            'foto.dimensions' => 'La imagen debe tener dimensiones entre 100x100 y 4096x4096 píxeles',
            'descripcion.max' => 'La descripción no debe superar los 500 caracteres',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'trabajo_id' => 'trabajo',
            'tipo' => 'tipo de foto',
            'foto' => 'imagen',
            'descripcion' => 'descripción',
        ];
    }
}
