<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Models\FotoTrabajo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request para validar la creación/actualización de fotos de trabajos
 */
final class StoreFotoTrabajoRequest extends FormRequest
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
            'foto_base64' => [
                'required',
                'string',
                // Validar que sea un string base64 válido de imagen
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (!preg_match('/^data:image\/(jpeg|jpg|png|webp);base64,[a-zA-Z0-9\/+=]+$/', $value)) {
                        $fail('El campo foto_base64 debe ser una imagen válida en formato base64 (data:image/jpeg;base64, ...).');
                    }
                    
                    // Validar tamaño máximo (5MB)
                    $base64String = explode(',', $value)[1] ?? '';
                    $sizeInBytes = (int) (strlen($base64String) * 3 / 4);
                    $maxSize = 5 * 1024 * 1024; // 5MB
                    
                    if ($sizeInBytes > $maxSize) {
                        $fail('La imagen no debe superar los 5MB de peso.');
                    }
                },
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
            'foto_base64.required' => 'La foto es requerida',
            'foto_base64.string' => 'La foto debe ser un string en base64',
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
            'foto_base64' => 'foto',
            'descripcion' => 'descripción',
        ];
    }
}
