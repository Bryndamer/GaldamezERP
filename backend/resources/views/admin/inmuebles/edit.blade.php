@extends('layouts.admin')

@section('title', 'Editar Inmueble')

@section('content')

<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.inmuebles.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
        ← Volver
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Editar Inmueble</h1>
        <p class="mt-0.5 text-sm text-gray-500">{{ $inmueble->titulo }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-8 py-8 max-w-4xl">
    <form method="POST" action="{{ route('admin.inmuebles.update', $inmueble) }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')

        @include('admin.inmuebles._form', ['categories' => $categories, 'inmueble' => $inmueble])

        <div class="flex items-center gap-4 pt-2 border-t border-gray-100">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-6 py-2.5 rounded-lg transition-colors">
                Actualizar inmueble
            </button>
            <a href="{{ route('admin.inmuebles.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                Cancelar
            </a>
        </div>
    </form>
</div>

@endsection
