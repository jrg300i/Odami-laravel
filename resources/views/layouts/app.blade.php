<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Tapicería Odami')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Scripts Head -->
    @stack('head')
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 min-h-screen flex flex-col">
    <!-- Navbar -->
    <header class="bg-amber-800 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <div class="bg-amber-600 p-2 rounded-lg">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h11M9 21V3m0 18l-6-6 6-6"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold">Tapicería Odami</h1>
                            <p class="text-amber-200 text-xs">Excelencia en tapicería</p>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <nav class="hidden md:flex items-center space-x-8">
                    @auth
                        @if(auth()->user()->isAdmin() || auth()->user()->isTapicero())
                            <a href="{{ route('dashboard') }}" class="hover:text-amber-200 transition-colors {{ request()->routeIs('dashboard') ? 'text-amber-200 font-semibold' : '' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('clientes.index') }}" class="hover:text-amber-200 transition-colors {{ request()->routeIs('clientes.*') ? 'text-amber-200 font-semibold' : '' }}">
                                Clientes
                            </a>
                            <a href="{{ route('trabajos.index') }}" class="hover:text-amber-200 transition-colors {{ request()->routeIs('trabajos.*') ? 'text-amber-200 font-semibold' : '' }}">
                                Trabajos
                            </a>
                            <a href="{{ route('facturas.index') }}" class="hover:text-amber-200 transition-colors {{ request()->routeIs('facturas.*') ? 'text-amber-200 font-semibold' : '' }}">
                                Facturas
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- User Menu -->
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-amber-600 flex items-center justify-center">
                                    <span class="font-semibold">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                </div>
                                <span class="hidden md:inline">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Mi Perfil
                                </a>
                                @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Administración
                                </a>
                                @endif
                                <hr class="my-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Cerrar Sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-amber-200 hover:text-white transition-colors">
                            Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Sidebar for authenticated users -->
        @auth
            @if(auth()->user()->isAdmin() || auth()->user()->isTapicero())
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Sidebar -->
                        <aside class="md:w-64 flex-shrink-0">
                            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                                <h2 class="font-semibold text-gray-800 mb-4">Menú Principal</h2>
                                <nav class="space-y-2">
                                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-amber-50 {{ request()->routeIs('dashboard') ? 'bg-amber-50 text-amber-800' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                        <span>Dashboard</span>
                                    </a>
                                    
                                    <a href="{{ route('clientes.index') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-amber-50 {{ request()->routeIs('clientes.*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5 0c-.66 0-1.293-.103-1.887-.297M9 21v-1a6 6 0 00-6-6H3"/>
                                        </svg>
                                        <span>Clientes</span>
                                    </a>
                                    
                                    <a href="{{ route('trabajos.index') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-amber-50 {{ request()->routeIs('trabajos.*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <span>Trabajos</span>
                                    </a>
                                    
                                    <a href="{{ route('materiales.index') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-amber-50 {{ request()->routeIs('materiales.*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <span>Materiales</span>
                                    </a>
                                    
                                    <a href="{{ route('facturas.index') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-amber-50 {{ request()->routeIs('facturas.*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <span>Facturas</span>
                                    </a>
                                    
                                    <a href="{{ route('pagos.index') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-amber-50 {{ request()->routeIs('pagos.*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>Pagos</span>
                                    </a>
                                    
                                    @if(auth()->user()->isAdmin())
                                    <div class="pt-4 border-t">
                                        <h3 class="font-medium text-gray-500 text-xs uppercase mb-2">Administración</h3>
                                        <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-amber-50 {{ request()->routeIs('admin.users.*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <span>Usuarios</span>
                                        </a>
                                        
                                        <a href="{{ route('backups.index') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-amber-50 {{ request()->routeIs('backups.*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                                            </svg>
                                            <span>Backups</span>
                                        </a>
                                        
                                        <a href="{{ route('admin.system.configuracion') }}" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-amber-50 {{ request()->routeIs('admin.system.*') ? 'bg-amber-50 text-amber-800' : 'text-gray-600' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span>Configuración</span>
                                        </a>
                                    </div>
                                    @endif
                                </nav>
                            </div>
                            
                            <!-- Stats Card -->
                            <div class="bg-amber-50 rounded-lg shadow-sm p-4 border border-amber-200">
                                <h3 class="font-semibold text-amber-800 mb-2">Resumen</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Clientes activos:</span>
                                        <span class="font-semibold">0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Trabajos en curso:</span>
                                        <span class="font-semibold">0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-600">Facturas pendientes:</span>
                                        <span class="font-semibold">0</span>
                                    </div>
                                </div>
                            </div>
                        </aside>

                        <!-- Main Content Area -->
                        <div class="flex-grow">
                            <!-- Page Header -->
                            <div class="mb-6">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-900">@yield('page-title')</h1>
                                        @hasSection('page-subtitle')
                                        <p class="text-gray-600 mt-1">@yield('page-subtitle')</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        @yield('page-actions')
                                    </div>
                                </div>
                                @hasSection('breadcrumbs')
                                <nav class="mt-4" aria-label="Breadcrumb">
                                    <ol class="flex items-center space-x-2 text-sm">
                                        @yield('breadcrumbs')
                                    </ol>
                                </nav>
                                @endif
                            </div>

                            <!-- Alerts -->
                            @if(session('success'))
                            <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if(session('error'))
                            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Page Content -->
                            <div class="bg-white rounded-lg shadow-sm p-6">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Content for client users -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    @yield('content')
                </div>
            @endif
        @else
            <!-- Content for guests -->
            @yield('content')
        @endauth
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Tapicería Odami</h3>
                    <p class="text-gray-400">Especialistas en restauración y tapicería de muebles con más de 20 años de experiencia.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contacto</h3>
                    <p class="text-gray-400">Tel: 55 1234 5678</p>
                    <p class="text-gray-400">Email: info@tapiceria-odami.com</p>
                    <p class="text-gray-400">Horario: Lunes a Viernes 9am - 6pm</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Sistema</h3>
                    <p class="text-gray-400">Versión 1.0.0</p>
                    <p class="text-gray-400">&copy; {{ date('Y') }} Tapicería Odami. Todos los derechos reservados.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
     <script>
          import { defineConfig } from 'vite';
        import laravel from 'laravel-vite-plugin';

        export default defineConfig({
            plugins: [
                laravel({
                    input: ['resources/css/app.css', 'resources/js/app.js'],
                    refresh: true,
                }),
            ],
        });
     </script>
    @stack('scripts')
</body>
</html>