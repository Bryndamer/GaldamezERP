@extends('layouts.admin')
@section('title', 'Nueva Categoría')
@section('content')

<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.categorias.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors text-sm">← Volver</a>
    <h1 class="text-2xl font-bold text-gray-900">Nueva Categoría</h1>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-8 py-8 max-w-lg">
    <form method="POST" action="{{ route('admin.categorias.store') }}">
        @csrf
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" autofocus
                class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition
                       focus:ring-2 focus:ring-blue-500 focus:border-transparent
                       {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                placeholder="Ej: Dúplex">
            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            <p class="mt-1 text-xs text-gray-400">El slug se genera automáticamente a partir del nombre.</p>
        </div>
        <div class="flex items-center gap-4 pt-2 border-t border-gray-100">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-6 py-2.5 rounded-lg transition-colors">
                Guardar categoría
            </button>
            <a href="{{ route('admin.categorias.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
        </div>
    </form>
</div>

@endsection
