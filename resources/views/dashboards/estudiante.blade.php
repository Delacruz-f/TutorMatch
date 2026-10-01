@extends('layouts.app')

@section('title', 'Dashboard Estudiante')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Panel de Estudiante</h1>
    <p class="text-gray-600">Bienvenido, {{ Auth::user()->nombre }}. Aquí podrás buscar tutores y reservar clases.</p>
</div>
@endsection
