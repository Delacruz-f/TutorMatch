@extends('layouts.app')

@section('title', 'Nuevo Usuario')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass rounded-2xl p-6 text-white mb-6">
        <h1 class="text-3xl font-bold">➕ Nuevo Usuario</h1>
        <p class="text-white/80">Crea un usuario manualmente</p>
    </div>

    <div class="glass rounded-2xl p-6 text-white">
        <form method="POST" action="{{ route('admin.usuarios.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Apellido</label>
                    <input type="text" name="apellido" value="{{ old('apellido') }}" required
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Contraseña</label>
                    <input type="password" name="password" required
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Confirmar</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Rol</label>
                    <select name="rol" required class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                        <option value="estudiante" class="text-gray-800">Estudiante</option>
                        <option value="tutor" class="text-gray-800">Tutor</option>
                        <option value="administrador" class="text-gray-800">Administrador</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}"
                           class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Biografía</label>
                <textarea name="biografia" rows="3" maxlength="1000"
                          class="w-full px-4 py-3 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">{{ old('biografia') }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-white text-brand-600 px-6 py-3 rounded-xl font-bold hover:bg-white/90 transition">
                    Crear Usuario
                </button>
                <a href="{{ route('admin.usuarios.index') }}" class="bg-white/20 hover:bg-white/30 px-6 py-3 rounded-xl font-bold transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection