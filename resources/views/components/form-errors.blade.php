{{--
    Errores de validación con estilo de las pantallas "guest" (auth).
    Uso: <x-form-errors />
--}}
@props(['blur' => false])

@if($errors->any())

    <div class="bg-red-500/30 border border-red-300/40 rounded-xl px-4 py-3 mb-4 text-sm @if($blur) backdrop-blur @endif">

        @foreach($errors->all() as $error)
            <p class="flex items-start gap-2"><x-icon name="alert-triangle" class="w-4 h-4 shrink-0 mt-0.5" /> <span>{{ $error }}</span></p>
        @endforeach

    </div>

@endif
