<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Entrega;
use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('username', $validated['username'])->first();

        if (!$usuario) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        // Verificar password (soporta texto plano y hash)
        $passwordValid = Hash::check($validated['password'], $usuario->password) 
            || $usuario->password === $validated['password'];

        if (!$passwordValid) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales inválidas'
            ], 401);
        }

        if (!$usuario->activo) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario inactivo'
            ], 403);
        }

        // Actualizar último acceso
        $usuario->update(['ultimo_acceso' => now()]);

        // Crear token
        $token = $usuario->createToken('auth-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login exitoso',
            'data' => [
                'usuario' => $usuario,
                'token' => $token,
            ]
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada exitosamente'
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }

    public function index(): JsonResponse
    {
        $usuarios = Usuario::where('activo', true)
            ->orderBy('nombre', 'asc')
            ->get(['id', 'username', 'nombre', 'email', 'telefono', 'rol', 'ultimo_acceso']);

        return response()->json([
            'success' => true,
            'data' => $usuarios
        ]);
    }
}
