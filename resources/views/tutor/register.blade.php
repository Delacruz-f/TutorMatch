@extends('layouts.app')

@section('title', 'Registro de Tutor')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md" x-data="tutorForm()">
    <h2 class="text-3xl font-bold text-center mb-6 text-blue-600">Completa tu Perfil de Tutor</h2>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tutor.register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Datos Académicos -->
        <h3 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">1. Datos Académicos</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Universidad *</label>
                <input type="text" name="universidad" value="{{ old('universidad') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Carrera *</label>
                <input type="text" name="carrera" value="{{ old('carrera') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <!-- Documentos -->
        <h3 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">2. Documentos de Verificación</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Número de DNI *</label>
                <input type="text" name="dni_numero" value="{{ old('dni_numero') }}" required maxlength="15"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Foto del DNI * (JPG/PNG, máx 2MB)</label>
                <input type="file" name="dni_foto" accept="image/*" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">CV en PDF * (máx 5MB)</label>
                <input type="file" name="cv_archivo" accept="application/pdf" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
            </div>
        </div>

        <!-- Foto de perfil -->
        <h3 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">3. Foto de Perfil</h3>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Foto de perfil (opcional, JPG/PNG)</label>
            <input type="file" name="foto_perfil" accept="image/*"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md">
        </div>

        <!-- Materias y Precio -->
        <h3 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">4. Materias y Precio</h3>
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Materias que dominas *</label>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                @foreach($materias as $materia)
                    <label class="flex items-center space-x-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                        <input type="checkbox" name="materias[]" value="{{ $materia->id }}"
                               {{ in_array($materia->id, old('materias', [])) ? 'checked' : '' }}
                               class="rounded text-blue-600">
                        <span>{{ $materia->nombre }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2">Precio por hora (S/) *</label>
            <input type="number" name="precio_hora" value="{{ old('precio_hora') }}" required min="5" max="500" step="0.01"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">
        </div>

        <!-- Disponibilidad -->
        <h3 class="text-xl font-semibold mb-4 text-gray-700 border-b pb-2">5. Disponibilidad Horaria</h3>
        <div class="space-y-3 mb-6">
            <template x-for="(slot, index) in slots" :key="index">
                <div class="flex flex-wrap gap-2 items-end p-3 bg-gray-50 rounded">
                    <div class="flex-1 min-w-[150px]">
                        <label class="block text-xs text-gray-600 mb-1">Día</label>
                        <select :name="`disponibilidad[${index}][dia_semana]`" x-model="slot.dia" required
                                class="w-full px-2 py-1 border rounded text-sm">
                            <option value="">Seleccionar</option>
                            <option value="1">Lunes</option>
                            <option value="2">Martes</option>
                            <option value="3">Miércoles</option>
                            <option value="4">Jueves</option>
                            <option value="5">Viernes</option>
                            <option value="6">Sábado</option>
                            <option value="7">Domingo</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Desde</label>
                        <input type="time" :name="`disponibilidad[${index}][hora_inicio]`" x-model="slot.inicio" required
                               class="px-2 py-1 border rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Hasta</label>
                        <input type="time" :name="`disponibilidad[${index}][hora_fin]`" x-model="slot.fin" required
                               class="px-2 py-1 border rounded text-sm">
                    </div>
                    <button type="button" @click="removeSlot(index)"
                            class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600"
                            x-show="slots.length > 1">
                        Eliminar
                    </button>
                </div>
            </template>
            <button type="button" @click="addSlot()"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-sm">
                + Agregar horario
            </button>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 transition font-semibold">
            Enviar Solicitud de Tutor
        </button>
    </form>
</div>

<script>
function tutorForm() {
    return {
        slots: [{ dia: '', inicio: '', fin: '' }],
        addSlot() {
            this.slots.push({ dia: '', inicio: '', fin: '' });
        },
        removeSlot(index) {
            if (this.slots.length > 1) {
                this.slots.splice(index, 1);
            }
        }
    }
}
</script>
@endsection
