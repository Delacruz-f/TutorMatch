@extends('layouts.app')

@section('title', 'Retiros')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h1 class="text-3xl font-bold text-gray-800">💰 Retiros</h1>
    <p class="text-2xl font-bold text-green-600 mt-2">Saldo disponible: S/ {{ $saldo }}</p>
</div>

<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h2 class="text-2xl font-bold mb-4">Solicitar Retiro</h2>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tutor.retiros.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @csrf
        <input type="number" name="monto" step="0.01" min="10" max="{{ $saldo }}" placeholder="Monto (S/)" required
               class="px-3 py-2 border rounded-md">
        <select name="metodo" required class="px-3 py-2 border rounded-md">
            <option value="yape">Yape</option>
            <option value="plin">Plin</option>
        </select>
        <input type="text" name="numero_cuenta" placeholder="N° de celular" required
               class="px-3 py-2 border rounded-md">
        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            Solicitar Retiro
        </button>
    </form>
</div>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-4">Historial de Retiros</h2>
    @if($retiros->isEmpty())
        <p class="text-gray-500">No has solicitado retiros aún.</p>
    @else
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Fecha</th>
                    <th class="p-2 text-left">Monto</th>
                    <th class="p-2 text-left">Método</th>
                    <th class="p-2 text-left">N° Cuenta</th>
                    <th class="p-2 text-left">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($retiros as $r)
                    <tr class="border-b">
                        <td class="p-2">{{ $r->created_at->format('d/m/Y') }}</td>
                        <td class="p-2">S/ {{ $r->monto }}</td>
                        <td class="p-2">{{ strtoupper($r->metodo) }}</td>
                        <td class="p-2">{{ $r->numero_cuenta }}</td>
                        <td class="p-2">
                            <span class="text-xs px-2 py-1 rounded
                                {{ $r->estado === 'pagado' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $r->estado === 'pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $r->estado === 'rechazado' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($r->estado) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
