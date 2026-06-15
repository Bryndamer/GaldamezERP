<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Galdámez ERP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-gray-100" x-data="{ sidebarOpen: false }">

<div class="flex h-full">

    {{-- ══════════ SIDEBAR ══════════ --}}
    <aside class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col bg-gray-900 text-white shadow-xl
                  transform transition-transform duration-200 ease-in-out
                  lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

        {{-- Brand --}}
        <div class="flex items-center gap-2 px-6 py-5 border-b border-gray-700/60">
            <div class="h-8 w-8 rounded-lg bg-blue-600 flex items-center justify-center flex-shrink-0">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold leading-tight">Galdámez ERP</p>
                <p class="text-xs text-gray-400 leading-tight">Panel Administrativo</p>
            </div>
        </div>

        {{-- Navegación --}}
        <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-0.5">

            @php
                $navLink = function(string $label, string $icon, string $routeName, string $matchPattern) {
                    $active = request()->routeIs($matchPattern);
                    $base   = 'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-colors';
                    $style  = $active
                        ? "$base bg-blue-600 text-white"
                        : "$base text-gray-300 hover:bg-gray-800 hover:text-white";
                    $url    = route($routeName);
                    return "<a href=\"$url\" class=\"$style\">$icon $label</a>";
                };

                $icons = [
                    'dashboard' => '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
                    'inmuebles' => '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><path stroke-linecap="round" stroke-linejoin="round" d="M9 22V12h6v10"/></svg>',
                    'categorias'=> '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>',
                    'users'     => '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a4 4 0 00-5-5M9 20H4v-2a4 4 0 015-5m0 0a4 4 0 110-8 4 4 0 010 8zm8-4a4 4 0 11-8 0"/></svg>',
                    'mensajes'   => '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                    'plantillas' => '<svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>',
                ];
            @endphp

            @if(Auth::user()->isAdmin())
                {!! $navLink('Dashboard',   $icons['dashboard'],  'admin.dashboard',        'admin.dashboard') !!}
            @endif

            {!! $navLink('Inmuebles', $icons['inmuebles'], 'admin.inmuebles.index', 'admin.inmuebles.*') !!}

            @if(Auth::user()->isAdmin())
                <div class="pt-3 pb-1">
                    <p class="px-3 text-[10px] font-semibold uppercase tracking-widest text-gray-500">Configuración</p>
                </div>
                {!! $navLink('Categorías',  $icons['categorias'], 'admin.categorias.index', 'admin.categorias.*') !!}
                {!! $navLink('Usuarios',    $icons['users'],      'admin.users.index',      'admin.users.*') !!}

                @php $unread = \App\Models\Mensaje::where('leido', false)->count(); @endphp
                <a href="{{ route('admin.mensajes.index') }}"
                   class="flex items-center justify-between rounded-lg px-3 py-2.5 text-sm font-medium transition-colors
                          {{ request()->routeIs('admin.mensajes.*') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <span class="flex items-center gap-3">{!! $icons['mensajes'] !!} Mensajes</span>
                    @if($unread > 0)
                        <span class="rounded-full bg-red-500 px-2 py-0.5 text-[10px] font-bold text-white">{{ $unread }}</span>
                    @endif
                </a>

                {!! $navLink('Plantillas de Correo', $icons['plantillas'], 'admin.plantillas.index', 'admin.plantillas.*') !!}
            @endif

        </nav>

        {{-- Usuario + Logout --}}
        <div class="border-t border-gray-700/60 px-4 py-4">
            <div class="flex items-center gap-3 mb-3">
                <div class="h-8 w-8 rounded-full bg-gray-700 flex items-center justify-center flex-shrink-0 text-xs font-bold text-white">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 capitalize">{{ Auth::user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex w-full items-center gap-2 rounded-lg px-3 py-1.5 text-sm text-gray-400 hover:bg-gray-800 hover:text-red-400 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Overlay móvil --}}
    <div class="fixed inset-0 z-40 bg-black/50 lg:hidden"
         x-show="sidebarOpen"
         x-transition:enter="transition-opacity duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         style="display:none"></div>

    {{-- ══════════ CONTENIDO PRINCIPAL ══════════ --}}
    <div class="flex flex-1 flex-col lg:pl-64 min-h-screen">

        {{-- Topbar móvil --}}
        <div class="flex items-center gap-4 bg-white border-b border-gray-200 px-4 py-3 lg:hidden shadow-sm">
            <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="font-semibold text-gray-800 text-sm">Galdámez ERP</span>
        </div>

        <main class="flex-1 px-6 py-8 max-w-7xl mx-auto w-full">

            {{-- Alertas de sesión globales --}}
            @if(session('success'))
                <div class="mb-5 flex items-center gap-3 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                    <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-5 flex items-center gap-3 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                    <svg class="h-4 w-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')

        </main>

        <footer class="border-t border-gray-200 bg-white px-6 py-3 text-xs text-center text-gray-400">
            developed by
            <a href="https://portafolio-layout.vercel.app/Portafolioindex.html"
               target="_blank"
               rel="noopener noreferrer"
               class="text-blue-500 hover:text-blue-700 transition-colors">Danilo Rauda</a>
            &amp; Galdámez S.A. de C.V. | powered by WebExperience &copy; 2026 todos los derechos reservados.
        </footer>
    </div>

</div>
</body>
</html>
