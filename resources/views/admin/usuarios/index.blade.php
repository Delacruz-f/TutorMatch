@extends('layouts.app')

@section('title', __('ui.manage_users'))

@section('content')
<div class="glass rounded-2xl p-6 text-white mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">{{ __('ui.manage_users') }}</h1>
            <p class="text-white/80">{{ __('ui.administer_users') }}</p>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="btn-white inline-flex items-center gap-2 px-5 py-2.5 font-bold">
            <x-icon name="plus" class="w-4 h-4" /> {{ __('ui.new_user') }}
        </a>
    </div>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    <div class="glass rounded-xl p-4 text-white text-center">
        <p class="text-xs text-white/70 uppercase">{{ __('ui.total') }}</p>
        <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
    </div>
    <div class="glass rounded-xl p-4 text-white text-center">
        <p class="text-xs text-white/70 uppercase">{{ __('ui.students') }}</p>
        <p class="text-2xl font-bold">{{ $stats['estudiantes'] }}</p>
    </div>
    <div class="glass rounded-xl p-4 text-white text-center">
        <p class="text-xs text-white/70 uppercase">{{ __('ui.tutors') }}</p>
        <p class="text-2xl font-bold">{{ $stats['tutores'] }}</p>
    </div>
    <div class="glass rounded-xl p-4 text-white text-center">
        <p class="text-xs text-white/70 uppercase">{{ __('ui.admins') }}</p>
        <p class="text-2xl font-bold">{{ $stats['administradores'] }}</p>
    </div>
    <div class="glass rounded-xl p-4 text-white text-center">
        <p class="text-xs text-white/70 uppercase">{{ __('ui.active_plural') }}</p>
        <p class="text-2xl font-bold text-green-200">{{ $stats['activos'] }}</p>
    </div>
    <div class="glass rounded-xl p-4 text-white text-center">
        <p class="text-xs text-white/70 uppercase">{{ __('ui.suspended_plural') }}</p>
        <p class="text-2xl font-bold text-red-200">{{ $stats['suspendidos'] }}</p>
    </div>
</div>

<!-- Filtros -->
<div class="glass rounded-2xl p-4 text-white mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('ui.search_name_or_email') }}"
               class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white placeholder-white/60 focus:bg-white/25 focus:outline-none">
        <select name="rol" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
            <option value="" class="text-gray-800">{{ __('ui.all_roles') }}</option>
            <option value="estudiante" {{ request('rol') == 'estudiante' ? 'selected' : '' }} class="text-gray-800">{{ __('ui.student') }}</option>
            <option value="tutor" {{ request('rol') == 'tutor' ? 'selected' : '' }} class="text-gray-800">{{ __('ui.tutor') }}</option>
            <option value="administrador" {{ request('rol') == 'administrador' ? 'selected' : '' }} class="text-gray-800">{{ __('ui.administrator') }}</option>
        </select>
        <select name="estado" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
            <option value="" class="text-gray-800">{{ __('ui.all_states') }}</option>
            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }} class="text-gray-800">{{ __('ui.active') }}</option>
            <option value="suspendido" {{ request('estado') == 'suspendido' ? 'selected' : '' }} class="text-gray-800">{{ __('ui.suspended') }}</option>
        </select>
        <button type="submit" class="btn-white inline-flex items-center justify-center gap-2 py-2 font-bold">
            <x-icon name="search" class="w-4 h-4" /> {{ __('ui.filter') }}
        </button>
    </form>
</div>

<!-- Lista -->
<div class="glass rounded-2xl p-6 text-white">
    @if($usuarios->isEmpty())
        <p class="text-center py-8 text-white/70">{{ __('ui.no_users_found') }}</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/20">
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.user') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.email_label') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.role_label') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.status') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.registration') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($usuarios as $u)
                        <tr class="border-b border-white/10 hover:bg-white/5 transition">
                            <td class="p-3">
                                <div class="flex items-center gap-3">
                                    @if($u->foto)
                                        <img src="{{ Storage::url($u->foto) }}" class="w-9 h-9 rounded-full object-cover">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center font-bold text-sm">
                                            {{ substr($u->nombre, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium">{{ $u->nombre }} {{ $u->apellido }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 text-white/80">{{ $u->email }}</td>
                            <td class="p-3">
                                @php
                                    $coloresRol = [
                                        'administrador' => 'bg-purple-400/30 text-purple-100 border-purple-300/40',
                                        'tutor' => 'bg-green-400/30 text-green-100 border-green-300/40',
                                        'estudiante' => 'bg-blue-400/30 text-blue-100 border-blue-300/40',
                                    ];
                                    $colorRol = $coloresRol[$u->rol] ?? 'bg-gray-400/30';
                                @endphp
                                <span class="{{ $colorRol }} text-xs px-3 py-1 rounded-full border capitalize">{{ __('ui.role_labels.' . $u->rol) }}</span>
                            </td>
                            <td class="p-3">
                                @if($u->estado === 'activo')
                                    <span class="inline-flex items-center gap-1 bg-green-400/30 text-green-100 border border-green-300/40 text-xs px-3 py-1 rounded-full"><x-icon name="check-circle" class="w-3.5 h-3.5" /> {{ __('ui.active') }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-red-400/30 text-red-100 border border-red-300/40 text-xs px-3 py-1 rounded-full"><x-icon name="ban" class="w-3.5 h-3.5" /> {{ __('ui.suspended') }}</span>
                                @endif
                            </td>
                            <td class="p-3 text-xs text-white/60">{{ $u->created_at->format('d/m/Y') }}</td>
                            <td class="p-3">
                                <div class="flex gap-1 flex-wrap">
                                    <a href="{{ route('admin.usuarios.show', $u->id) }}" class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/15 p-1.5 rounded-lg" title="{{ __('ui.view') }}"><x-icon name="eye" class="w-4 h-4" /></a>
                                    <a href="{{ route('admin.usuarios.edit', $u->id) }}" class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/15 p-1.5 rounded-lg" title="{{ __('ui.edit') }}"><x-icon name="pencil" class="w-4 h-4" /></a>
                                    
                                    @if($u->id !== Auth::id() && $u->rol !== 'administrador')
                                        @if($u->estado === 'activo')
                                            <form method="POST" action="{{ route('admin.usuarios.suspender', $u->id) }}" class="inline" onsubmit="return confirm(@json(__('ui.suspend_user_confirm')))">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/15 p-1.5 rounded-lg" title="{{ __('ui.suspend') }}"><x-icon name="ban" class="w-4 h-4" /></button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.usuarios.activar', $u->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/15 p-1.5 rounded-lg" title="{{ __('ui.activate') }}"><x-icon name="check" class="w-4 h-4" /></button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.usuarios.destroy', $u->id) }}" class="inline" onsubmit="return confirm(@json(__('ui.delete_user_confirm')))">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/15 p-1.5 rounded-lg" title="{{ __('ui.delete') }}"><x-icon name="trash" class="w-4 h-4" /></button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $usuarios->links() }}</div>
    @endif
</div>
@endsection