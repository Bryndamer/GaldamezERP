@extends('layouts.admin')

@section('title', 'Inmuebles')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Inmuebles</h1>
        <p class="mt-1 text-sm text-gray-500">
            {{ Auth::user()->isAdmin() ? 'Todos los inmuebles del sistema' : 'Mis inmuebles' }}
        </p>
    </div>
    <a href="{{ route('admin.inmuebles.create') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        + Nuevo inmueble
    </a>
</div>

{{-- Alertas de sesión --}}
@if (session('success'))
    <div class="mb-5 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

{{-- Filtro por estado --}}
<form method="GET" action="{{ route('admin.inmuebles.index') }}" class="mb-5 flex items-center gap-3">
    <label class="text-sm font-medium text-gray-600">Filtrar por estado:</label>
    <select name="estado" onchange="this.form.submit()"
        class="rounded-lg border border-gray-300 bg-white px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        <option value="">Todos</option>
        <option value="disponible" {{ request('estado') === 'disponible' ? 'selected' : '' }}>Disponible</option>
        <option value="vendido"    {{ request('estado') === 'vendido'    ? 'selected' : '' }}>Vendido</option>
        <option value="reservado"  {{ request('estado') === 'reservado'  ? 'selected' : '' }}>Reservado</option>
    </select>
    @if(request('estado'))
        <a href="{{ route('admin.inmuebles.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Limpiar</a>
    @endif
</form>

{{-- Tabla --}}
<div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-left">
            <tr>
                <th class="px-4 py-3 font-medium">Título</th>
                <th class="px-4 py-3 font-medium">Tipo</th>
                <th class="px-4 py-3 font-medium">Precio</th>
                <th class="px-4 py-3 font-medium">Estado</th>
                @if(Auth::user()->isAdmin())
                <th class="px-4 py-3 font-medium">Agente</th>
                @endif
                <th class="px-4 py-3 font-medium">Fotos</th>
                <th class="px-4 py-3 font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($inmuebles as $inmueble)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 font-medium text-gray-900">
                    {{ $inmueble->titulo }}
                    <p class="text-xs text-gray-400 font-normal">{{ $inmueble->category->name ?? '—' }}</p>
                </td>
                <td class="px-4 py-3 capitalize text-gray-600">{{ $inmueble->tipo }}</td>
                <td class="px-4 py-3 text-gray-800">${{ number_format($inmueble->precio, 2) }}</td>
                <td class="px-4 py-3">
                    @php
                        $badge = match($inmueble->estado) {
                            'disponible' => 'bg-green-100 text-green-700',
                            'reservado'  => 'bg-yellow-100 text-yellow-700',
                            'vendido'    => 'bg-red-100 text-red-700',
                        };
                    @endphp
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }} capitalize">
                        {{ $inmueble->estado }}
                    </span>
                </td>
                @if(Auth::user()->isAdmin())
                <td class="px-4 py-3 text-gray-600">{{ $inmueble->agente->name ?? '—' }}</td>
                @endif
                <td class="px-4 py-3 text-gray-500">{{ count($inmueble->fotos ?? []) }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.inmuebles.edit', $inmueble) }}"
                           class="text-blue-600 hover:text-blue-800 font-medium transition-colors">Editar</a>

                        <form method="POST" action="{{ route('admin.inmuebles.destroy', $inmueble) }}"
                              onsubmit="return confirm('¿Eliminar este inmueble? Esta acción también borrará sus fotos.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium transition-colors">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ Auth::user()->isAdmin() ? 7 : 6 }}" class="px-4 py-12 text-center text-gray-400 text-sm">
                    No hay inmuebles registrados.
                    <a href="{{ route('admin.inmuebles.create') }}" class="text-blue-600 hover:underline ml-1">Crear el primero</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($inmuebles->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $inmuebles->links() }}
    </div>
    @endif
</div>

@endsection
