@extends('layouts.app')

@section('title', 'Dejar Reseña')

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">⭐ Dejar Reseña</h1>

    <div class="bg-blue-50 p-4 rounded mb-6">
        <p><strong>Tutor:</strong> {{ $clase->tutor->user->nombre }} {{ $clase->tutor->user->apellido }}</p>
        <p><strong>Materia:</strong> {{ $clase->materia->nombre }}</p>
        <p><strong>Fecha:</strong> {{ $clase->fecha->format('d/m/Y') }}</p>
    </div>

    <form method="POST" action="{{ route('resenas.store', $clase->id) }}">
        @csrf

        <div class="mb-4">
            <label class="block font-bold mb-2">Calificación</label>
            <div class="flex gap-2 text-3xl" x-data="{ rating: 5 }">
                <template x-for="i in 5">
                    <button type="button" @click="rating = i"
                            :class="i <= rating ? 'text-yellow-500' : 'text-gray-300'">
                        ⭐
                    </button>
                </template>
                <input type="hidden" name="calificacion" :value="rating">
            </div>
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-2">Comentario (opcional)</label>
            <textarea name="comentario" rows="4" maxlength="1000"
                      class="w-full px-3 py-2 border rounded-md"></textarea>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 font-bold">
            Enviar Reseña
        </button>
    </form>
</div>
@endsection
