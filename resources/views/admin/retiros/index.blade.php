@extends('layouts.app')

@section('title', 'Gestión de Retiros')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">💰 Gestión de Retiros</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    <h2 class="text-2xl font-semibold mb-4 text-yellow-600">⏳ Pendientes ({{ $pendientes->count() }})</h2>

    @if($pendientes->isEmpty())
        <p class="text-gray-500 mb-8">No hay retiros pendientes.</p>
    @else
        <div class="space-y-3 mb-8">
            @foreach($pendientes as $r)
                <div class="border rounded-lg p-4 bg-yellow-50">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Tutor</p>
                            <p class="font-bold">{{ $r->tutor->user->nombre }} {{ $r->tutor->user->apellido }}</p>
                            <p class="text-sm">{{ $r->tutor->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Método / Cuenta</p>
                            <p class="font-bold">{{ strtoupper($r->metodo) }}: {{ $r->numero_cuenta }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Monto</p>
                            <p class="text-2xl font-bold text-green-600">S/ {{ $r->monto }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-3">
                        <form method="POST" action="{{ route('admin.retiros.pagar', $r->id) }}">
                            @csrf
                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                                ✅ Marcar como Pagado
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.retiros.rechazar', $r->id) }}"
                              onsubmit="return confirm('¿Rechazar este retiro?')" class="flex gap-2">
                            @csrf
                            <input type="text" name="motivo" placeholder="Motivo" required
                                   class="px-3 py-1 border rounded text-sm">
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                                ❌ Rechazar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <h2 class="text-2xl font-semibold mb-4 text-green-600">Historial</h2>
    @if($historial->isEmpty())
        <p class="text-gray-500">Sin historial.</p>
    @else
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">Fecha</th>
                    <th class="p-2 text-left">Tutor</th>
                    <th class="p-2 text-left">Monto</th>
                    <th class="p-2 text-left">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historial as $h)
                    <tr class="border-b">
                        <td class="p-2">{{ $h->updated_at->format('d/m/Y') }}</td>
                        <td class="p-2">{{ $h->tutor->user->nombre }}</td>
                        <td class="p-2">S/ {{ $h->monto }}</td>
                        <td class="p-2">
                            <span class="text-xs px-2 py-1 rounded
                                {{ $h->estado === 'pagado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($h->estado) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
