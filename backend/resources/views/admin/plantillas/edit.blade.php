@extends('layouts.admin')
@section('title', 'Editar Plantilla')

@section('content')

<div class="flex items-center gap-3 mb-6">
    <a href="{{ route('admin.plantillas.index') }}"
       class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-700 transition-colors">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Plantillas
    </a>
    <span class="text-gray-300">/</span>
    <span class="text-sm font-medium text-gray-700">{{ $plantilla->nombre }}</span>
</div>

<div class="max-w-2xl">

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100">
            <h1 class="text-lg font-bold text-gray-900">{{ $plantilla->nombre }}</h1>
            <p class="mt-0.5 text-xs font-mono text-gray-400">{{ $plantilla->identificador }}</p>
        </div>

        <form method="POST" action="{{ route('admin.plantillas.update', $plantilla) }}" class="px-6 py-6 space-y-5">
            @csrf
            @method('PUT')

            {{-- Asunto --}}
            <div>
                <label for="asunto" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Asunto del correo
                    <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="asunto"
                       name="asunto"
                       value="{{ old('asunto', $plantilla->asunto) }}"
                       maxlength="255"
                       class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                              @error('asunto') border-red-400 bg-red-50 @enderror">
                @error('asunto')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Saludo (solo visible si la plantilla lo tiene o es del cliente) --}}
            <div>
                <label for="saludo" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Saludo inicial
                    <span class="text-gray-400 font-normal">(opcional)</span>
                </label>
                <input type="text"
                       id="saludo"
                       name="saludo"
                       value="{{ old('saludo', $plantilla->saludo) }}"
                       maxlength="255"
                       placeholder="Ej: ¡Gracias por contactarnos, :nombre!"
                       class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                              @error('saludo') border-red-400 bg-red-50 @enderror">
                @error('saludo')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cuerpo principal --}}
            <div>
                <label for="cuerpo_principal" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Cuerpo principal
                    <span class="text-red-500">*</span>
                </label>
                <textarea id="cuerpo_principal"
                          name="cuerpo_principal"
                          rows="4"
                          maxlength="2000"
                          class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none
                                 @error('cuerpo_principal') border-red-400 bg-red-50 @enderror">{{ old('cuerpo_principal', $plantilla->cuerpo_principal) }}</textarea>
                @error('cuerpo_principal')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cuerpo secundario --}}
            <div>
                <label for="cuerpo_secundario" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Párrafo adicional
                    <span class="text-gray-400 font-normal">(opcional)</span>
                </label>
                <textarea id="cuerpo_secundario"
                          name="cuerpo_secundario"
                          rows="3"
                          maxlength="2000"
                          class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400
                                 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none
                                 @error('cuerpo_secundario') border-red-400 bg-red-50 @enderror">{{ old('cuerpo_secundario', $plantilla->cuerpo_secundario) }}</textarea>
                @error('cuerpo_secundario')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Firma --}}
            <div>
                <label for="firma" class="block text-sm font-medium text-gray-700 mb-1.5">
                    Firma
                    <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       id="firma"
                       name="firma"
                       value="{{ old('firma', $plantilla->firma) }}"
                       maxlength="255"
                       class="w-full rounded-lg border border-gray-300 px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400
                              focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                              @error('firma') border-red-400 bg-red-50 @enderror">
                @error('firma')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Token info --}}
            <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 text-sm text-amber-700">
                Usa <code class="bg-amber-100 px-1 py-0.5 rounded font-mono text-xs">:nombre</code>
                en el asunto o saludo para insertar el nombre del cliente automáticamente.
            </div>

            {{-- Acciones --}}
            <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100">
                <a href="{{ route('admin.plantillas.index') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 transition-colors">
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-lg transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar cambios
                </button>
            </div>

        </form>
    </div>

</div>

@endsection
