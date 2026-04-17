<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FotosTrabajosSeeder extends Seeder
{
    // URLs de imágenes de ejemplo (de picsum.photos)
    private $urlsAntes = [
        'https://images.unsplash.com/photo-1556228453-efd6c1ff04f6?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1560184897-67f4a3f9a7fa?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1584622650111-993a426fbf0a?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    ];
    
    private $urlsDurante = [
        'https://images.unsplash.com/photo-1580480055273-228ff5388ef8?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1595428774223-ef52624120d2?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1556228720-195a672e8a03?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    ];
    
    private $urlsDespues = [
        'https://images.unsplash.com/photo-1517705008128-361805f42e86?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1540574163026-643ea20ade25?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
        'https://images.unsplash.com/photo-1560185007-5f0bb1866cab?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80',
    ];
    
    private $titulosAntes = [
        'Estado inicial',
        'Daños visibles',
        'Vista frontal antes',
        'Vista lateral antes',
        'Detalle daños',
        'Condición general',
        'Partes desgastadas',
        'Estructura inicial',
        'Color original',
        'Materiales viejos',
    ];
    
    private $titulosDurante = [
        'Proceso de desmontaje',
        'Limpieza profunda',
        'Reparación estructura',
        'Corte de tela',
        'Colocación espuma',
        'Costura manual',
        'Tapizado en proceso',
        'Ajuste medidas',
        'Detalle costura',
        'Montaje parcial',
    ];
    
    private $titulosDespues = [
        'Resultado final',
        'Vista frontal terminada',
        'Vista lateral terminada',
        'Detalle acabados',
        'Comparación antes/después',
        'En entorno del cliente',
        'Vista completa',
        'Detalle costuras',
        'Acabado profesional',
        'Cliente satisfecho',
    ];
    
    private $descripciones = [
        'Fotografía documental del estado inicial',
        'Se observan los daños y desgastes',
        'Imagen de referencia para el trabajo',
        'Fotografía durante el proceso de restauración',
        'Detalle del trabajo artesanal',
        'Resultado del proceso de tapizado',
        'Comparativa visual del avance',
        'Documentación para el portafolio',
        'Imagen para seguimiento del proyecto',
        'Fotografía de calidad para catálogo',
    ];

    public function run(): void
    {
        // Verificar que existen trabajos
        $trabajos = DB::table('trabajos')->pluck('id')->toArray();
        
        if (empty($trabajos)) {
            $this->command->error('Primero ejecuta el seeder de trabajos!');
            return;
        }

        $fotos = [];
        $ordenContador = 0;

        foreach ($trabajos as $trabajoId) {
            // Para cada trabajo, crear 2-4 fotos de cada fase
            $fases = ['antes', 'durante', 'despues'];
            
            foreach ($fases as $fase) {
                $numFotos = rand(2, 4);
                
                for ($i = 0; $i < $numFotos; $i++) {
                    $esPrincipal = ($i === 0 && $fase === 'despues') ? true : false;
                    
                    // Seleccionar URLs según fase
                    if ($fase === 'antes') {
                        $url = $this->urlsAntes[array_rand($this->urlsAntes)];
                        $titulo = $this->titulosAntes[array_rand($this->titulosAntes)];
                    } elseif ($fase === 'durante') {
                        $url = $this->urlsDurante[array_rand($this->urlsDurante)];
                        $titulo = $this->titulosDurante[array_rand($this->titulosDurante)];
                    } else {
                        $url = $this->urlsDespues[array_rand($this->urlsDespues)];
                        $titulo = $this->titulosDespues[array_rand($this->titulosDespues)];
                    }
                    
                    // Generar rutas simuladas
                    $uuid = Str::uuid();
                    $rutaOriginal = "trabajos/{$trabajoId}/original/{$uuid}.jpg";
                    $rutaMiniatura = "trabajos/{$trabajoId}/thumbnail/{$uuid}_thumb.jpg";
                    $rutaComprimida = "trabajos/{$trabajoId}/compressed/{$uuid}_compressed.jpg";
                    
                    // Tamaños simulados
                    $tamanioOriginal = rand(1500, 5000); // 1.5MB - 5MB
                    $tamanioComprimido = rand(200, 800); // 200KB - 800KB
                    
                    // Metadata simulada
                    $metadata = [
                        'camara' => 'Canon EOS 5D Mark IV',
                        'objetivo' => '24-70mm f/2.8',
                        'apertura' => 'f/' . rand(28, 56) / 10,
                        'velocidad' => '1/' . rand(60, 200),
                        'iso' => rand(100, 800),
                        'resolucion' => '6000x4000',
                        'fecha_toma' => now()->subDays(rand(1, 30))->format('Y-m-d H:i:s'),
                        'autor' => 'Fotógrafo del taller',
                    ];
                    
                    $fotos[] = [
                        'trabajo_id' => $trabajoId,
                        'titulo' => $titulo,
                        'descripcion' => $this->descripciones[array_rand($this->descripciones)],
                        'ruta_original' => $rutaOriginal,
                        'ruta_miniatura' => $rutaMiniatura,
                        'ruta_comprimida' => $rutaComprimida,
                        'fase' => $fase,
                        'es_principal' => $esPrincipal,
                        'orden' => $ordenContador++,
                        'tamanio_original' => $tamanioOriginal / 1000, // Convertir a KB
                        'tamanio_comprimido' => $tamanioComprimido / 1000,
                        'metadata' => json_encode($metadata),
                        'created_at' => now()->subDays(rand(1, 90)),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insertar en lotes para mejor performance
        $chunks = array_chunk($fotos, 50);
        foreach ($chunks as $chunk) {
            DB::table('fotos_trabajos')->insert($chunk);
        }

        $this->command->info('Seeder de fotos_trabajos ejecutado exitosamente!');
        $this->command->info('Total de fotos insertadas: ' . count($fotos));
        
        // Estadísticas por fase
        $this->command->info("\nResumen por fase:");
        $fasesCount = [
            'antes' => 0,
            'durante' => 0,
            'despues' => 0,
        ];
        
        foreach ($fotos as $foto) {
            $fasesCount[$foto['fase']]++;
        }
        
        foreach ($fasesCount as $fase => $cantidad) {
            $this->command->info("- $fase: $cantidad fotos");
        }
        
        // Fotos principales
        $principales = array_filter($fotos, function($foto) {
            return $foto['es_principal'];
        });
        
        $this->command->info("Fotos principales: " . count($principales));
        
        // Tamaño total
        $tamanioTotal = array_sum(array_column($fotos, 'tamanio_original'));
        $this->command->info("Tamaño total: " . number_format($tamanioTotal / 1024, 2) . " MB");
    }
}