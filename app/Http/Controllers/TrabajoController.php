<?php

namespace App\Http\Controllers;

use App\Models\Trabajo;
use App\Models\Cliente;
use App\Models\Material;
use App\Http\Requests\TrabajoRequest;
use Illuminate\Http\Request;

class TrabajoController extends Controller
{
    public function index(Request $request)
    {
        $query = Trabajo::with(['cliente', 'fotos']);

        // Búsqueda
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Filtros
        if ($request->has('estado')) {
            $query->porEstado($request->estado);
        }

        if ($request->has('tipo')) {
            $query->porTipo($request->tipo);
        }

        if ($request->has('urgente')) {
            $query->urgentes();
        }

        $trabajos = $query->orderBy('prioridad', 'desc')
                         ->orderBy('created_at', 'desc')
                         ->paginate(12);

        $estadisticas = [
            'total' => Trabajo::count(),
            'en_proceso' => Trabajo::where('estado', 'en_proceso')->count(),
            'completados' => Trabajo::where('estado', 'completado')->count(),
            'urgentes' => Trabajo::where('urgente', true)->count(),
        ];

        return view('trabajos.index', compact('trabajos', 'estadisticas'));
    }

    public function create()
    {
        $clientes = Cliente::activo()->get();
        $materiales = Material::activos()->get();
        
        return view('trabajos.create', compact('clientes', 'materiales'));
    }

    public function store(TrabajoRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['codigo_trabajo'] = $this->generarCodigoTrabajo();

        $trabajo = Trabajo::create($data);

        // Asignar materiales si se proporcionaron
        if ($request->has('materiales')) {
            $materialesData = [];
            foreach ($request->materiales as $materialId => $cantidad) {
                if ($cantidad > 0) {
                    $material = Material::find($materialId);
                    $materialesData[$materialId] = [
                        'cantidad' => $cantidad,
                        'unidad_medida' => 'metros',
                        'costo_total' => $cantidad * $material->precio_metro
                    ];
                }
            }
            $trabajo->materiales()->sync($materialesData);
        }

        return redirect()->route('trabajos.show', $trabajo)
                        ->with('success', 'Trabajo creado exitosamente.');
    }

    public function show(Trabajo $trabajo)
    {
        $trabajo->load(['cliente', 'materiales', 'fotos', 'facturas.pagos']);
        
        $costos = [
            'materiales' => $trabajo->calcularCostosMateriales(),
            'estimado' => $trabajo->costo_estimado,
            'final' => $trabajo->costo_final,
        ];

        return view('trabajos.show', compact('trabajo', 'costos'));
    }

    public function edit(Trabajo $trabajo)
    {
        $clientes = Cliente::activo()->get();
        $materiales = Material::activos()->get();
        
        return view('trabajos.edit', compact('trabajo', 'clientes', 'materiales'));
    }

    public function update(TrabajoRequest $request, Trabajo $trabajo)
    {
        $trabajo->update($request->validated());

        // Actualizar materiales
        if ($request->has('materiales')) {
            $materialesData = [];
            foreach ($request->materiales as $materialId => $cantidad) {
                if ($cantidad > 0) {
                    $material = Material::find($materialId);
                    $materialesData[$materialId] = [
                        'cantidad' => $cantidad,
                        'unidad_medida' => 'metros',
                        'costo_total' => $cantidad * $material->precio_metro
                    ];
                }
            }
            $trabajo->materiales()->sync($materialesData);
        }

        return redirect()->route('trabajos.show', $trabajo)
                        ->with('success', 'Trabajo actualizado exitosamente.');
    }

    public function destroy(Trabajo $trabajo)
    {
        if ($trabajo->facturas()->exists()) {
            return redirect()->back()
                            ->with('error', 'No se puede eliminar el trabajo porque tiene facturas asociadas.');
        }

        $trabajo->delete();

        return redirect()->route('trabajos.index')
                        ->with('success', 'Trabajo eliminado exitosamente.');
    }

    public function completar(Trabajo $trabajo)
    {
        $trabajo->marcarComoCompletado();

        return redirect()->back()
                        ->with('success', 'Trabajo marcado como completado.');
    }

    private function generarCodigoTrabajo()
    {
        $ultimoTrabajo = Trabajo::orderBy('id', 'desc')->first();
        $numero = $ultimoTrabajo ? intval(substr($ultimoTrabajo->codigo_trabajo, 2)) + 1 : 1;
        
        return 'TR' . str_pad($numero, 4, '0', STR_PAD_LEFT);
    }
}