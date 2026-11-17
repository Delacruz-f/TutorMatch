@extends('layouts.app')

@section('title', __('ui.pay_class_title'))

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center"><x-icon name="credit-card" class="w-7 h-7" /> {{ __('ui.complete_payment') }}</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Resumen -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6">
        <h2 class="font-bold mb-2">{{ __('ui.booking_summary') }}</h2>

        <p>
            <strong>{{ __('ui.tutor') }}:</strong>
            {{ $clase->tutor->user->nombre }}
            {{ $clase->tutor->user->apellido }}
        </p>

        <p>
            <strong>{{ __('ui.subject') }}:</strong>
            {{ $clase->materia->nombre }}
        </p>

        <p>
            <strong>{{ __('ui.date') }}:</strong>
            {{ $clase->fecha->format('d/m/Y') }}
        </p>

        <p>
            <strong>{{ __('ui.time_label') }}</strong>
            {{ substr($clase->hora_inicio, 0, 5) }}
            -
            {{ substr($clase->hora_fin, 0, 5) }}
        </p>

        <p>
            <strong>{{ __('ui.duration') }}:</strong>
            {{ $clase->duracion_min }} minutos
        </p>

        <hr class="my-2">

        <p class="text-2xl font-bold text-green-600">
            {{ __('ui.total_suffix') }} S/ {{ $clase->precio_total }}
        </p>
    </div>

    <!-- Pago mediante Yape / Plin -->
    <form method="POST"
          action="{{ route('pagos.store', $clase->id) }}"
          enctype="multipart/form-data">

        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">
                {{ __('ui.payment_method_label') }}
            </label>

            <select name="metodo"
                    required
                    class="w-full px-3 py-2 border rounded-md">

                <option value="yape">{{ __('ui.method_labels.yape') }}</option>

                <option value="plin">{{ __('ui.method_labels.plin') }}</option>

                <option value="tarjeta">{{ __('ui.method_labels.tarjeta') }}</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">
                {{ __('ui.operation_reference') }}
            </label>

            <input type="text"
                   name="referencia"
                   required
                   maxlength="100"
                   value="{{ old('referencia') }}"
                   class="w-full px-3 py-2 border rounded-md">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">
                {{ __('ui.capture_receipt') }}
            </label>

            <input type="file"
                   name="comprobante_imagen"
                   accept="image/*"
                   required
                   class="w-full px-3 py-2 border rounded-md">

            <p class="text-xs text-gray-500 mt-1">
                {{ __('ui.upload_payment_capture') }}
            </p>
        </div>

        <button type="submit"
                class="w-full inline-flex items-center justify-center gap-2 bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 font-bold">
            <x-icon name="check-circle" class="w-5 h-5" /> {{ __('ui.send_payment_validation') }}
        </button>
    </form>

    <!-- Separador -->
    <div class="text-center my-4 text-gray-400 text-sm">
        {{ __('ui.or') }}
    </div>

    <!-- Pago con tarjeta mediante Culqi -->
    <a href="{{ route('pagos.tarjeta', $clase->id) }}"
       class="w-full inline-flex items-center justify-center gap-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white py-3 rounded-xl font-bold hover:opacity-90 transition">

        <x-icon name="credit-card" class="w-5 h-5" /> {{ __('ui.pay_with_card_culqi') }}

    </a>
</div>
@endsection
