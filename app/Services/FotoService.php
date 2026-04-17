<?php

namespace App\Services;

use App\Models\FotoTrabajo;
use App\Models\Trabajo;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Exception;

class FotoService
{
    public function subirFotos($archivos, Trabajo $trabajo, $fase, $datosAdicionales = [])
    {
        $fotosSubidas = [];

        foreach ($archivos as $archivo) {
            try {
                $fotoData = $this->procesarFoto($archivo, $trabajo);
                $fotoData = array_merge($fotoData, $datosAdicionales);
                $fotoData['trabajo_id'] = $trabajo->id;
                $fotoData['fase'] = $fase;

                $foto = FotoTrabajo::create($fotoData);
                $fotosSubidas[] = $foto;

            } catch (Exception $e) {
                // Si falla una foto, continuar con las demás pero loguear el error
                \Log::error("Error subiendo foto para trabajo {$trabajo->id}: " . $e->getMessage());
                continue;
            }
        }

        return $fotosSubidas;
    }

    public function procesarFoto($archivo, Trabajo $trabajo)
    {
        $nombreOriginal = $archivo->getClientOriginalName();
        $extension = $archivo->getClientOriginalExtension();
        $nombreArchivo = 'foto_' . time() . '_' . uniqid() . '.' . $extension;

        // Rutas
        $rutaCarpeta = "trabajos/{$trabajo->id}/fotos";
        $rutaOriginal = "{$rutaCarpeta}/original/{$nombreArchivo}";
        $rutaMiniatura = "{$rutaCarpeta}/miniatura/{$nombreArchivo}";
        $rutaComprimida = "{$rutaCarpeta}/comprimida/{$nombreArchivo}";

        // Guardar original
        Storage::putFileAs("public/{$rutaCarpeta}/original", $archivo, $nombreArchivo);
        $tamanioOriginal = $archivo->getSize() / 1024; // KB

        // Crear miniatura (300x300)
        $miniatura = Image::make($archivo->getRealPath())
                         ->fit(300, 300, function ($constraint) {
                             $constraint->aspectRatio();
                             $constraint->upsize();
                         })
                         ->encode($extension, 80);
        
        Storage::put("public/{$rutaMiniatura}", $miniatura->getEncoded());

        // Crear versión comprimida (1200x1200 máximo)
        $comprimida = Image::make($archivo->getRealPath())
                          ->resize(1200, 1200, function ($constraint) {
                              $constraint->aspectRatio();
                              $constraint->upsize();
                          })
                          ->encode($extension, 75);
        
        Storage::put("public/{$rutaComprimida}", $comprimida->getEncoded());
        $tamanioComprimido = strlen($comprimida->getEncoded()) / 1024; // KB

        // Metadata
        $metadata = [
            'original_name' => $nombreOriginal,
            'mime_type' => $archivo->getMimeType(),
            'extension' => $extension,
            'dimensions' => [
                'width' => $comprimida->width(),
                'height' => $comprimida->height(),
            ],
            'procesado_en' => now()->toISOString()
        ];

        return [
            'ruta_original' => $rutaOriginal,
            'ruta_miniatura' => $rutaMiniatura,
            'ruta_comprimida' => $rutaComprimida,
            'tamanio_original' => $tamanioOriginal,
            'tamanio_comprimido' => $tamanioComprimido,
            'metadata' => $metadata,
            'titulo' => pathinfo($nombreOriginal, PATHINFO_FILENAME),
        ];
    }

    public function eliminarFoto(FotoTrabajo $foto)
    {
        // Eliminar archivos físicos
        $archivos = [
            'public/' . $foto->ruta_original,
            'public/' . $foto->ruta_miniatura,
            'public/' . $foto->ruta_comprimida
        ];

        foreach ($archivos as $archivo) {
            if (Storage::exists($archivo)) {
                Storage::delete($archivo);
            }
        }

        $foto->delete();
    }

    public function comprimirFotosAntiguas($dias = 30)
    {
        $fechaLimite = now()->subDays($dias);
        $fotos = FotoTrabajo::where('created_at', '<', $fechaLimite)
                           ->whereNull('ruta_comprimida')
                           ->get();

        $comprimidas = 0;

        foreach ($fotos as $foto) {
            try {
                $this->comprimirFotoExistente($foto);
                $comprimidas++;
            } catch (Exception $e) {
                \Log::error("Error comprimiendo foto {$foto->id}: " . $e->getMessage());
            }
        }

        return $comprimidas;
    }

    private function comprimirFotoExistente(FotoTrabajo $foto)
    {
        if (!Storage::exists('public/' . $foto->ruta_original)) {
            throw new Exception("Archivo original no encontrado");
        }

        $rutaOriginal = storage_path('app/public/' . $foto->ruta_original);
        $extension = pathinfo($foto->ruta_original, PATHINFO_EXTENSION);

        // Crear versión comprimida
        $comprimida = Image::make($rutaOriginal)
                          ->resize(1200, 1200, function ($constraint) {
                              $constraint->aspectRatio();
                              $constraint->upsize();
                          })
                          ->encode($extension, 75);

        $rutaComprimida = str_replace('/original/', '/comprimida/', $foto->ruta_original);
        Storage::put("public/{$rutaComprimida}", $comprimida->getEncoded());

        $tamanioComprimido = strlen($comprimida->getEncoded()) / 1024; // KB

        // Actualizar registro
        $foto->update([
            'ruta_comprimida' => $rutaComprimida,
            'tamanio_comprimido' => $tamanioComprimido
        ]);
    }

    public function obtenerUsoEspacio()
    {
        $carpetas = [
            'trabajos' => 'public/trabajos',
            'comprobantes' => 'public/comprobantes',
            'backups' => 'backups'
        ];

        $uso = [];

        foreach ($carpetas as $nombre => $carpeta) {
            if (Storage::exists($carpeta)) {
                $tamanio = $this->calcularTamanioCarpeta($carpeta);
                $uso[$nombre] = [
                    'tamanio_bytes' => $tamanio,
                    'tamanio_mb' => round($tamanio / 1024 / 1024, 2),
                    'tamanio_gb' => round($tamanio / 1024 / 1024 / 1024, 2)
                ];
            }
        }

        return $uso;
    }

    private function calcularTamanioCarpeta($carpeta)
    {
        $tamanio = 0;
        $archivos = Storage::allFiles($carpeta);
        
        foreach ($archivos as $archivo) {
            $tamanio += Storage::size($archivo);
        }

        return $tamanio;
    }

    public function limpiarFotosTemporales($horas = 24)
    {
        $fechaLimite = now()->subHours($horas);
        $carpetaTemporal = 'public/temp';

        if (!Storage::exists($carpetaTemporal)) {
            return 0;
        }

        $archivos = Storage::files($carpetaTemporal);
        $eliminados = 0;

        foreach ($archivos as $archivo) {
            $ultimaModificacion = Storage::lastModified($archivo);
            if ($ultimaModificacion < $fechaLimite->timestamp) {
                Storage::delete($archivo);
                $eliminados++;
            }
        }

        return $eliminados;
    }
}