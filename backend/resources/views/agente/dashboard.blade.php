@extends('layouts.admin')

@section('title', 'Mi Portal')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Portal de Agente</h1>
        <p class="mt-1 text-sm text-gray-500">Bienvenido, {{ Auth::user()->name }}.</p>
    </div>

    <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-8 py-16 text-center">
        <p class="text-gray-400 text-sm">Módulo de gestión de inmuebles — Fase 3</p>
    </div>
@endsection
