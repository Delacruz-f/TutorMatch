{{--
    Alertas flash del panel (equivalente al bloque "ALERTAS" de layouts/app).
    Uso: <x-alert />
--}}
@if(session('success'))

    <div class="mb-4 glass text-white px-4 py-3 rounded-xl border-l-4 border-green-400 flex items-center gap-3">

        <x-icon name="check-circle" class="w-5 h-5 shrink-0" />

        {{ session('success') }}

    </div>

@endif


@if(session('error'))

    <div class="mb-4 glass text-white px-4 py-3 rounded-xl border-l-4 border-red-400 flex items-center gap-3">

        <x-icon name="alert-triangle" class="w-5 h-5 shrink-0" />

        {{ session('error') }}

    </div>

@endif


@if($errors->any())

    <div class="mb-4 glass text-white px-4 py-3 rounded-xl border-l-4 border-red-400">

        <p class="font-bold mb-1 flex items-center gap-2">
            <x-icon name="alert-triangle" class="w-4 h-4" /> {{ __('ui.review_errors') }}
        </p>


        <ul class="list-disc list-inside text-sm">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif
