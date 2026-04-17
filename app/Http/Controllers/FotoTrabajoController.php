<?php

namespace App\Http\Controllers;

use App\Models\FotoTrabajo;
use App\Models\Trabajo;
use App\Http\Requests\FotoTrabajoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class FotoTrabajoController extends Controller
{
    public function index(Trabajo $trabajo)
    {
        $fotos = $trabajo->fotos()->orderBy('fase')->orderBy('orden')->get();
        
        return view('fotos.index', compact('trabajo', 'fotos'));
    }

    public function create(Trabajo $trabajo)
    {
        return view('fotos.create', compact('trabajo'));
    }

    public function store(FotoTrabajoRequest $request, Trabajo $trabajo)
    {
        try {
            $fotosSubidas = [];

            foreach ($request->file('fotos') as $foto) {
                $fotoData = $this->procesarFoto($foto, $trabajo);
                $fotoData['trabajo_id'] = $trabajo->id;
                $fotoData['fase'] = $request->fase;
                $fotoData['titulo'] = $request->titulo;
                $fotoData['descripcion'] = $request->descripcion;

                $fotoTrabajo = FotoTrabajo::create($fotoData);
                $fotosSubidas[] = $fotoTrabajo;
            }

            // Si se marcó alguna como principal, establecer la primera como principal
            if ($request->es_principal && count($fotosSubidas) > 0) {
                $fotosSubidas[0]->marcarComoPrincipal();
            }

            return redirect()->route('trabajos.fotos.index', $trabajo)
                            ->with('success', 'Fotos subidas exitosamente.');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error al subir las fotos: ' . $e->getMessage());
        }
    }

    public function show(FotoTrabajo $foto)
    {
        $foto->load('trabajo');
        
        return view('fotos.show', compact('foto'));
    }

    public function destroy(FotoTrabajo $foto)
    {
        // Eliminar archivos físicos
        Storage::delete([
            'public/' . $foto->ruta_original,
            'public/' . $foto->ruta_miniatura,
            'public/' . $foto->ruta_comprimida
        ]);

        $trabajo_id = $foto->trabajo_id;
        $foto->delete();

        return redirect()->route('trabajos.fotos.index', $trabajo_id)
                        ->with('success', 'Foto eliminada exitosamente.');
    }

    public function marcarPrincipal(FotoTrabajo $foto)
    {
        $foto->marcarComoPrincipal();

        return redirect()->back()
                        ->with('success', 'Foto marcada como principal.');
    }

    public function galeria(Trabajo $trabajo)
    {
        $fotos = $trabajo->fotos()
                         ->orderBy('fase')
                         ->orderBy('orden')
                         ->get()
                         ->groupBy('fase');

        return view('fotos.galeria', compact('trabajo', 'fotos'));
    }

    private function procesarFoto($foto, Trabajo $trabajo)
    {
        $nombreOriginal = $foto->getClientOriginalName();
        $extension = $foto->getClientOriginalExtension();
        $nombreArchivo = 'foto_' . time() . '_' . uniqid() . '.' . $extension;

        // Rutas
        $rutaCarpeta = "trabajos/{$trabajo->id}/fotos";
        $rutaOriginal = "{$rutaCarpeta}/original/{$nombreArchivo}";
        $rutaMiniatura = "{$rutaCarpeta}/miniatura/{$nombreArchivo}";
        $rutaComprimida = "{$rutaCarpeta}/comprimida/{$nombreArchivo}";

        // Guardar original
        Storage::putFileAs("public/{$rutaCarpeta}/original", $foto, $nombreArchivo);
        $tamanioOriginal = $foto->getSize() / 1024; // KB

        // Crear miniatura (300x300)
        $miniatura = Image::make($foto->getRealPath())
                         ->fit(300, 300, function ($constraint) {
                             $constraint->aspectRatio();
                             $constraint->upsize();
                         })
                         ->encode($extension, 80);
        
        Storage::put("public/{$rutaMiniatura}", $miniatura->getEncoded());

        // Crear versión comprimida (1200x1200 máximo)
        $comprimida = Image::make($foto->getRealPath())
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
            'mime_type' => $foto->getMimeType(),
            'extension' => $extension,
            'dimensions' => [
                'width' => $comprimida->width(),
                'height' => $comprimida->height(),
            ]
        ];

        return [
            'ruta_original' => $rutaOriginal,
            'ruta_miniatura' => $rutaMiniatura,
            'ruta_comprimida' => $rutaComprimida,
            'tamanio_original' => $tamanioOriginal,
            'tamanio_comprimido' => $tamanioComprimido,
            'metadata' => $metadata
        ];
    }
}