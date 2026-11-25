@extends('layouts.app')

@section('title', __('ui.write_review'))

@section('content')
<div class="max-w-xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center"><x-icon name="star" class="w-7 h-7" /> {{ __('ui.write_review') }}</h1>

    <div class="bg-blue-50 p-4 rounded mb-6">
        <p><strong>{{ __('ui.tutor') }}:</strong> {{ $clase->tutor->user->nombre }} {{ $clase->tutor->user->apellido }}</p>
        <p><strong>{{ __('ui.subject') }}:</strong> {{ $clase->materia->nombre }}</p>
        <p><strong>{{ __('ui.date') }}:</strong> {{ $clase->fecha->format('d/m/Y') }}</p>
    </div>

    <form method="POST" action="{{ route('resenas.store', $clase->id) }}">
        @csrf

        <div class="mb-4">
            <label class="block font-bold mb-2">{{ __('ui.rating') }}</label>
            <div class="flex gap-2 text-3xl" x-data="{ rating: 5 }">
                <template x-for="i in 5">
                    <button type="button" @click="rating = i"
                            :class="i <= rating ? 'text-orange-300' : 'text-white/25'">
                        <x-icon name="star" class="w-8 h-8" />
                    </button>
                </template>
                <input type="hidden" name="calificacion" :value="rating">
            </div>
        </div>

        <div class="mb-6">
            <label class="block font-bold mb-2">{{ __('ui.optional_comment') }}</label>
            <textarea name="comentario" rows="4" maxlength="1000"
                      class="w-full px-3 py-2 border rounded-md"></textarea>
        </div>

        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-orange-600 text-white py-3 rounded-xl hover:bg-orange-700 font-bold">
            <x-icon name="check" class="w-5 h-5" /> {{ __('ui.submit_review') }}
        </button>
    </form>
</div>
@endsection
