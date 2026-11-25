@extends('layouts.app')

@section('title', __('ui.manage_subjects'))

@section('content')
<div class="glass rounded-2xl p-6 text-white mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">{{ __('ui.manage_subjects') }}</h1>
            <p class="text-white/80">{{ __('ui.categories_available') }}</p>
        </div>
        <a href="{{ route('admin.materias.create') }}" class="btn-white inline-flex items-center gap-2 px-5 py-2.5 font-bold">
            <x-icon name="plus" class="w-4 h-4" /> {{ __('ui.new_subject') }}
        </a>
    </div>
</div>

<div class="glass rounded-2xl p-6 text-white">
    @if($materias->isEmpty())
        <p class="text-center py-8 text-white/70">{{ __('ui.no_subjects_registered') }}</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/20">
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.icon') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.subject') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.slug') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.tutors') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.status') }}</th>
                        <th class="text-left p-3 font-semibold text-white/80">{{ __('ui.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($materias as $m)
                        <tr class="border-b border-white/10 hover:bg-white/5 transition">
                            <td class="p-3">
                                @if($m->icono)
                                    <img src="{{ Storage::url($m->icono) }}" class="w-10 h-10 rounded-lg object-cover">
                                @else
                                    <div class="icon-chip"><x-icon name="book-open" /></div>
                                @endif
                            </td>
                            <td class="p-3">
                                <p class="font-medium">{{ $m->nombre }}</p>
                                <p class="text-xs text-white/60">{{ Str::limit($m->descripcion, 60) }}</p>
                            </td>
                            <td class="p-3 text-white/60 text-xs font-mono">{{ $m->slug }}</td>
                            <td class="p-3"><span class="bg-white/20 px-2 py-1 rounded-full text-xs">{{ $m->tutores_count }}</span></td>
                            <td class="p-3">
                                @if($m->activo)
                                    <span class="inline-flex items-center gap-1 bg-green-400/30 text-green-100 border border-green-300/40 text-xs px-3 py-1 rounded-full"><x-icon name="check-circle" class="w-3.5 h-3.5" /> {{ __('ui.active_plan') }}</span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-gray-400/30 text-gray-100 border border-gray-300/40 text-xs px-3 py-1 rounded-full"><x-icon name="pause" class="w-3.5 h-3.5" /> {{ __('ui.inactive_feminine') }}</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="flex gap-1 flex-wrap">
                                    <form method="POST" action="{{ route('admin.materias.toggle', $m->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/15 p-1.5 rounded-lg" title="{{ __('ui.change_status') }}">
                                            <x-icon :name="$m->activo ? 'pause' : 'play'" class="w-4 h-4" />
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.materias.edit', $m->id) }}" class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/15 p-1.5 rounded-lg" title="{{ __('ui.edit') }}"><x-icon name="pencil" class="w-4 h-4" /></a>
                                    <form method="POST" action="{{ route('admin.materias.destroy', $m->id) }}" class="inline" onsubmit="return confirm(@json(__('ui.delete_subject_confirm')))">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/15 p-1.5 rounded-lg" title="{{ __('ui.delete') }}"><x-icon name="trash" class="w-4 h-4" /></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $materias->links() }}</div>
    @endif
</div>
@endsection