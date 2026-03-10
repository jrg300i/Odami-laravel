<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\Cliente;
use App\Models\FotoTrabajo;
use App\Models\Trabajo;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Tests para el endpoint de fotos de trabajos
 */
final class FotoTrabajoTest extends TestCase
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
    }

    /**
     * Test: Puede obtener fotos de un trabajo
     */
    public function test_puede_obtener_fotos_de_trabajo(): void
    {
        // Crear fotos de prueba
        FotoTrabajo::create([
            'trabajo_id' => $this->trabajo->id,
            'foto_url' => 'fotos/test1.jpg',
            'foto_base64' => 'data:image/jpeg;base64,test123',
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'subido_por' => $this->usuario->id,
        ]);

        FotoTrabajo::create([
            'trabajo_id' => $this->trabajo->id,
            'foto_url' => 'fotos/test2.jpg',
            'foto_base64' => 'data:image/jpeg;base64,test456',
            'tipo' => FotoTrabajo::TIPO_PROCESO,
            'subido_por' => $this->usuario->id,
        ]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson("/api/trabajos/{$this->trabajo->id}/fotos");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'trabajo' => [
                        'id' => $this->trabajo->id,
                        'tipo_trabajo' => $this->trabajo->tipo_trabajo,
                    ],
                    'conteo' => [
                        'recepcion' => 1,
                        'proceso' => 1,
                        'final' => 0,
                        'total' => 2,
                    ],
                ],
            ]);
    }

    /**
     * Test: Puede subir una foto de recepción
     */
    public function test_puede_subir_foto_recepcion(): void
    {
        $fotoBase64 = 'data:image/jpeg;base64,' . base64_encode('imagen_test_data');

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'foto_base64' => $fotoBase64,
            'descripcion' => 'Foto de recepción del sofá',
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Foto subida exitosamente',
            ]);

        $this->assertDatabaseHas('fotos_trabajo', [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
        ]);
    }

    /**
     * Test: Puede subir una foto de proceso
     */
    public function test_puede_subir_foto_proceso(): void
    {
        $fotoBase64 = 'data:image/png;base64,' . base64_encode('imagen_proceso_data');

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_PROCESO,
            'foto_base64' => $fotoBase64,
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('fotos_trabajo', [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_PROCESO,
        ]);
    }

    /**
     * Test: Puede subir una foto final
     */
    public function test_puede_subir_foto_final(): void
    {
        $fotoBase64 = 'data:image/webp;base64,' . base64_encode('imagen_final_data');

        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_FINAL,
            'foto_base64' => $fotoBase64,
            'descripcion' => 'Trabajo terminado',
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('fotos_trabajo', [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_FINAL,
        ]);
    }

    /**
     * Test: No puede subir foto con tipo inválido
     */
    public function test_no_puede_subir_foto_con_tipo_invalido(): void
    {
        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => 'invalido',
            'foto_base64' => 'data:image/jpeg;base64,test',
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('tipo');
    }

    /**
     * Test: No puede subir foto sin trabajo_id
     */
    public function test_no_puede_subir_foto_sin_trabajo(): void
    {
        $payload = [
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'foto_base64' => 'data:image/jpeg;base64,test',
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('trabajo_id');
    }

    /**
     * Test: No puede subir foto con base64 inválido
     */
    public function test_no_puede_subir_foto_con_base64_invalido(): void
    {
        $payload = [
            'trabajo_id' => $this->trabajo->id,
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'foto_base64' => 'not-valid-base64',
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('foto_base64');
    }

    /**
     * Test: No puede subir foto con trabajo inexistente
     */
    public function test_no_puede_subir_foto_con_trabajo_inexistente(): void
    {
        $payload = [
            'trabajo_id' => 99999,
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'foto_base64' => 'data:image/jpeg;base64,test',
        ];

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->postJson('/api/fotos', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('trabajo_id');
    }

    /**
     * Test: Puede obtener una foto específica
     */
    public function test_puede_obtener_foto_especifica(): void
    {
        $foto = FotoTrabajo::create([
            'trabajo_id' => $this->trabajo->id,
            'foto_url' => 'fotos/test.jpg',
            'foto_base64' => 'data:image/jpeg;base64,test',
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'subido_por' => $this->usuario->id,
        ]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson("/api/fotos/{$foto->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $foto->id,
                    'tipo' => FotoTrabajo::TIPO_RECEPCION,
                ],
            ]);
    }

    /**
     * Test: Puede eliminar una foto
     */
    public function test_puede_eliminar_foto(): void
    {
        $foto = FotoTrabajo::create([
            'trabajo_id' => $this->trabajo->id,
            'foto_url' => 'fotos/test.jpg',
            'foto_base64' => 'data:image/jpeg;base64,test',
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'subido_por' => $this->usuario->id,
        ]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->deleteJson("/api/fotos/{$foto->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Foto eliminada exitosamente',
            ]);

        $this->assertDatabaseMissing('fotos_trabajo', [
            'id' => $foto->id,
        ]);
    }

    /**
     * Test: El conteo de fotos se incluye en la lista de trabajos
     */
    public function test_conteo_fotos_en_lista_trabajos(): void
    {
        // Crear fotos para el trabajo
        FotoTrabajo::create([
            'trabajo_id' => $this->trabajo->id,
            'foto_url' => 'fotos/test1.jpg',
            'foto_base64' => 'data:image/jpeg;base64,test1',
            'tipo' => FotoTrabajo::TIPO_RECEPCION,
            'subido_por' => $this->usuario->id,
        ]);

        FotoTrabajo::create([
            'trabajo_id' => $this->trabajo->id,
            'foto_url' => 'fotos/test2.jpg',
            'foto_base64' => 'data:image/jpeg;base64,test2',
            'tipo' => FotoTrabajo::TIPO_PROCESO,
            'subido_por' => $this->usuario->id,
        ]);

        FotoTrabajo::create([
            'trabajo_id' => $this->trabajo->id,
            'foto_url' => 'fotos/test3.jpg',
            'foto_base64' => 'data:image/jpeg;base64,test3',
            'tipo' => FotoTrabajo::TIPO_PROCESO,
            'subido_por' => $this->usuario->id,
        ]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson('/api/trabajos');

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $this->trabajo->id,
                'fotos_conteo' => [
                    'recepcion' => 1,
                    'proceso' => 2,
                    'final' => 0,
                    'total' => 3,
                ],
            ]);
    }

    /**
     * Test: El conteo de fotos se incluye en el detalle de trabajo
     */
    public function test_conteo_fotos_en_detalle_trabajo(): void
    {
        FotoTrabajo::create([
            'trabajo_id' => $this->trabajo->id,
            'foto_url' => 'fotos/test.jpg',
            'foto_base64' => 'data:image/jpeg;base64,test',
            'tipo' => FotoTrabajo::TIPO_FINAL,
            'subido_por' => $this->usuario->id,
        ]);

        $response = $this->actingAs($this->usuario, 'sanctum')
            ->getJson("/api/trabajos/{$this->trabajo->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'fotos_conteo' => [
                    'recepcion' => 0,
                    'proceso' => 0,
                    'final' => 1,
                    'total' => 1,
                ],
            ]);
    }

    /**
     * Test: Los tipos válidos son solo recepcion, proceso y final
     */
    public function test_tipos_validos(): void
    {
        $this->assertTrue(FotoTrabajo::esTipoValido('recepcion'));
        $this->assertTrue(FotoTrabajo::esTipoValido('proceso'));
        $this->assertTrue(FotoTrabajo::esTipoValido('final'));
        $this->assertFalse(FotoTrabajo::esTipoValido('otro'));
        $this->assertFalse(FotoTrabajo::esTipoValido(''));
    }

    /**
     * Test: La información de tipos es correcta
     */
    public function test_info_tipos(): void
    {
        $infoRecepcion = FotoTrabajo::getTipoInfo('recepcion');
        $this->assertEquals('📥', $infoRecepcion['icono']);
        $this->assertEquals('Recepción', $infoRecepcion['label']);
        $this->assertEquals('#2196F3', $infoRecepcion['color']);

        $infoProceso = FotoTrabajo::getTipoInfo('proceso');
        $this->assertEquals('🔨', $infoProceso['icono']);
        $this->assertEquals('Proceso', $infoProceso['label']);

        $infoFinal = FotoTrabajo::getTipoInfo('final');
        $this->assertEquals('✨', $infoFinal['icono']);
        $this->assertEquals('Final', $infoFinal['label']);
    }
}
