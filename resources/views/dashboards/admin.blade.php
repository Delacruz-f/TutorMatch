@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Panel de Administrador</h1>
    <p class="text-gray-600 mb-6">Bienvenido, {{ Auth::user()->nombre }}.</p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.tutores.index') }}"
           class="block p-6 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition">
            <h3 class="text-xl font-semibold text-yellow-800">👨‍🏫 Gestión de Tutores</h3>
            <p class="text-sm text-yellow-700 mt-1">Aprobar o rechazar tutores pendientes</p>
        </a>
        <div class="p-6 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="text-xl font-semibold text-blue-800">👥 Usuarios</h3>
            <p class="text-sm text-blue-700 mt-1">Próximamente...</p>
        </div>
        <div class="p-6 bg-green-50 border border-green-200 rounded-lg">
            <h3 class="text-xl font-semibold text-green-800">💰 Transacciones</h3>
            <p class="text-sm text-green-700 mt-1">Próximamente...</p>
        </div>
    </div>
</div>
@endsection
