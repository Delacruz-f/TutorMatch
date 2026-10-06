@extends('layouts.app')

@section('title', 'Validación de Pagos')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">💳 Validación de Pagos</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <h2 class="text-2xl font-semibold mb-4 text-yellow-600">⏳ Pendientes de Validación ({{ $pagosPendientes->count() }})</h2>

    @if($pagosPendientes->isEmpty())
        <p class="text-gray-500 mb-8">No hay pagos pendientes.</p>
    @else
        <div class="space-y-4 mb-8">
            @foreach($pagosPendientes as $pago)
                <div class="border rounded-lg p-4 bg-yellow-50">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Estudiante</p>
                            <p class="font-bold">{{ $pago->user->nombre }} {{ $pago->user->apellido }}</p>
                            <p class="text-sm">{{ $pago->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tutor / Materia</p>
                            <p class="font-bold">{{ $pago->clase->tutor->user->nombre }} {{ $pago->clase->tutor->user->apellido }}</p>
                            <p class="text-sm">{{ $pago->clase->materia->nombre }}</p>
                            <p class="text-sm">{{ $pago->clase->fecha->format('d/m/Y') }} {{ substr($pago->clase->hora_inicio, 0, 5) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pago</p>
                            <p class="font-bold text-green-600 text-lg">S/ {{ $pago->monto }}</p>
                            <p class="text-sm">Método: {{ strtoupper($pago->metodo) }}</p>
                            <p class="text-sm">Ref: {{ $pago->referencia }}</p>
                        </div>
                    </div>
                    <div class="mt-3 flex gap-3 items-center">
                        <a href="{{ Storage::url($pago->comprobante_imagen) }}" target="_blank"
                           class="bg-blue-500 text-white px-4 py-2 rounded text-sm hover:bg-blue-600">
                            Ver Comprobante
                        </a>
                        <form method="POST" action="{{ route('admin.pagos.aprobar', $pago->id) }}">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded text-sm hover:bg-green-700">
                                ✅ Aprobar
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.pagos.rechazar', $pago->id) }}"
                              onsubmit="return confirm('¿Rechazar este pago?')" class="flex gap-2">
                            @csrf
                            <input type="text" name="motivo" placeholder="Motivo del rechazo" required
                                   class="px-3 py-1 border rounded text-sm">
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded text-sm hover:bg-red-700">
                                ❌ Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <h2 class="text-2xl font-semibold mb-4 text-green-600">✅ Procesados Recientemente</h2>
    @if($pagosRecientes->isEmpty())
        <p class="text-gray-500">Sin pagos procesados aún.</p>
    @else
        <table class="w-full text-sm border-collapse">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Fecha</th>
                    <th class="p-2 text-left">Estudiante</th>
                    <th class="p-2 text-left">Monto</th>
                    <th class="p-2 text-left">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pagosRecientes as $p)
                    <tr class="border-b">
                        <td class="p-2">{{ $p->updated_at->format('d/m/Y H:i') }}</td>
                        <td class="p-2">{{ $p->user->nombre }} {{ $p->user->apellido }}</td>
                        <td class="p-2">S/ {{ $p->monto }}</td>
                        <td class="p-2">
                            @php
                                $colores = [
                                    'pagado' => 'bg-green-100 text-green-800',
                                    'fallido' => 'bg-red-100 text-red-800',
                                    'reembolsado' => 'bg-gray-100 text-gray-800',
                                ];
                                $color = $colores[$p->estado] ?? 'bg-gray-100';
                            @endphp
                            <span class="{{ $color }} px-2 py-1 rounded text-xs">{{ ucfirst($p->estado) }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
