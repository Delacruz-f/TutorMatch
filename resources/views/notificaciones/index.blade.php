@extends('layouts.app')

@section('title', 'Notificaciones')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">🔔 Notificaciones</h1>
        <form method="POST" action="{{ route('notificaciones.leer-todas') }}">
            @csrf
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                Marcar todas como leídas
            </button>
        </form>
    </div>

    @if($notificaciones->isEmpty())
        <p class="text-gray-500 text-center py-8">No tienes notificaciones.</p>
    @else
        <div class="space-y-2">
            @foreach($notificaciones as $n)
                <div class="border rounded-lg p-4 {{ $n->leida ? 'bg-white' : 'bg-blue-50 border-blue-200' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-bold {{ $n->leida ? 'text-gray-700' : 'text-blue-800' }}">
                                {{ $n->titulo }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $n->mensaje }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $n->created_at->diffForHumans() }}</p>
                        </div>
                        <form method="POST" action="{{ route('notificaciones.leer', $n->id) }}">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:underline text-sm">
                                {{ $n->leida ? 'Ver' : 'Marcar leída' }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $notificaciones->links() }}</div>
    @endif
</div>
@endsection
