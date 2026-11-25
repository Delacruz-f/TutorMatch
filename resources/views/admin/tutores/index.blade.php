@extends('layouts.app')

@section('title', __('ui.manage_tutors'))

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ __('ui.manage_tutors') }}</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tutores Pendientes -->
    <h2 class="text-2xl font-semibold mb-4 text-yellow-600">⏳ {{ __('ui.pending_approval_count', ['count' => $tutoresPendientes->count()]) }}</h2>

    @if($tutoresPendientes->isEmpty())
        <p class="text-gray-500 mb-6">{{ __('ui.no_pending_tutor_requests') }}</p>
    @else
        <div class="space-y-4 mb-8">
            @foreach($tutoresPendientes as $tutor)
                <div class="border rounded-lg p-4 bg-yellow-50">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-bold text-lg">{{ $tutor->user->nombre }} {{ $tutor->user->apellido }}</h3>
                            <p class="text-sm text-gray-600">{{ $tutor->user->email }}</p>
                            <p class="text-sm mt-1"><strong>{{ __('ui.university_label') }}</strong> {{ $tutor->universidad }}</p>
                            <p class="text-sm"><strong>{{ __('ui.degree_label') }}</strong> {{ $tutor->carrera }}</p>
                            <p class="text-sm"><strong>{{ __('ui.national_id_label') }}</strong> {{ $tutor->dni_numero }}</p>
                            <p class="text-sm"><strong>{{ __('ui.hourly_price_label') }}</strong> S/ {{ $tutor->precio_hora }}</p>
                            <p class="text-sm mt-1"><strong>{{ __('ui.subjects_label') }}</strong>
                                {{ $tutor->materias->pluck('nombre')->join(', ') }}
                            </p>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('admin.tutores.documento', [$tutor->id, 'dni']) }}" target="_blank"
                               class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 text-center">
                                {{ __('ui.view_national_id') }}
                            </a>
                            <a href="{{ route('admin.tutores.documento', [$tutor->id, 'cv']) }}" target="_blank"
                               class="bg-purple-500 text-white px-3 py-1 rounded text-sm hover:bg-purple-600 text-center">
                                {{ __('ui.view_resume') }}
                            </a>
                        </div>
                    </div>
                    <div class="flex gap-2 mt-4">
                        <form method="POST" action="{{ route('admin.tutores.aprobar', $tutor->id) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-1.5 bg-green-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-green-700">
                                <x-icon name="check" class="w-4 h-4" /> {{ __('ui.approve') }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.tutores.rechazar', $tutor->id) }}"
                              onsubmit="return confirm(@json(__('ui.reject_tutor_confirm')))">
                            @csrf
                            <input type="text" name="motivo_rechazo" placeholder="{{ __('ui.reject_reason') }}" required
                                   class="px-3 py-2 border rounded mr-2">
                            <button type="submit" class="inline-flex items-center gap-1.5 bg-red-600 text-white px-4 py-2 rounded-xl font-semibold hover:bg-red-700">
                                <x-icon name="x" class="w-4 h-4" /> {{ __('ui.reject') }}
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Tutores Aprobados Recientes -->
    <h2 class="text-2xl font-semibold mb-4 text-green-600 flex items-center"><x-icon name="check-circle" class="w-6 h-6" /> {{ __('ui.recent_approved_tutors') }}</h2>
    @if($tutoresAprobados->isEmpty())
        <p class="text-gray-500">{{ __('ui.no_approved_tutors') }}</p>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($tutoresAprobados as $tutor)
                <div class="border rounded-lg p-4 bg-green-50">
                    <h3 class="font-bold">{{ $tutor->user->nombre }} {{ $tutor->user->apellido }}</h3>
                    <p class="text-sm text-gray-600">{{ $tutor->carrera }} - {{ $tutor->universidad }}</p>
                    <p class="text-sm">S/ {{ $tutor->precio_hora }}/hora</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
