@extends('layouts.admin')
@section('title', 'Editar Usuario')
@section('content')

<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors text-sm">← Volver</a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Editar Usuario</h1>
        <p class="mt-0.5 text-sm text-gray-500">{{ $user->email }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-8 py-8 max-w-lg">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" autofocus
                class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nueva contraseña
                <span class="text-gray-400 font-normal">(dejar en blanco para mantener la actual)</span>
            </label>
            <div class="grid grid-cols-2 gap-4">
                <input type="password" name="password"
                    class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                    placeholder="Mínimo 8 caracteres">
                <input type="password" name="password_confirmation"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Confirmar">
            </div>
            @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-5 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rol <span class="text-red-500">*</span></label>
                <select name="role"
                    class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('role') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    <option value="admin"  {{ old('role', $user->role) === 'admin'  ? 'selected' : '' }}>Administrador</option>
                    <option value="agente" {{ old('role', $user->role) === 'agente' ? 'selected' : '' }}>Agente</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
        </div>

        <div class="flex items-center gap-4 pt-2 border-t border-gray-100">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-6 py-2.5 rounded-lg transition-colors">
                Actualizar usuario
            </button>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
        </div>
    </form>
</div>

@endsection
