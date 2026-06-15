{{--
    Partial compartido entre create.blade.php y edit.blade.php
    Variables esperadas: $categories (Collection), $inmueble (Inmueble|null)
--}}
@php $editing = isset($inmueble) && $inmueble->exists; @endphp

{{-- Título --}}
<div class="mb-5">
    <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
    <input type="text" name="titulo" value="{{ old('titulo', $inmueble->titulo ?? '') }}"
        class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition
               focus:ring-2 focus:ring-blue-500 focus:border-transparent
               {{ $errors->has('titulo') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
        placeholder="Ej: Casa moderna en Santa Tecla" maxlength="255">
    @error('titulo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

{{-- Descripción --}}
<div class="mb-5">
    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción <span class="text-red-500">*</span></label>
    <textarea name="descripcion" rows="4"
        class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition
               focus:ring-2 focus:ring-blue-500 focus:border-transparent
               {{ $errors->has('descripcion') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
        placeholder="Descripción detallada del inmueble..." maxlength="5000">{{ old('descripcion', $inmueble->descripcion ?? '') }}</textarea>
    @error('descripcion')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

{{-- Fila: Precio + Metraje --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Precio (USD) <span class="text-red-500">*</span></label>
        <input type="number" name="precio" value="{{ old('precio', $inmueble->precio ?? '') }}"
            step="0.01" min="0.01"
            class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition
                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                   {{ $errors->has('precio') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
            placeholder="0.00">
        @error('precio')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Metraje (m²) <span class="text-red-500">*</span></label>
        <input type="number" name="metraje" value="{{ old('metraje', $inmueble->metraje ?? '') }}"
            step="0.01" min="0.01"
            class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition
                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                   {{ $errors->has('metraje') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
            placeholder="0.00">
        @error('metraje')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Fila: Habitaciones + Baños --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Habitaciones <span class="text-red-500">*</span></label>
        <input type="number" name="habitaciones" value="{{ old('habitaciones', $inmueble->habitaciones ?? '') }}"
            min="0" max="99"
            class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition
                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                   {{ $errors->has('habitaciones') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
        @error('habitaciones')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Baños <span class="text-red-500">*</span></label>
        <input type="number" name="banos" value="{{ old('banos', $inmueble->banos ?? '') }}"
            min="0" max="99"
            class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition
                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                   {{ $errors->has('banos') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
        @error('banos')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Dirección --}}
<div class="mb-5">
    <label class="block text-sm font-medium text-gray-700 mb-1">Dirección <span class="text-red-500">*</span></label>
    <input type="text" name="direccion" value="{{ old('direccion', $inmueble->direccion ?? '') }}"
        class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition
               focus:ring-2 focus:ring-blue-500 focus:border-transparent
               {{ $errors->has('direccion') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
        placeholder="Colonia, municipio, departamento" maxlength="255">
    @error('direccion')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>

{{-- Fila: Tipo + Estado + Categoría --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-5">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
        <select name="tipo"
            class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition
                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                   {{ $errors->has('tipo') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            <option value="">Seleccionar...</option>
            @foreach(['casa' => 'Casa', 'apto' => 'Apartamento', 'terreno' => 'Terreno'] as $val => $label)
                <option value="{{ $val }}" {{ old('tipo', $inmueble->tipo ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('tipo')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-red-500">*</span></label>
        <select name="estado"
            class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition
                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                   {{ $errors->has('estado') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            <option value="">Seleccionar...</option>
            @foreach(['disponible' => 'Disponible', 'reservado' => 'Reservado', 'vendido' => 'Vendido'] as $val => $label)
                <option value="{{ $val }}" {{ old('estado', $inmueble->estado ?? 'disponible') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('estado')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Categoría <span class="text-red-500">*</span></label>
        <select name="category_id"
            class="w-full rounded-lg border px-3 py-2 text-sm shadow-sm outline-none transition
                   focus:ring-2 focus:ring-blue-500 focus:border-transparent
                   {{ $errors->has('category_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            <option value="">Seleccionar...</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                    {{ old('category_id', $inmueble->category_id ?? '') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
        @error('category_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Fotos existentes (solo en edición) --}}
@if($editing && !empty($inmueble->fotos))
<div class="mb-5">
    <label class="block text-sm font-medium text-gray-700 mb-2">Fotos actuales</label>
    <div class="flex flex-wrap gap-3">
        @foreach($inmueble->fotos as $foto)
        <img src="{{ Storage::url($foto) }}" alt="Foto inmueble"
             class="h-20 w-20 object-cover rounded-lg border border-gray-200 shadow-sm">
        @endforeach
    </div>
    <p class="mt-1 text-xs text-gray-500">Para reemplazar las fotos, selecciona nuevas imágenes abajo.</p>
</div>
@endif

{{-- Upload de fotos --}}
<div class="mb-6">
    <label class="block text-sm font-medium text-gray-700 mb-1">
        {{ $editing ? 'Reemplazar fotos' : 'Fotos' }}
        <span class="text-gray-400 font-normal">(máx. 20 — JPG, PNG o WebP — 10 MB c/u)</span>
    </label>
    <input type="file" name="fotos[]" multiple accept="image/jpeg,image/png,image/webp"
        class="block w-full text-sm text-gray-600
               file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
               file:bg-blue-50 file:text-blue-700 file:font-medium
               hover:file:bg-blue-100 cursor-pointer
               {{ $errors->has('fotos') || $errors->has('fotos.*') ? 'border border-red-400 rounded-lg p-2 bg-red-50' : '' }}">
    @error('fotos')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    @error('fotos.*')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
</div>
