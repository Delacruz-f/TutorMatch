@php($languages = ['es' => 'ES', 'en' => 'EN', 'zh_CN' => '中文'])

<form method="POST" action="{{ route('language.update') }}" class="hidden sm:flex items-center gap-1" aria-label="{{ __('ui.language') }}">
    @csrf
    @foreach ($languages as $locale => $label)
        <button
            type="submit"
            name="locale"
            value="{{ $locale }}"
            lang="{{ str_replace('_', '-', $locale) }}"
            aria-pressed="{{ app()->getLocale() === $locale ? 'true' : 'false' }}"
            class="rounded-lg px-2.5 py-1.5 text-xs font-semibold transition {{ app()->getLocale() === $locale ? 'bg-white/25 text-white' : 'text-white/75 hover:bg-white/10 hover:text-white' }}">
            {{ $label }}
        </button>
    @endforeach
</form>

<form method="POST" action="{{ route('language.update') }}" class="relative sm:hidden" aria-label="{{ __('ui.language') }}">
    @csrf
    <select
        name="locale"
        aria-label="{{ __('ui.language') }}"
        @change="$el.form.submit()"
        class="appearance-none rounded-lg border border-white/20 bg-white/10 py-2 pl-3 pr-9 text-sm font-semibold text-white focus:outline-none focus:ring-2 focus:ring-white/40">
        @foreach ($languages as $locale => $label)
            <option value="{{ $locale }}" lang="{{ str_replace('_', '-', $locale) }}" @selected(app()->getLocale() === $locale) class="bg-slate-900 text-white">
                {{ $label }}
            </option>
        @endforeach
    </select>
    <x-icon name="chevron-down" class="pointer-events-none absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2" />
</form>
