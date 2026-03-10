<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Cliente;
use App\Models\FotoTrabajo;
use App\Models\Trabajo;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Tests para upload de archivos de fotos de trabajos
 */
final class UploadFotoTrabajoTest extends TestCase
{
    use RefreshDatabase;

    private Usuario $usuario;
    private Cliente $cliente;
    private Trabajo $trabajo;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear usuario de prueba
        $this->usuario = Usuario::create([
            'nombre' => 'Usuario Test',
            'email' => 'test@tapiceria.com',
            'password' => bcrypt('password123'),
            'rol' => 'admin',
        ]);

        // Crear cliente de prueba
        $this->cliente = Cliente::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'juan@example.com',
            'telefono' => '999888777',
            'direccion' => 'Calle Test 123',
            'activo' => true,
        ]);

        // Crear trabajo de prueba
        $this->trabajo = Trabajo::create([
            'cliente_id' => $this->cliente->id,
            'tipo_trabajo' => 'Tapizado de Sofá',
            'descripcion' => 'Sofá 3 cuerpos',
            'estado' => 'en_proceso',
            'precio_estimado' => 450.00,
            'anticipo' => 100.00,
            'creado_por' => $this->usuario->id,
        ]);

        // Fake el almacenamiento para las pruebas
        Storage::fake('photos');
    }

    /**
     * Test: Puede subir una foto desde archivo
     */
    public function test_puede_subir_foto_desde_archivo(): void
    {
        // Crear archivo de prueba (imagen JPEG)
        $file = UploadedFile::fake()->image('foto-prueba.jpg', 800, 600);

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'foto' => $file,
            'descripcion' => 'Foto de recepción desde archivo',
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos/upload', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Foto subida exitosamente desde archivo',
            ]);

        // Verificar que el archivo se guardó
        Storage::disk('photos')->assertExists('fotos/' . $file->hashName());

        // Verificar en BD
        $this->assertDatabaseHas('fotos_trabajo', [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
        ]);
    }

    /**
     * Test: Puede subir foto de proceso desde archivo
     */
    public function test_puede_subir_foto_proceso_desde_archivo(): void
    {
        $file = UploadedFile::fake()->image('proceso.png', 1024, 768);

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_PROCESO,
            'foto' => $file,
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos/upload', $payload);

        $response->assertStatus(201);
        Storage::disk('photos')->assertExists('fotos/' . $file->hashName());
    }

    /**
     * Test: Puede subir foto final desde archivo
     */
    public function test_puede_subir_foto_final_desde_archivo(): void
    {
        $file = UploadedFile::fake()->image('final.webp', 1200, 900);

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_FINAL,
            'foto' => $file,
            'descripcion' => 'Trabajo terminado!',
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos/upload', $payload);

        $response->assertStatus(201);
    }

    /**
     * Test: No puede subir archivo que no sea imagen
     */
    public function test_no_puede_subir_archivo_que_no_sea_imagen(): void
    {
        $file = UploadedFile::fake()->create('documento.pdf', 1024);

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'foto' => $file,
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos/upload', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('foto');
    }

    /**
     * Test: No puede subir imagen muy grande (más de 5MB)
     */
    public function test_no_puede_subir_imagen_muy_grande(): void
    {
        // Crear archivo fake de 6MB (6144 KB)
        $file = UploadedFile::fake()->image('grande.jpg', 4000, 3000)->size(6144);

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'foto' => $file,
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos/upload', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('foto');
    }

    /**
     * Test: No puede subir imagen con dimensiones inválidas
     */
    public function test_no_puede_subir_imagen_muy_pequena(): void
    {
        // Imagen de 50x50 (mínimo es 100x100)
        $file = UploadedFile::fake()->image('pequena.jpg', 50, 50);

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'foto' => $file,
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos/upload', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('foto');
    }

    /**
     * Test: No puede subir imagen con dimensiones muy grandes
     */
    public function test_no_puede_subir_imagen_demasiado_grande(): void
    {
        // Imagen de 5000x5000 (máximo es 4096x4096)
        $file = UploadedFile::fake()->image('gigante.jpg', 5000, 5000);

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'foto' => $file,
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos/upload', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('foto');
    }

    /**
     * Test: Puede subir múltiples fotos de una vez
     */
    public function test_puede_subir_multiples_fotos_de_una_vez(): void
    {
        $file1 = UploadedFile::fake()->image('foto1.jpg', 800, 600);
        $file2 = UploadedFile::fake()->image('foto2.jpg', 800, 600);
        $file3 = UploadedFile::fake()->image('foto3.jpg', 800, 600);

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_PROCESO,
            'fotos' => [$file1, $file2, $file3],
            'descripcion' => 'Progreso del trabajo',
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->post('/api/fotos/upload-multiple', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonPath('data.fotos_subidas', function ($fotos) {
                return count($fotos) === 3;
            });

        // Verificar que se guardaron las 3 fotos
        $this->assertDatabaseCount('fotos_trabajo', 3);
    }

    /**
     * Test: Upload múltiple con algunos archivos inválidos
     */
    public function test_upload_multiple_con_algunos_archivos_invalidos(): void
    {
        $file1 = UploadedFile::fake()->image('valida1.jpg', 800, 600);
        $file2 = UploadedFile::fake()->create('invalido.pdf', 1024); // No es imagen
        $file3 = UploadedFile::fake()->image('valida2.jpg', 800, 600);

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_PROCESO,
            'fotos' => [$file1, $file2, $file3],
        ];

        // Nota: La validación debería fallar antes de procesar
        $response = $this->actingAs($this->usuario, 'sanctum')
            ->post('/api/fotos/upload-multiple', $payload);

        // Debería fallar la validación porque hay un PDF
        $response->assertStatus(422);
    }

    /**
     * Test: Upload múltiple sin archivos
     */
    public function test_upload_multiple_sin_archivos(): void
    {
        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'fotos' => [],
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->post('/api/fotos/upload-multiple', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('fotos');
    }

    /**
     * Test: La foto guardada tiene formato base64 válido
     */
    public function test_foto_guardada_tiene_formato_base64_valido(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'foto' => $file,
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos/upload', $payload);

        $responseData = $response->json();
        $fotoBase64 = $responseData['data']['foto_base64'];

        // Verificar formato base64
        $this->assertMatchesRegularExpression(
            '/^data:image\/(jpeg|jpg|png|webp);base64,[a-zA-Z0-9\/+=]+$/',
            $fotoBase64
        );
    }

    /**
     * Test: La foto se guarda con la descripción correcta
     */
    public function test_foto_se_guarda_con_descripcion_correcta(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);
        $descripcion = 'Descripción de prueba para la foto';

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'foto' => $file,
            'descripcion' => $descripcion,
        ];

        $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos/upload', $payload);

        $this->assertDatabaseHas('fotos_trabajo', [
            'trabajo_id' => $this->trabajo->id,
            'descripcion' => $descripcion,
        ]);
    }

    /**
     * Test: Formatos de archivo soportados (JPEG, PNG, WEBP)
     */
    public function test_formatos_soportados(): void
    {
        $formatos = [
            ['nombre' => 'test.jpg', 'tipo' => 'jpeg'],
            ['nombre' => 'test.png', 'tipo' => 'png'],
            ['nombre' => 'test.webp', 'tipo' => 'webp'],
        ];

        foreach ($formatos as $formato) {
            $file = UploadedFile::fake()->image($formato['nombre'], 800, 600);

            $payload = [
                'trabajo_id' => $this->trabajo->id,
                'tipo' => FotoTrabajo::TIPO_RECEPCION,
                'foto' => $file,
            ];

            $response = $this->actingAs($this->usuario, 'sanctum')
                ->postJson('/api/fotos/upload', $payload);

            $response->assertStatus(201);

            // Limpiar para la siguiente iteración
            Storage::disk('photos')->deleteDirectory('fotos');
        }
    }
}
