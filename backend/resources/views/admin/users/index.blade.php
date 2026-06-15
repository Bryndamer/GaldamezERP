@extends('layouts.admin')
@section('title', 'Usuarios')
@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Usuarios</h1>
        <p class="mt-0.5 text-sm text-gray-500">Administradores y agentes del sistema</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
        + Nuevo usuario
    </a>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 text-left">
            <tr>
                <th class="px-5 py-3 font-medium">Usuario</th>
                <th class="px-5 py-3 font-medium">Rol</th>
                <th class="px-5 py-3 font-medium">Teléfono</th>
                <th class="px-5 py-3 font-medium">Registrado</th>
                <th class="px-5 py-3 font-medium">Acciones</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition-colors {{ $user->id === Auth::id() ? 'bg-blue-50/30' : '' }}">
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600 flex-shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">
                                {{ $user->name }}
                                @if($user->id === Auth::id())
                                    <span class="ml-1 text-xs text-blue-600 font-normal">(tú)</span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-400">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-3">
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium capitalize
                        {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }}">
                        {{ $user->role }}
                    </span>
                </td>
                <td class="px-5 py-3 text-gray-500">{{ $user->phone ?? '—' }}</td>
                <td class="px-5 py-3 text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="px-5 py-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="text-blue-600 hover:text-blue-800 font-medium transition-colors">Editar</a>
                        @if($user->id !== Auth::id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('¿Eliminar al usuario {{ addslashes($user->name) }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 font-medium transition-colors">
                                Eliminar
                            </button>
                        </form>
                        @else
                        <span class="text-gray-300 text-xs cursor-not-allowed" title="No puedes eliminarte a ti mismo">Eliminar</span>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-5 py-12 text-center text-gray-400 text-sm">No hay usuarios registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">{{ $users->links() }}</div>
    @endif
</div>

@endsection
