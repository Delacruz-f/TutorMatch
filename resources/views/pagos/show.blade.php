@extends('layouts.app')

@section('title', 'Pagar Clase')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">💳 Completar Pago</h1>

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
        <h2 class="font-bold mb-2">Resumen de la Reserva</h2>

        <p>
            <strong>Tutor:</strong>
            {{ $clase->tutor->user->nombre }}
            {{ $clase->tutor->user->apellido }}
        </p>

        <p>
            <strong>Materia:</strong>
            {{ $clase->materia->nombre }}
        </p>

        <p>
            <strong>Fecha:</strong>
            {{ $clase->fecha->format('d/m/Y') }}
        </p>

        <p>
            <strong>Hora:</strong>
            {{ substr($clase->hora_inicio, 0, 5) }}
            -
            {{ substr($clase->hora_fin, 0, 5) }}
        </p>

        <p>
            <strong>Duración:</strong>
            {{ $clase->duracion_min }} minutos
        </p>

        <hr class="my-2">

        <p class="text-2xl font-bold text-green-600">
            Total: S/ {{ $clase->precio_total }}
        </p>
    </div>

    <!-- Pago mediante Yape / Plin -->
    <form method="POST"
          action="{{ route('pagos.store', $clase->id) }}"
          enctype="multipart/form-data">

        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">
                Método de pago
            </label>

            <select name="metodo"
                    required
                    class="w-full px-3 py-2 border rounded-md">

                <option value="yape">
                    📱 Yape
                </option>

                <option value="plin">
                    📱 Plin
                </option>

                <option value="tarjeta">
                    💳 Tarjeta
                </option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">
                N° de operación / Referencia
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
                Captura del comprobante
            </label>

            <input type="file"
                   name="comprobante_imagen"
                   accept="image/*"
                   required
                   class="w-full px-3 py-2 border rounded-md">

            <p class="text-xs text-gray-500 mt-1">
                Sube una captura de tu Yape/Plin (JPG o PNG, máx 2MB)
            </p>
        </div>

        <button type="submit"
                class="w-full bg-green-600 text-white py-3 rounded-md hover:bg-green-700 font-bold">
            Enviar Pago para Validación
        </button>
    </form>

    <!-- Separador -->
    <div class="text-center my-4 text-gray-400 text-sm">
        o
    </div>

    <!-- Pago con tarjeta mediante Culqi -->
    <a href="{{ route('pagos.tarjeta', $clase->id) }}"
       class="block w-full text-center bg-gradient-to-r from-purple-600 to-blue-600 text-white py-3 rounded-xl font-bold hover:opacity-90 transition">

        💳 Pagar con Tarjeta (Culqi)

    </a>
</div>
@endsection
