@extends('layouts.app')

@section('title', __('ui.reports'))

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md mb-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-4 flex items-center"><x-icon name="chart" class="w-7 h-7" /> {{ __('ui.income_reports') }}</h1>

    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
    <div>
        <label class="block text-sm font-bold mb-1">{{ __('ui.from') }}</label>
        <input type="date" name="desde" value="{{ $desde }}" class="w-full px-3 py-2 border rounded">
    </div>
    <div>
        <label class="block text-sm font-bold mb-1">{{ __('ui.until') }}</label>
        <input type="date" name="hasta" value="{{ $hasta }}" class="w-full px-3 py-2 border rounded">
    </div>
    <div class="flex items-end">
        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Generar Reporte
        </button>
    </div>
    <div class="flex items-end">
        <a href="{{ route('admin.reportes.pdf', ['desde' => $desde, 'hasta' => $hasta]) }}"
           class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 text-center font-bold">
            <span class="inline-flex items-center justify-center gap-1.5"><x-icon name="download" class="w-4 h-4" /> {{ __('ui.download_pdf') }}</span>
        </a>
    </div>
</form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-green-50 p-6 rounded-lg shadow">
        <p class="text-sm text-green-700">{{ __('ui.total_income') }}</p>
        <p class="text-3xl font-bold text-green-800">S/ {{ number_format($totalIngresos, 2) }}</p>
    </div>
    <div class="bg-blue-50 p-6 rounded-lg shadow">
        <p class="text-sm text-blue-700">{{ __('ui.class_commissions') }}</p>
        <p class="text-3xl font-bold text-blue-800">S/ {{ number_format($comisiones, 2) }}</p>
    </div>
    <div class="bg-purple-50 p-6 rounded-lg shadow">
        <p class="text-sm text-purple-700">{{ __('ui.subscriptions') }}</p>
        <p class="text-3xl font-bold text-purple-800">S/ {{ number_format($ingresosSuscripciones, 2) }}</p>
    </div>
    <div class="bg-yellow-50 p-6 rounded-lg shadow">
        <p class="text-sm text-yellow-700">{{ __('ui.completed_classes') }}</p>
        <p class="text-3xl font-bold text-yellow-800">{{ $clasesCompletadas }}</p>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-3">{{ __('ui.new_users') }}</h2>
        <p class="text-4xl font-bold text-blue-600">{{ $nuevosUsuarios }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-3">{{ __('ui.paid_withdrawals') }}</h2>
        <p class="text-4xl font-bold text-red-600">S/ {{ number_format($retirosPagados, 2) }}</p>
    </div>
</div>

<div class="bg-white p-6 rounded-lg shadow mt-6">
    <h2 class="text-xl font-bold mb-3 flex items-center"><x-icon name="trophy" class="w-5 h-5" /> {{ __('ui.top_tutors_period') }}</h2>
    @if($topTutores->isEmpty())
        <p class="text-gray-500">{{ __('ui.no_data') }}</p>
    @else
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 text-left">{{ __('ui.tutor') }}</th>
                    <th class="p-2 text-left">{{ __('ui.generated_income') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topTutores as $t)
                    <tr class="border-b">
                        <td class="p-2">{{ $t->tutor->user->nombre }} {{ $t->tutor->user->apellido }}</td>
                        <td class="p-2 font-bold text-green-600">S/ {{ number_format($t->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
