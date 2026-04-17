@extends('layouts.app')

@section('page-title', 'Mi Perfil')

@section('content')
<div class="space-y-6">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Información del Perfil</h3>
            <p class="mt-1 text-sm text-gray-600">
                Actualiza tu información personal y preferencias.
            </p>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <!-- Nombre -->
                        <div>
                            <label for="name" class="form-label">
                                Nombre Completo
                            </label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $user->name) }}"
                                   class="form-input @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="form-label">
                                Correo Electrónico
                            </label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', $user->email) }}"
                                   class="form-input @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="phone" class="form-label">
                                Teléfono
                            </label>
                            <input type="text" name="phone" id="phone" 
                                   value="{{ old('phone', $user->phone) }}"
                                   class="form-input @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="address" class="form-label">
                                Dirección
                            </label>
                            <textarea name="address" id="address" rows="3"
                                      class="form-input @error('address') border-red-500 @enderror">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Roles -->
                        <div>
                            <label class="form-label">Roles</label>
                            <div class="mt-2 space-y-2">
                                @foreach($user->roles as $role)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $role->color ? 'text-white' : 'bg-gray-100 text-gray-800' }}"
                                      style="{{ $role->color ? "background-color: {$role->color}" : '' }}">
                                    {{ ucfirst($role->name) }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="btn-primary">
                            Guardar Cambios
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Cambiar Contraseña -->
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Cambiar Contraseña</h3>
            <p class="mt-1 text-sm text-gray-600">
                Actualiza tu contraseña para mantener segura tu cuenta.
            </p>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <form action="{{ route('profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                        <!-- Contraseña Actual -->
                        <div>
                            <label for="current_password" class="form-label">
                                Contraseña Actual
                            </label>
                            <input type="password" name="current_password" id="current_password"
                                   class="form-input @error('current_password') border-red-500 @enderror">
                            @error('current_password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nueva Contraseña -->
                        <div>
                            <label for="password" class="form-label">
                                Nueva Contraseña
                            </label>
                            <input type="password" name="password" id="password"
                                   class="form-input @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div>
                            <label for="password_confirmation" class="form-label">
                                Confirmar Nueva Contraseña
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-input">
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <button type="submit" class="btn-primary">
                            Cambiar Contraseña
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Información de Sesión -->
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Información de Sesión</h3>
            <p class="mt-1 text-sm text-gray-600">
                Detalles de tu última sesión.
            </p>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white space-y-4 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">
                                Último Acceso
                            </label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">
                                IP del Último Acceso
                            </label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $user->last_login_ip ?? 'No disponible' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">
                                Fecha de Registro
                            </label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $user->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">
                                Estado de la Cuenta
                            </label>
                            <p class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Activa' : 'Inactiva' }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection