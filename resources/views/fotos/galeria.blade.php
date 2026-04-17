@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Galería de Fotos</h1>
            <p class="text-gray-600">{{ $trabajo->titulo ?? 'Trabajo #' . $trabajo->id }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('trabajos.fotos.index', $trabajo) }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                Gestión de Fotos
            </a>
            <a href="{{ route('trabajos.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                Volver
            </a>
        </div>
    </div>

    <div id="lightbox" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden items-center justify-center p-4">
        <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white hover:text-gray-300">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <button onclick="prevImage()" class="absolute left-4 text-white hover:text-gray-300">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button onclick="nextImage()" class="absolute right-4 text-white hover:text-gray-300">
            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
        <div class="max-w-5xl max-h-full">
            <img id="lightbox-img" src="" alt="" class="max-w-full max-h-[85vh] object-contain rounded-lg">
            <div id="lightbox-info" class="text-center mt-4 text-white">
                <p id="lightbox-title" class="text-xl font-semibold"></p>
                <p id="lightbox-desc" class="text-gray-300 mt-1"></p>
            </div>
        </div>
    </div>

    @forelse($fotos as $fase => $fotosFase)
        <div class="mb-10">
            <div class="flex items-center gap-3 mb-4">
                <h2 class="text-xl font-bold text-gray-800">
                    @switch($fase)
                        @case('inicio') Fase de Inicio @break
                        @case('proceso') En Proceso @break
                        @case('final') Resultado Final @break
                        @default {{ ucfirst($fase) }}
                    @endswitch
                </h2>
                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                    @switch($fase)
                        @case('inicio') bg-blue-100 text-blue-800 @break
                        @case('proceso') bg-yellow-100 text-yellow-800 @break
                        @case('final') bg-green-100 text-green-800 @break
                        @default bg-gray-100 text-gray-800
                    @endswitch">
                    {{ $fotosFase->count() }} {{ $fotosFase->count() == 1 ? 'foto' : 'fotos' }}
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($fotosFase as $foto)
                    <div class="group relative bg-white rounded-lg shadow-md overflow-hidden cursor-pointer hover:shadow-xl transition-shadow duration-300"
                         onclick="openLightbox({{ json_encode($fotosFase) }}, {{ $loop->index }})">
                        <div class="aspect-square overflow-hidden">
                            <img src="{{ $foto->url_comprimida }}" 
                                 alt="{{ $foto->titulo ?? 'Foto' }}" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        @if($foto->es_principal)
                            <div class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 p-1 rounded-full">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end">
                            <div class="p-3 w-full">
                                <p class="text-white text-sm font-medium truncate">{{ $foto->titulo ?? 'Sin título' }}</p>
                                <p class="text-gray-300 text-xs truncate">{{ $foto->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="text-center py-16">
            <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No hay fotos</h3>
            <p class="mt-2 text-gray-500">Este trabajo aún no tiene fotos en la galería.</p>
            <a href="{{ route('trabajos.fotos.create', $trabajo) }}" class="mt-6 inline-block px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                Subir fotos
            </a>
        </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
let currentImages = [];
let currentIndex = 0;

function openLightbox(images, index) {
    currentImages = images;
    currentIndex = index;
    
    document.getElementById('lightbox-img').src = currentImages[currentIndex].url_original;
    document.getElementById('lightbox-title').textContent = currentImages[currentIndex].titulo || 'Sin título';
    document.getElementById('lightbox-desc').textContent = currentImages[currentIndex].descripcion || '';
    
    document.getElementById('lightbox').classList.remove('hidden');
    document.getElementById('lightbox').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    document.getElementById('lightbox').classList.add('hidden');
    document.getElementById('lightbox').classList.remove('flex');
    document.body.style.overflow = '';
}

function prevImage() {
    currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
    updateLightbox();
}

function nextImage() {
    currentIndex = (currentIndex + 1) % currentImages.length;
    updateLightbox();
}

function updateLightbox() {
    document.getElementById('lightbox-img').src = currentImages[currentIndex].url_original;
    document.getElementById('lightbox-title').textContent = currentImages[currentIndex].titulo || 'Sin título';
    document.getElementById('lightbox-desc').textContent = currentImages[currentIndex].descripcion || '';
}

document.addEventListener('keydown', function(e) {
    if (document.getElementById('lightbox').classList.contains('flex')) {
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') prevImage();
        if (e.key === 'ArrowRight') nextImage();
    }
});

document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) closeLightbox();
});
</script>
@endpush
