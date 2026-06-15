@extends('layouts.admin')
@section('title', 'Nuevo Usuario')
@section('content')

<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors text-sm">← Volver</a>
    <h1 class="text-2xl font-bold text-gray-900">Nuevo Usuario</h1>
</div>

<div class="bg-white rounded-2xl border border-gray-200 shadow-sm px-8 py-8 max-w-lg">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" autofocus
                class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="mb-5">
            <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico <span class="text-red-500">*</span></label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>

        <div class="grid grid-cols-2 gap-5 mb-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña <span class="text-red-500">*</span></label>
                <input type="password" name="password"
                    class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation"
                    class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-blue-500 focus:border-transparent border-gray-300">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-5 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Rol <span class="text-red-500">*</span></label>
                <select name="role"
                    class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('role') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                    <option value="">Seleccionar...</option>
                    <option value="admin"  {{ old('role') === 'admin'  ? 'selected' : '' }}>Administrador</option>
                    <option value="agente" {{ old('role') === 'agente' ? 'selected' : '' }}>Agente</option>
                </select>
                @error('role')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                    class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition focus:ring-2 focus:ring-blue-500 focus:border-transparent border-gray-300"
                    placeholder="+503 7000-0000">
            </div>
        </div>

        <div class="flex items-center gap-4 pt-2 border-t border-gray-100">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm px-6 py-2.5 rounded-lg transition-colors">
                Crear usuario
            </button>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Cancelar</a>
        </div>
    </form>
</div>

@endsection
