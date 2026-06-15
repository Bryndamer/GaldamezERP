@extends('layouts.admin')
@section('title', 'Plantillas de Correo')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Plantillas de Correo</h1>
        <p class="mt-0.5 text-sm text-gray-500">Personaliza los textos de los correos automáticos del sistema</p>
    </div>
</div>

<div class="grid gap-5 sm:grid-cols-2">
    @foreach($plantillas as $plantilla)
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-shadow">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-gray-900 text-base">{{ $plantilla->nombre }}</h2>
                    <span class="inline-block mt-1 text-xs font-mono bg-gray-100 text-gray-500 px-2 py-0.5 rounded">
                        {{ $plantilla->identificador }}
                    </span>
                </div>
                <a href="{{ route('admin.plantillas.edit', $plantilla) }}"
                   class="flex-shrink-0 inline-flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-3.5 py-1.5 rounded-lg transition-colors">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Editar
                </a>
            </div>
        </div>
        <div class="px-6 py-5 space-y-3 text-sm">
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Asunto</span>
                <p class="mt-1 text-gray-700">{{ $plantilla->asunto }}</p>
            </div>
            @if($plantilla->saludo)
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Saludo</span>
                <p class="mt-1 text-gray-700">{{ $plantilla->saludo }}</p>
            </div>
            @endif
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Cuerpo principal</span>
                <p class="mt-1 text-gray-600 line-clamp-2">{{ $plantilla->cuerpo_principal }}</p>
            </div>
            <div>
                <span class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Firma</span>
                <p class="mt-1 text-gray-700">{{ $plantilla->firma }}</p>
            </div>
            <p class="text-xs text-gray-400 pt-1">
                Actualizado: {{ $plantilla->updated_at->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
    @endforeach
</div>

<div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl px-5 py-4">
    <p class="text-sm text-blue-700 font-medium">Token disponible en los textos:</p>
    <p class="text-sm text-blue-600 mt-1">
        Usa <code class="bg-blue-100 px-1.5 py-0.5 rounded font-mono text-xs">:nombre</code>
        para insertar el nombre del cliente en el asunto o saludo automáticamente.
    </p>
</div>

@endsection
