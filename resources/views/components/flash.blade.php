{{--
    Mensaje flash simple (estilo de las pantallas "guest").
    Uso: <x-flash type="success" /> | <x-flash type="error" />
--}}
@props(['type' => 'success'])

@if(session($type))

    <div class="{{ $type === 'error' ? 'bg-red-500/30 border-red-300/40' : 'bg-green-500/30 border-green-300/40' }} border rounded-xl px-4 py-3 mb-4 text-sm flex items-center gap-2">

        <x-icon :name="$type === 'error' ? 'alert-triangle' : 'check-circle'" class="w-4 h-4 shrink-0" />

        {{ session($type) }}

    </div>

@endif
