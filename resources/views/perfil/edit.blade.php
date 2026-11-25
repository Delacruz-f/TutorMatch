@extends('layouts.app')

@section('title', __('ui.my_profile_page'))

@section('content')
<div class="max-w-3xl mx-auto">

    <!-- Header -->
    <div class="glass rounded-2xl p-6 text-white mb-6">
        <h1 class="text-3xl font-bold">{{ __('ui.my_profile_page') }}</h1>
        <p class="text-white/80">{{ __('ui.update_personal_info') }}</p>
    </div>

    <!-- Datos personales -->
    <div class="glass rounded-2xl p-6 text-white mb-6">
        <h2 class="text-xl font-bold mb-4 flex items-center"><x-icon name="user" class="w-5 h-5" /> {{ __('ui.personal_details') }}</h2>

        <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Foto actual -->
            <div class="flex items-center gap-4 mb-4">
                @if(Auth::user()->foto)
                    <img src="{{ Storage::url(Auth::user()->foto) }}" alt="{{ __('ui.photo_alt') }}" class="w-20 h-20 rounded-full object-cover border-2 border-white/30">
                @else
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center text-3xl font-bold">
                        {{ substr(Auth::user()->nombre, 0, 1) }}
                    </div>
                @endif
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-1">{{ __('ui.change_photo') }}</label>
                    <input type="file" name="foto" accept="image/*"
                           class="w-full text-sm text-white/80 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-white/20 file:text-white hover:file:bg-white/30">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('ui.first_name') }}</label>
                    <input type="text" name="nombre" value="{{ old('nombre', Auth::user()->nombre) }}" required
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('ui.last_name') }}</label>
                    <input type="text" name="apellido" value="{{ old('apellido', Auth::user()->apellido) }}" required
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">{{ __('ui.email') }}</label>
                <input type="email" value="{{ Auth::user()->email }}" disabled
                       class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white/60 cursor-not-allowed">
                <p class="text-xs text-white/60 mt-1">{{ __('ui.email_cannot_change') }}</p>
                @if(Auth::user()->hasVerifiedEmail())
                    <p class="mt-2 text-sm font-semibold text-green-300" role="status">
                        Correo verificado
                    </p>
                @else
                    <div class="mt-3 flex flex-col items-start gap-2">
                        <p class="text-sm text-amber-200" role="status">
                            Tu correo todavía no está verificado.
                        </p>
                        <a href="{{ route('verification.notice') }}"
                           class="inline-flex items-center rounded-xl bg-[#8b5e3c] px-4 py-2 font-semibold text-white transition hover:bg-[#70482d]">
                            Verificar correo
                        </a>
                    </div>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">{{ __('ui.phone') }}</label>
                <input type="text" name="telefono" value="{{ old('telefono', Auth::user()->telefono) }}"
                       placeholder="+51 999 999 999"
                       class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">{{ __('ui.biography') }}</label>
                <textarea name="biografia" rows="4" maxlength="1000"
                          placeholder="{{ __('ui.tell_about_yourself') }}"
                          class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">{{ old('biografia', Auth::user()->biografia) }}</textarea>
            </div>

            <button type="submit" class="bg-white text-brand-600 px-6 py-3 rounded-xl font-bold hover:bg-white/90 transition">
                Guardar cambios
            </button>
        </form>
    </div>

    <!-- Cambiar contraseña -->
    <div class="glass rounded-2xl p-6 text-white">
        <h2 class="text-xl font-bold mb-4 flex items-center"><x-icon name="lock" class="w-5 h-5" /> {{ __('ui.change_password') }}</h2>

        <form method="POST" action="{{ route('perfil.password') }}" class="space-y-4">
            @csrf
            @method('PUT')

            @if(Auth::user()->google_id)
                <p class="text-sm text-white/80">
                    Iniciaste sesión con Google. Para establecer una contraseña, solo necesitas ingresar una nueva.
                </p>
            @else
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('ui.current_password') }}</label>
                    <input type="password" name="current_password" required
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('ui.new_password') }}</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">{{ __('ui.password_confirm') }}</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
            </div>

            <button type="submit" class="bg-white text-brand-600 px-6 py-3 rounded-xl font-bold hover:bg-white/90 transition">
                Cambiar contraseña
            </button>
        </form>
    </div>

    <div class="glass rounded-2xl p-6 text-white mt-6">
        <h2 class="text-xl font-bold mb-2">{{ __('ui.delete_account') }}</h2>
        <p class="text-sm text-white/80 mb-4">
            Esta acción es permanente y cerrará tu sesión.
        </p>

        <form method="POST" action="{{ route('perfil.destroy') }}"
              onsubmit="return confirm(@json(__('ui.delete_account_confirm')));">
            @csrf
            @method('DELETE')

            <button type="submit" class="bg-red-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-red-700 transition">
                Eliminar mi cuenta
            </button>
        </form>
    </div>
</div>
@endsection