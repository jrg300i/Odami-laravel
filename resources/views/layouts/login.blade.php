@extends('layouts.auth')

@section('title', 'Iniciar Sesión')

@section('content')
<form class="space-y-6" action="{{ route('login') }}" method="POST">
    @csrf
    
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700">
            Correo electrónico
        </label>
        <div class="mt-1">
            <input id="email" name="email" type="email" autocomplete="email" required
                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm"
                   value="{{ old('email') }}">
        </div>
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">
            Contraseña
        </label>
        <div class="mt-1">
            <input id="password" name="password" type="password" autocomplete="current-password" required
                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-sm">
        </div>
    </div>

    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <input id="remember_me" name="remember" type="checkbox"
                   class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
            <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                Recordarme
            </label>
        </div>

        @if (Route::has('password.request'))
        <div class="text-sm">
            <a href="{{ route('password.request') }}" class="font-medium text-amber-600 hover:text-amber-500">
                ¿Olvidaste tu contraseña?
            </a>
        </div>
        @endif
    </div>

    <div>
        <button type="submit"
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
            Iniciar Sesión
        </button>
    </div>
</form>

<div class="mt-6">
    <div class="relative">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">
                ¿No tienes una cuenta?
            </span>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('register') }}"
           class="w-full flex justify-center py-2 px-4 border border-amber-600 rounded-md shadow-sm text-sm font-medium text-amber-600 bg-white hover:bg-amber-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
            Registrarse
        </a>
    </div>
</div>
@endsection

@section('auth-footer')
<p class="text-sm text-gray-600">
    &copy; {{ date('Y') }} Tapicería Odami. Todos los derechos reservados.
</p>
@endsection