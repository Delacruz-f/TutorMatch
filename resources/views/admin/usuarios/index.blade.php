@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="glass rounded-2xl p-6 text-white mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">Gestión de Usuarios</h1>
            <p class="text-white/80">Administra estudiantes, tutores y administradores</p>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="bg-white text-brand-600 px-5 py-2.5 rounded-xl font-bold hover:bg-white/90 transition">
            ➕ Nuevo Usuario
        </a>
    </div>
</div>

<!-- Estadísticas -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
    <div class="glass rounded-xl p-4 text-white text-center">
        <p class="text-xs text-white/70 uppercase">Total</p>
        <p class="text-2xl font-bold">{{ $stats['total'] }}</p>
    </div>
    <div class="glass rounded-xl p-4 text-white text-center">
        <p class="text-xs text-white/70 uppercase">Estudiantes</p>
        <p class="text-2xl font-bold">{{ $stats['estudiantes'] }}</p>
    </div>
    <div class="glass rounded-xl p-4 text-white text-center">
        <p class="text-xs text-white/70 uppercase">Tutores</p>
        <p class="text-2xl font-bold">{{ $stats['tutores'] }}</p>
    </div>
    <div class="glass rounded-xl p-4 text-white text-center">
        <p class="text-xs text-white/70 uppercase">Admins</p>
        <p class="text-2xl font-bold">{{ $stats['administradores'] }}</p>
    </div>
    <div class="glass rounded-xl p-4 text-white text-center">
        <p class="text-xs text-white/70 uppercase">Activos</p>
        <p class="text-2xl font-bold text-green-200">{{ $stats['activos'] }}</p>
    </div>
    <div class="glass rounded-xl p-4 text-white text-center">
        <p class="text-xs text-white/70 uppercase">Suspendidos</p>
        <p class="text-2xl font-bold text-red-200">{{ $stats['suspendidos'] }}</p>
    </div>
</div>

<!-- Filtros -->
<div class="glass rounded-2xl p-4 text-white mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar nombre o email..."
               class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white placeholder-white/60 focus:bg-white/25 focus:outline-none">
        <select name="rol" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
            <option value="" class="text-gray-800">Todos los roles</option>
            <option value="estudiante" {{ request('rol') == 'estudiante' ? 'selected' : '' }} class="text-gray-800">Estudiante</option>
            <option value="tutor" {{ request('rol') == 'tutor' ? 'selected' : '' }} class="text-gray-800">Tutor</option>
            <option value="administrador" {{ request('rol') == 'administrador' ? 'selected' : '' }} class="text-gray-800">Administrador</option>
        </select>
        <select name="estado" class="px-4 py-2 rounded-xl bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none">
            <option value="" class="text-gray-800">Todos los estados</option>
            <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }} class="text-gray-800">Activo</option>
            <option value="suspendido" {{ request('estado') == 'suspendido' ? 'selected' : '' }} class="text-gray-800">Suspendido</option>
        </select>
        <button type="submit" class="bg-white text-brand-600 py-2 rounded-xl font-bold hover:bg-white/90 transition">
            🔍 Filtrar
        </button>
    </form>
</div>

<!-- Lista -->
<div class="glass rounded-2xl p-6 text-white">
    @if($usuarios->isEmpty())
        <p class="text-center py-8 text-white/70">No se encontraron usuarios.</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-white/20">
                        <th class="text-left p-3 font-semibold text-white/80">Usuario</th>
                        <th class="text-left p-3 font-semibold text-white/80">Email</th>
                        <th class="text-left p-3 font-semibold text-white/80">Rol</th>
                        <th class="text-left p-3 font-semibold text-white/80">Estado</th>
                        <th class="text-left p-3 font-semibold text-white/80">Registro</th>
                        <th class="text-left p-3 font-semibold text-white/80">Acciones</th>
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
                                <span class="{{ $colorRol }} text-xs px-3 py-1 rounded-full border capitalize">{{ $u->rol }}</span>
                            </td>
                            <td class="p-3">
                                @if($u->estado === 'activo')
                                    <span class="bg-green-400/30 text-green-100 border border-green-300/40 text-xs px-3 py-1 rounded-full">✅ Activo</span>
                                @else
                                    <span class="bg-red-400/30 text-red-100 border border-red-300/40 text-xs px-3 py-1 rounded-full">🚫 Suspendido</span>
                                @endif
                            </td>
                            <td class="p-3 text-xs text-white/60">{{ $u->created_at->format('d/m/Y') }}</td>
                            <td class="p-3">
                                <div class="flex gap-1 flex-wrap">
                                    <a href="{{ route('admin.usuarios.show', $u->id) }}" class="bg-blue-500/40 hover:bg-blue-500/60 px-2 py-1 rounded text-xs" title="Ver">👁️</a>
                                    <a href="{{ route('admin.usuarios.edit', $u->id) }}" class="bg-yellow-500/40 hover:bg-yellow-500/60 px-2 py-1 rounded text-xs" title="Editar">✏️</a>
                                    
                                    @if($u->id !== Auth::id() && $u->rol !== 'administrador')
                                        @if($u->estado === 'activo')
                                            <form method="POST" action="{{ route('admin.usuarios.suspender', $u->id) }}" class="inline" onsubmit="return confirm('¿Suspender a este usuario?')">
                                                @csrf
                                                <button type="submit" class="bg-orange-500/40 hover:bg-orange-500/60 px-2 py-1 rounded text-xs" title="Suspender">🚫</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.usuarios.activar', $u->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-green-500/40 hover:bg-green-500/60 px-2 py-1 rounded text-xs" title="Activar">✅</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.usuarios.destroy', $u->id) }}" class="inline" onsubmit="return confirm('¿Eliminar permanentemente a este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500/40 hover:bg-red-500/60 px-2 py-1 rounded text-xs" title="Eliminar">🗑️</button>
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