@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="{{ route('trabajos.fotos.index', $foto->trabajo) }}" class="text-indigo-600 hover:text-indigo-800 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Volver a fotos del trabajo
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="relative bg-gray-900">
                    <img src="{{ $foto->url_original }}" alt="{{ $foto->titulo }}" class="w-full h-auto">
                    @if($foto->es_principal)
                        <div class="absolute top-4 right-4 bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-1">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Principal
                        </div>
                    @endif
                </div>
            </div>

            @if($foto->descripcion)
                <div class="mt-6 bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Descripción</h3>
                    <p class="text-gray-600">{{ $foto->descripcion }}</p>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información de la Foto</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-500">Título</dt>
                        <dd class="text-gray-900 font-medium">{{ $foto->titulo ?? 'Sin título' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Fase</dt>
                        <dd>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @switch($foto->fase)
                                    @case('inicio') bg-blue-100 text-blue-800 @break
                                    @case('proceso') bg-yellow-100 text-yellow-800 @break
                                    @case('final') bg-green-100 text-green-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                {{ ucfirst($foto->fase ?? 'Sin fase') }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Fecha de subida</dt>
                        <dd class="text-gray-900">{{ $foto->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Última modificación</dt>
                        <dd class="text-gray-900">{{ $foto->updated_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Detalles Técnicos</h3>
                <dl class="space-y-3">
                    @if(isset($foto->metadata['dimensions']))
                        <div>
                            <dt class="text-sm text-gray-500">Dimensiones</dt>
                            <dd class="text-gray-900">{{ $foto->metadata['dimensions']['width'] ?? 'N/A' }} × {{ $foto->metadata['dimensions']['height'] ?? 'N/A' }} px</dd>
                        </div>
                    @endif
                    @if(isset($foto->metadata['extension']))
                        <div>
                            <dt class="text-sm text-gray-500">Formato</dt>
                            <dd class="text-gray-900 uppercase">{{ $foto->metadata['extension'] ?? 'N/A' }}</dd>
                        </div>
                    @endif
                    @if(isset($foto->metadata['mime_type']))
                        <div>
                            <dt class="text-sm text-gray-500">Tipo MIME</dt>
                            <dd class="text-gray-900 text-sm">{{ $foto->metadata['mime_type'] ?? 'N/A' }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="text-sm text-gray-500">Tamaño original</dt>
                        <dd class="text-gray-900">{{ number_format($foto->tamanio_original ?? 0, 2) }} KB</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Tamaño comprimido</dt>
                        <dd class="text-gray-900">{{ $foto->tamanio_formateado }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Trabajo Relacionado</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm text-gray-500">Trabajo</dt>
                        <dd class="text-gray-900 font-medium">{{ $foto->trabajo->titulo ?? 'Trabajo #' . $foto->trabajo->id }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Cliente</dt>
                        <dd class="text-gray-900">{{ $foto->trabajo->cliente->nombre ?? 'N/A' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Estado</dt>
                        <dd>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                @switch($foto->trabajo->estado)
                                    @case('pendiente') bg-gray-100 text-gray-800 @break
                                    @case('en_proceso') bg-blue-100 text-blue-800 @break
                                    @case('completado') bg-green-100 text-green-800 @break
                                    @case('cancelado') bg-red-100 text-red-800 @break
                                    @default bg-gray-100 text-gray-800
                                @endswitch">
                                {{ ucfirst(str_replace('_', ' ', $foto->trabajo->estado ?? 'N/A')) }}
                            </span>
                        </dd>
                    </div>
                </dl>
                <a href="{{ route('trabajos.show', $foto->trabajo) }}" class="mt-4 inline-block text-indigo-600 hover:text-indigo-800 text-sm">
                    Ver trabajo completo →
                </a>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones</h3>
                <div class="space-y-3">
                    @if(!$foto->es_principal)
                        <form action="{{ route('fotos.marcarPrincipal', $foto) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-2 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                                Marcar como principal
                            </button>
                        </form>
                    @endif
                    <a href="{{ $foto->url_original }}" target="_blank" class="block w-full px-4 py-2 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 text-center">
                        Ver original
                    </a>
                    <form action="{{ route('fotos.destroy', $foto) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta foto?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-red-100 text-red-800 rounded-lg hover:bg-red-200">
                            Eliminar foto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
