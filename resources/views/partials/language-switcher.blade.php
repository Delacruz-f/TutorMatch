<form method="POST" action="{{ route('language.update') }}" class="flex items-center gap-1" aria-label="{{ __('ui.language') }}">
    @csrf
    @foreach (['es' => 'ES', 'en' => 'EN', 'zh_CN' => '中文'] as $locale => $label)
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
