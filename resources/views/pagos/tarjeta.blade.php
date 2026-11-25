@extends('layouts.app')

@section('title', __('ui.pay_by_card_title'))

@section('content')
<div class="max-w-xl mx-auto">
    <div class="glass rounded-2xl p-6 text-white mb-6">
        <h1 class="text-3xl font-bold mb-1 flex items-center"><x-icon name="credit-card" class="w-7 h-7" /> {{ __('ui.card_payment') }}</h1>
        <p class="text-white/80">{{ __('ui.secure_payment_with_culqi') }}</p>
    </div>

    <div class="glass rounded-2xl p-6 text-white">
        <!-- Resumen -->
        <div class="bg-white/10 rounded-xl p-4 mb-6">
            <p class="text-sm text-white/70">{{ __('ui.paying_for') }}</p>
            <p class="text-xl font-bold">{{ $clase->materia->nombre }}</p>
            <p class="text-sm text-white/80">{{ __('ui.tutor') }}: {{ $clase->tutor->user->nombre }} {{ $clase->tutor->user->apellido }}</p>
            <p class="text-sm text-white/80">{{ $clase->fecha->format('d/m/Y') }} · {{ substr($clase->hora_inicio, 0, 5) }}</p>
            <p class="text-3xl font-bold text-green-300 mt-3">S/ {{ $clase->precio_total }}</p>
        </div>

        @if(session('error'))
            <div class="bg-red-500/30 border border-red-300/40 rounded-xl px-4 py-3 mb-4 text-sm flex items-center gap-2">
                <x-icon name="alert-triangle" class="w-4 h-4 shrink-0" /> {{ session('error') }}
            </div>
        @endif

        <form id="pago-form" method="POST" action="{{ route('pagos.tarjeta.procesar', $clase->id) }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" id="culqi-token">
            <input type="hidden" name="email" value="{{ Auth::user()->email }}">

            <div>
                <label class="block text-sm font-medium mb-2">{{ __('ui.card_number') }}</label>
                <input type="text" id="card_number" placeholder="4111 1111 1111 1111" maxlength="19" required
                       class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white placeholder-white/50 focus:bg-white/25 focus:outline-none">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-medium mb-2">{{ __('ui.month') }}</label>
                    <input type="text" id="exp_month" placeholder="MM" maxlength="2" required
                           class="w-full px-3 py-3 rounded-xl bg-white/15 border border-white/30 text-white text-center placeholder-white/50 focus:bg-white/25 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-2">{{ __('ui.year') }}</label>
                    <input type="text" id="exp_year" placeholder="YY" maxlength="2" required
                           class="w-full px-3 py-3 rounded-xl bg-white/15 border border-white/30 text-white text-center placeholder-white/50 focus:bg-white/25 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-medium mb-2">CVV</label>
                    <input type="text" id="cvv" placeholder="123" maxlength="4" required
                           class="w-full px-3 py-3 rounded-xl bg-white/15 border border-white/30 text-white text-center placeholder-white/50 focus:bg-white/25 focus:outline-none">
                </div>
            </div>

            <button type="submit" id="btn-pagar"
                    class="btn-white w-full inline-flex items-center justify-center gap-2 py-3 font-bold">
                <x-icon name="lock" class="w-5 h-5" /> {{ __('ui.pay') }} S/ {{ $clase->precio_total }}
            </button>
        </form>

        <div class="mt-4 text-xs text-white/60 text-center flex items-center justify-center gap-1.5">
            <x-icon name="lock" class="w-3.5 h-3.5" /> {{ __('ui.secure_payment_culqi') }}
            @if(config('services.culqi.mode') === 'simulated')
                <br><strong class="text-yellow-300">{{ __('ui.simulated_mode') }}</strong>
            @endif
        </div>
    </div>
</div>

@if(config('services.culqi.mode') === 'simulated')
    {{-- Modo simulado: no usar Culqi.js, generar token fake --}}
    <script>
        document.getElementById('pago-form').addEventListener('submit', function(e) {
            e.preventDefault();
            document.getElementById('culqi-token').value = 'tok_sim_' + Math.random().toString(36).substring(7);
            this.submit();
        });
    </script>
@else
    {{-- Modo real: usar Culqi.js --}}
    <script src="https://checkout.culqi.com/js/v4"></script>
    <script>
        Culqi.publicKey = '{{ $publicKey }}';

        document.getElementById('pago-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-pagar');
            btn.disabled = true;
            btn.textContent = @json(__('ui.processing'));

            Culqi.settings({
                title: 'TutorMatch',
                currency: 'PEN',
                amount: {{ (int) round($clase->precio_total * 100) }},
                description: '{{ $clase->materia->nombre }}',
            });

            Culqi.createToken();
        });

        function culqi() {
            if (Culqi.token) {
                document.getElementById('culqi-token').value = Culqi.token.id;
                document.getElementById('pago-form').submit();
            } else {
                alert(@json(__('ui.error_prefix')) + ' ' + Culqi.error.user_message);
                document.getElementById('btn-pagar').disabled = false;
                document.getElementById('btn-pagar').textContent = @json(__('ui.pay')) + ' S/ {{ $clase->precio_total }}';
            }
        }
    </script>
@endif
@endsection