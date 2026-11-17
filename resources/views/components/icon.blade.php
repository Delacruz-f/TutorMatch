@props([
    'name' => 'circle',
    'size' => null,
    'stroke' => 1.75,
])

@php
    /*
    |--------------------------------------------------------------------------
    | Iconos SVG (estilo Lucide / línea, 24x24, trazo uniforme)
    |--------------------------------------------------------------------------
    | Todos heredan el color con currentColor para que el color sea único y
    | consistente en toda la aplicación (--tm-icon). Se pueden usar con:
    |   <x-icon name="users" />
    |   <x-icon name="users" size="20" class="text-lg" />
    | Para iconos rellenos (estrellas de calificación) añadir class="icon-filled".
    */

    $paths = [
        // Navegación / secciones
        'home' => '<path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V20a1 1 0 0 0 1 1h4v-6h4v6h4a1 1 0 0 0 1-1V9.5"/>',
        'search' => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>',
        'layout-dashboard' => '<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>',
        'graduation-cap' => '<path d="M22 9.5 12 5 2 9.5l10 4.5 10-4.5Z"/><path d="M6 11.6V16c0 1.5 2.7 3 6 3s6-1.5 6-3v-4.4"/><path d="M22 9.5V15"/>',
        'users' => '<path d="M16 21v-1.5a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4V21"/><circle cx="9" cy="7" r="3.5"/><path d="M22 21v-1.5a4 4 0 0 0-3-3.87"/><path d="M16.5 3.6a3.5 3.5 0 0 1 0 6.8"/>',
        'user' => '<circle cx="12" cy="8" r="4"/><path d="M4.5 21a7.5 7.5 0 0 1 15 0"/>',
        'book-open' => '<path d="M12 6.5C10.5 5 8.6 4.3 5 4.3c-.6 0-1 .4-1 1v12.2c0 .6.4 1 1 1 3.6 0 5.5.7 7 2.2 1.5-1.5 3.4-2.2 7-2.2.6 0 1-.4 1-1V5.3c0-.6-.4-1-1-1-3.6 0-5.5.7-7 2.2Z"/><path d="M12 6.5v14.2"/>',
        'credit-card' => '<rect x="2.5" y="5" width="19" height="14" rx="2.5"/><path d="M2.5 10h19"/><path d="M6.5 15h3"/>',
        'wallet' => '<path d="M20 7.5V6a2 2 0 0 0-2-2H5.5A2.5 2.5 0 0 0 3 6.5v11A2.5 2.5 0 0 0 5.5 20H19a1 1 0 0 0 1-1V7.5Z"/><path d="M3 6.5A2.5 2.5 0 0 1 5.5 4"/><path d="M21 11h-4.5a2.25 2.25 0 0 0 0 4.5H21"/>',
        'star' => '<path d="m12 3.6 2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.7-5.2 2.7 1-5.8L3.5 9.8l5.9-.9Z"/>',
        'receipt' => '<path d="M5 21V4.5A1.5 1.5 0 0 1 6.5 3h11A1.5 1.5 0 0 1 19 4.5V21l-3-1.8-2 1.8-2-1.8-2 1.8-2-1.8L5 21Z"/><path d="M9 8h6"/><path d="M9 12h6"/>',
        'trending-up' => '<path d="m3 17 6-6 4 4 8-8"/><path d="M15 7h6v6"/>',
        'chart' => '<path d="M4 20V10"/><path d="M10 20V4"/><path d="M16 20v-7"/><path d="M22 20H2"/>',
        'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7.5V12l3 1.8"/>',
        'bell' => '<path d="M18 8.5a6 6 0 1 0-12 0c0 4.2-1.5 5.5-1.5 5.5h15S18 12.7 18 8.5Z"/><path d="M10.3 18a2 2 0 0 0 3.4 0"/>',
        'key' => '<circle cx="8" cy="15.5" r="4"/><path d="m11 12.5 8-8"/><path d="m16.5 7 2.5 2.5"/><path d="m14 9.5 2 2"/>',
        'sparkles' => '<path d="M12 3.5 13.4 7 17 8.4 13.4 9.8 12 13.3 10.6 9.8 7 8.4 10.6 7 12 3.5Z"/><path d="M18.5 14.5 19.3 16.4 21.2 17.2 19.3 18 18.5 19.9 17.7 18 15.8 17.2 17.7 16.4 18.5 14.5Z"/><path d="M5.5 14.5 6.2 16 7.7 16.7 6.2 17.4 5.5 18.9 4.8 17.4 3.3 16.7 4.8 16 5.5 14.5Z"/>',
        'log-out' => '<path d="M9 21H5.5A1.5 1.5 0 0 1 4 19.5v-15A1.5 1.5 0 0 1 5.5 3H9"/><path d="m15 8 4 4-4 4"/><path d="M19 12H9"/>',
        'calendar' => '<rect x="3" y="5" width="18" height="16" rx="2.5"/><path d="M8 3v4"/><path d="M16 3v4"/><path d="M3 10h18"/>',
        'calendar-days' => '<rect x="3" y="5" width="18" height="16" rx="2.5"/><path d="M8 3v4"/><path d="M16 3v4"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 17.5h.01"/><path d="M12 17.5h.01"/>',
        'video' => '<rect x="2.5" y="6" width="13" height="12" rx="2.5"/><path d="m15.5 10.5 5-3v9l-5-3Z"/>',
        'bulb' => '<path d="M9 18h6"/><path d="M10 21h4"/><path d="M12 3a6 6 0 0 0-3.5 10.9c.6.5 1 1.2 1 2h5c0-.8.4-1.5 1-2A6 6 0 0 0 12 3Z"/>',
        'lock' => '<rect x="4.5" y="10.5" width="15" height="10.5" rx="2.5"/><path d="M8 10.5V7.5a4 4 0 0 1 8 0v3"/>',
        'shield' => '<path d="M12 3 5 5.7v5.6c0 4.3 2.9 7.7 7 9.7 4.1-2 7-5.4 7-9.7V5.7L12 3Z"/><path d="m9.2 11.8 2 2 3.6-3.6"/>',
        'mail' => '<rect x="2.5" y="5" width="19" height="14" rx="2.5"/><path d="m3.5 7 7.4 5.2a2 2 0 0 0 2.2 0L20.5 7"/>',
        'plus' => '<path d="M12 5v14"/><path d="M5 12h14"/>',
        'pencil' => '<path d="M17 3.5 20.5 7 8.5 19H5v-3.5L17 3.5Z"/><path d="m15 5.5 3.5 3.5"/>',
        'trash' => '<path d="M4 7h16"/><path d="M9 7V5.5A1.5 1.5 0 0 1 10.5 4h3A1.5 1.5 0 0 1 15 5.5V7"/><path d="M6 7l1 12.2A1.8 1.8 0 0 0 8.8 21h6.4a1.8 1.8 0 0 0 1.8-1.8L18 7"/><path d="M10.5 11v6"/><path d="M13.5 11v6"/>',
        'eye' => '<path d="M2.5 12S6 5.5 12 5.5 21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z"/><circle cx="12" cy="12" r="3"/>',
        'ban' => '<circle cx="12" cy="12" r="9"/><path d="m5.6 5.6 12.8 12.8"/>',
        'check' => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
        'check-circle' => '<circle cx="12" cy="12" r="9"/><path d="m8.5 12.2 2.4 2.4 4.6-4.8"/>',
        'x' => '<path d="M6 6l12 12"/><path d="M18 6 6 18"/>',
        'x-circle' => '<circle cx="12" cy="12" r="9"/><path d="m9 9 6 6"/><path d="m15 9-6 6"/>',
        'alert-triangle' => '<path d="M10.3 4.2 2.6 18a2 2 0 0 0 1.8 3h15.2a2 2 0 0 0 1.8-3L13.7 4.2a2 2 0 0 0-3.4 0Z"/><path d="M12 9.5V14"/><path d="M12 17.5h.01"/>',
        'alert-circle' => '<circle cx="12" cy="12" r="9"/><path d="M12 7.5V13"/><path d="M12 16.5h.01"/>',
        'info' => '<circle cx="12" cy="12" r="9"/><path d="M12 11v5.5"/><path d="M12 7.8h.01"/>',
        'file-text' => '<path d="M14 3H7.5A2.5 2.5 0 0 0 5 5.5v13A2.5 2.5 0 0 0 7.5 21h9a2.5 2.5 0 0 0 2.5-2.5V8l-5-5Z"/><path d="M14 3v5h5"/><path d="M8.5 13h7"/><path d="M8.5 16.5h4.5"/>',
        'download' => '<path d="M12 4v11"/><path d="m7.5 10.5 4.5 4.5 4.5-4.5"/><path d="M5 19.5h14"/>',
        'trophy' => '<path d="M7 4h10v5a5 5 0 0 1-10 0V4Z"/><path d="M7 5.5H4.5V7a3 3 0 0 0 3 3"/><path d="M17 5.5h2.5V7a3 3 0 0 1-3 3"/><path d="M12 14v3.5"/><path d="M8.5 21h7"/><path d="M9.5 17.5h5l.5 3.5h-6l.5-3.5Z"/>',
        'crown' => '<path d="M3 8.5 6.5 11 12 5l5.5 6 3.5-2.5-1.5 10h-14L3 8.5Z"/><path d="M5 21h14"/>',
        'badge-check' => '<path d="M12 2.8 14.4 5l3.2-.4.4 3.2 2.2 2.4-2.2 2.3-.4 3.2-3.2-.4L12 17.5 9.6 15.3l-3.2.4L6 12.5 3.8 10.2 6 7.8l.4-3.2L9.6 5 12 2.8Z"/><path d="m9.3 10.2 2 2 3.4-3.6"/>',
        'message-circle' => '<path d="M20.5 11.5a8.5 8.5 0 0 1-12.3 7.6L3.5 20.5l1.4-4.5A8.5 8.5 0 1 1 20.5 11.5Z"/>',
        'smartphone' => '<rect x="6" y="2.5" width="12" height="19" rx="2.5"/><path d="M11 18.5h2"/>',
        'pause' => '<path d="M9 5v14"/><path d="M15 5v14"/>',
        'play' => '<path d="M7 4.5 19 12 7 19.5v-15Z"/>',
        'flag' => '<path d="M5 21V4"/><path d="M5 4.5h11l-1.8 4 1.8 4H5"/>',
        'arrow-right' => '<path d="M4.5 12h14"/><path d="m13 6.5 5.5 5.5L13 17.5"/>',
        'arrow-left' => '<path d="M19.5 12h-14"/><path d="m11 17.5L5.5 12 11 6.5"/>',
        'menu' => '<path d="M4 7h16"/><path d="M4 12h16"/><path d="M4 17h16"/>',
        'chevron-down' => '<path d="m6 9.5 6 6 6-6"/>',
        'chevron-left' => '<path d="m14.5 5.5-6 6.5 6 6.5"/>',
        'chevron-right' => '<path d="m9.5 5.5 6 6.5-6 6.5"/>',
        'settings' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.9 2.9l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.6 1.7 1.7 0 0 0-1.8.4l-.1.1a2 2 0 1 1-2.8-2.9l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.6-1.1 1.7 1.7 0 0 0-.4-1.8l-.1-.2a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.2-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1Z"/>',
        'user-check' => '<path d="M15 21v-1.5a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4V21"/><circle cx="8.5" cy="7" r="3.5"/><path d="m16 11 2 2 4-4"/>',
        'camera' => '<path d="M4.5 8h2.2l1.4-2.2a1.5 1.5 0 0 1 1.3-.8h5.2a1.5 1.5 0 0 1 1.3.8L17.3 8h2.2A2.5 2.5 0 0 1 22 10.5v7A2.5 2.5 0 0 1 19.5 20h-15A2.5 2.5 0 0 1 2 17.5v-7A2.5 2.5 0 0 1 4.5 8Z"/><circle cx="12" cy="14" r="3.2"/>',
        'circle' => '<circle cx="12" cy="12" r="9"/>',
    ];

    $svg = $paths[$name] ?? $paths['circle'];
    $dimension = $size !== null ? 'width="' . e($size) . '" height="' . e($size) . '"' : 'width="1.25em" height="1.25em"';
@endphp

<svg
    {{ $attributes->merge(['class' => 'tm-icon']) }}
    xmlns="http://www.w3.org/2000/svg"
    {!! $dimension !!}
    viewBox="0 0 24 24"
    fill="none"
    stroke="currentColor"
    stroke-width="{{ $stroke }}"
    stroke-linecap="round"
    stroke-linejoin="round"
    aria-hidden="true"
    focusable="false">{!! $svg !!}</svg>
