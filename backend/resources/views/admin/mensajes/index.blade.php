@extends('layouts.admin')
@section('title', 'Mensajes / Inbox')
@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Mensajes</h1>
        <p class="mt-0.5 text-sm text-gray-500">Bandeja de entrada del formulario de contacto</p>
    </div>
    @php $noLeidos = $mensajes->where('leido', false)->count() @endphp
    @if($noLeidos > 0)
    <span class="inline-flex items-center gap-1.5 bg-red-100 text-red-700 text-sm font-medium px-3 py-1.5 rounded-full">
        {{ $noLeidos }} sin leer
    </span>
    @endif
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-left">
            <tr>
                <th class="px-5 py-3 font-medium">Remitente</th>
                <th class="px-5 py-3 font-medium">Tipo</th>
                <th class="px-5 py-3 font-medium">Mensaje</th>
                <th class="px-5 py-3 font-medium">Inmueble</th>
                <th class="px-5 py-3 font-medium">Fecha</th>
                <th class="px-5 py-3 font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($mensajes as $mensaje)
            <tr class="hover:bg-gray-50 transition-colors {{ ! $mensaje->leido ? 'bg-blue-50/40' : '' }}">
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-900 {{ ! $mensaje->leido ? 'font-semibold' : '' }}">
                        {{ $mensaje->nombre }}
                        @if(! $mensaje->leido)
                            <span class="ml-1 h-2 w-2 inline-block rounded-full bg-blue-500"></span>
                        @endif
                    </p>
                    <a href="mailto:{{ $mensaje->email }}" class="text-xs text-blue-600 hover:underline">{{ $mensaje->email }}</a>
                    @if($mensaje->telefono)
                    <p class="text-xs text-gray-400">{{ $mensaje->telefono }}</p>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium capitalize
                        {{ $mensaje->tipo === 'venta' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $mensaje->tipo === 'venta' ? 'Compra' : 'Consulta' }}
                    </span>
                </td>
                <td class="px-5 py-3 max-w-xs">
                    <p class="text-gray-700 line-clamp-2 text-xs leading-relaxed">{{ $mensaje->mensaje }}</p>
                </td>
                <td class="px-5 py-3 text-gray-500 text-xs">
                    {{ $mensaje->inmueble?->titulo ?? '—' }}
                </td>
                <td class="px-5 py-3 text-gray-500 text-xs whitespace-nowrap">
                    {{ $mensaje->created_at->format('d/m/Y') }}<br>
                    <span class="text-gray-400">{{ $mensaje->created_at->format('H:i') }}</span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-2">
                        <form method="POST" action="{{ route('admin.mensajes.leer', $mensaje) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="text-xs px-2 py-1 rounded-md border transition-colors
                                       {{ $mensaje->leido
                                           ? 'border-gray-300 text-gray-500 hover:bg-gray-50'
                                           : 'border-blue-300 bg-blue-50 text-blue-700 hover:bg-blue-100' }}"
                                title="{{ $mensaje->leido ? 'Marcar como no leído' : 'Marcar como leído' }}">
                                {{ $mensaje->leido ? '↩ No leído' : '✓ Leído' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.mensajes.reenviar', $mensaje) }}">
                            @csrf
                            <button type="submit"
                                    title="Reenviar correos al admin y al cliente"
                                    class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-800 font-medium transition-colors">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Reenviar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.mensajes.destroy', $mensaje) }}"
                              onsubmit="return confirm('¿Eliminar este mensaje de {{ addslashes($mensaje->nombre) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-medium transition-colors">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-16 text-center">
                    <div class="text-gray-300 text-4xl mb-3">✉</div>
                    <p class="text-gray-400 text-sm">La bandeja de entrada está vacía.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($mensajes->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $mensajes->links() }}</div>
    @endif
</div>

@endsection
