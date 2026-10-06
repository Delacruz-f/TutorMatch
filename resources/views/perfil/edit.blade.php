@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
<div class="max-w-3xl mx-auto">

    <!-- Header -->
    <div class="glass rounded-2xl p-6 text-white mb-6">
        <h1 class="text-3xl font-bold">Mi Perfil</h1>
        <p class="text-white/80">Actualiza tu información personal</p>
    </div>

    <!-- Datos personales -->
    <div class="glass rounded-2xl p-6 text-white mb-6">
        <h2 class="text-xl font-bold mb-4">👤 Datos personales</h2>

        <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            <!-- Foto actual -->
            <div class="flex items-center gap-4 mb-4">
                @if(Auth::user()->foto)
                    <img src="{{ Storage::url(Auth::user()->foto) }}" alt="Foto" class="w-20 h-20 rounded-full object-cover border-2 border-white/30">
                @else
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-brand-400 to-purple-500 flex items-center justify-center text-3xl font-bold">
                        {{ substr(Auth::user()->nombre, 0, 1) }}
                    </div>
                @endif
                <div class="flex-1">
                    <label class="block text-sm font-medium mb-1">Cambiar foto</label>
                    <input type="file" name="foto" accept="image/*"
                           class="w-full text-sm text-white/80 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-white/20 file:text-white hover:file:bg-white/30">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', Auth::user()->nombre) }}" required
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Apellido</label>
                    <input type="text" name="apellido" value="{{ old('apellido', Auth::user()->apellido) }}" required
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Correo electrónico</label>
                <input type="email" value="{{ Auth::user()->email }}" disabled
                       class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/20 text-white/60 cursor-not-allowed">
                <p class="text-xs text-white/60 mt-1">El correo no se puede cambiar.</p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono', Auth::user()->telefono) }}"
                       placeholder="+51 999 999 999"
                       class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Biografía</label>
                <textarea name="biografia" rows="4" maxlength="1000"
                          placeholder="Cuéntanos un poco sobre ti..."
                          class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">{{ old('biografia', Auth::user()->biografia) }}</textarea>
            </div>

            <button type="submit" class="bg-white text-brand-600 px-6 py-3 rounded-xl font-bold hover:bg-white/90 transition">
                Guardar cambios
            </button>
        </form>
    </div>

    <!-- Cambiar contraseña -->
    <div class="glass rounded-2xl p-6 text-white">
        <h2 class="text-xl font-bold mb-4">🔒 Cambiar contraseña</h2>

        <form method="POST" action="{{ route('perfil.password') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium mb-2">Contraseña actual</label>
                <input type="password" name="current_password" required
                       class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Nueva contraseña</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Confirmar</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
            </div>

            <button type="submit" class="bg-white text-brand-600 px-6 py-3 rounded-xl font-bold hover:bg-white/90 transition">
                Cambiar contraseña
            </button>
        </form>
    </div>
</div>
@endsection