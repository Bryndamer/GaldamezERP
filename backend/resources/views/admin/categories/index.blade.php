@extends('layouts.admin')
@section('title', 'Categorías')
@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Categorías</h1>
        <p class="mt-0.5 text-sm text-gray-500">Tipos de inmueble disponibles en el sistema</p>
    </div>
    <a href="{{ route('admin.categorias.create') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        + Nueva categoría
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-left">
            <tr>
                <th class="px-5 py-3 font-medium">Nombre</th>
                <th class="px-5 py-3 font-medium">Slug</th>
                <th class="px-5 py-3 font-medium text-center">Inmuebles</th>
                <th class="px-5 py-3 font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($categories as $category)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3 font-medium text-gray-900">{{ $category->name }}</td>
                <td class="px-5 py-3 text-gray-500 font-mono text-xs">{{ $category->slug }}</td>
                <td class="px-5 py-3 text-center">
                    <span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                        {{ $category->inmuebles_count }}
                    </span>
                </td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.categorias.edit', $category) }}"
                           class="text-blue-600 hover:text-blue-800 font-medium transition-colors">Editar</a>
                        <form method="POST" action="{{ route('admin.categorias.destroy', $category) }}"
                              onsubmit="return confirm('¿Eliminar la categoría {{ addslashes($category->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium transition-colors"
                                {{ $category->inmuebles_count > 0 ? 'disabled title=\'Tiene inmuebles asociados\'' : '' }}>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-12 text-center text-gray-400 text-sm">
                    No hay categorías registradas.
                    <a href="{{ route('admin.categorias.create') }}" class="text-blue-600 hover:underline ml-1">Crear la primera</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($categories->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $categories->links() }}</div>
    @endif
</div>

@endsection
