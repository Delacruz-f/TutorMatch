@extends('layouts.app')

@section('title', 'Moderación de Reseñas')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">⭐ Moderación de Reseñas</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="space-y-3">
        @foreach($resenas as $r)
            <div class="border rounded-lg p-4 {{ $r->estado === 'oculta' ? 'bg-gray-100 opacity-60' : '' }}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <p class="font-bold">{{ $r->estudiante->nombre }} → {{ $r->tutor->user->nombre }}</p>
                        <p class="text-yellow-500">{{ str_repeat('⭐', $r->calificacion) }}</p>
                        <p class="text-gray-700 mt-1">{{ $r->comentario }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $r->created_at->diffForHumans() }}</p>
                    </div>
                    <div>
                        @if($r->estado === 'visible')
                            <form method="POST" action="{{ route('admin.resenas.ocultar', $r->id) }}">
                                @csrf
                                <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                    Ocultar
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.resenas.mostrar', $r->id) }}">
                                @csrf
                                <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                    Mostrar
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="mt-4">{{ $resenas->links() }}</div>
</div>
@endsection
