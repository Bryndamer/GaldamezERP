@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500">Bienvenido, {{ Auth::user()->name }}.</p>
    </div>

    <div class="rounded-2xl border border-dashed border-gray-300 bg-white px-8 py-16 text-center">
        <p class="text-gray-400 text-sm">Módulos en construcción — Fase 3</p>
    </div>
@endsection
