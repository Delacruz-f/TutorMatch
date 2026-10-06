@extends('layouts.app')

@section('title', 'Mi Disponibilidad')

@section('content')
<div class="max-w-3xl mx-auto" x-data="disponibilidadForm()">
    <div class="glass rounded-2xl p-6 text-white mb-6">
        <h1 class="text-3xl font-bold mb-1">🕐 Mi Disponibilidad</h1>
        <p class="text-white/80">Configura los días y horarios en los que puedes dar clases</p>
    </div>

    @if(session('success'))
        <div class="glass text-white px-4 py-3 rounded-xl border-l-4 border-green-400 mb-6">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="glass text-white px-4 py-3 rounded-xl border-l-4 border-red-400 mb-6">
            @foreach($errors->all() as $e)
                <p>⚠️ {{ $e }}</p>
            @endforeach
        </div>
    @endif

    <div class="glass rounded-2xl p-6 text-white">
        <form method="POST" action="{{ route('tutor.disponibilidad.store') }}">
            @csrf

           <template x-for="(slot, index) in slots" :key="index">
    <div class="bg-white/10 rounded-xl p-4 mb-3 border border-white/20">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-3 items-end">
            
            {{-- Día --}}
            <div class="lg:col-span-3">
                <label class="block text-xs text-white/70 mb-1 font-medium">📅 Día</label>
                <select :name="`disponibilidad[${index}][dia_semana]`" x-model="slot.dia" required
                        class="w-full px-3 py-2 rounded-lg bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none text-sm">
                    <option value="" class="text-gray-800">Seleccionar</option>
                    <option value="1" class="text-gray-800">Lunes</option>
                    <option value="2" class="text-gray-800">Martes</option>
                    <option value="3" class="text-gray-800">Miércoles</option>
                    <option value="4" class="text-gray-800">Jueves</option>
                    <option value="5" class="text-gray-800">Viernes</option>
                    <option value="6" class="text-gray-800">Sábado</option>
                    <option value="7" class="text-gray-800">Domingo</option>
                </select>
            </div>

            {{-- Hora inicio --}}
            <div class="lg:col-span-4">
                <label class="block text-xs text-white/70 mb-1 font-medium">🕐 Desde</label>
                <div class="grid grid-cols-3 gap-1">
                    {{-- Hora --}}
                    <select x-model="slot.inicio_hora" @change="updateHoraInicio(index)" 
                            class="px-2 py-2 rounded-lg bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none text-sm">
                        <option value="01" class="text-gray-800">01</option>
                        <option value="02" class="text-gray-800">02</option>
                        <option value="03" class="text-gray-800">03</option>
                        <option value="04" class="text-gray-800">04</option>
                        <option value="05" class="text-gray-800">05</option>
                        <option value="06" class="text-gray-800">06</option>
                        <option value="07" class="text-gray-800">07</option>
                        <option value="08" class="text-gray-800">08</option>
                        <option value="09" class="text-gray-800">09</option>
                        <option value="10" class="text-gray-800">10</option>
                        <option value="11" class="text-gray-800">11</option>
                        <option value="12" class="text-gray-800">12</option>
                    </select>
                    {{-- Minutos --}}
                    <select x-model="slot.inicio_min" @change="updateHoraInicio(index)"
                            class="px-2 py-2 rounded-lg bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none text-sm">
                        <option value="00" class="text-gray-800">00</option>
                        <option value="15" class="text-gray-800">15</option>
                        <option value="30" class="text-gray-800">30</option>
                        <option value="45" class="text-gray-800">45</option>
                    </select>
                    {{-- AM/PM --}}
                    <select x-model="slot.inicio_ampm" @change="updateHoraInicio(index)"
                            class="px-2 py-2 rounded-lg bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none text-sm">
                        <option value="AM" class="text-gray-800">AM</option>
                        <option value="PM" class="text-gray-800">PM</option>
                    </select>
                </div>
                {{-- Input oculto que envía la hora final --}}
                <input type="hidden" :name="`disponibilidad[${index}][hora_inicio]`" :value="slot.inicio">
                <p class="text-xs text-white/60 mt-1">
                    Seleccionado: <strong x-text="formatHora(slot.inicio)"></strong>
                </p>
            </div>

            {{-- Hora fin --}}
            <div class="lg:col-span-4">
                <label class="block text-xs text-white/70 mb-1 font-medium">🕐 Hasta</label>
                <div class="grid grid-cols-3 gap-1">
                    {{-- Hora --}}
                    <select x-model="slot.fin_hora" @change="updateHoraFin(index)"
                            class="px-2 py-2 rounded-lg bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none text-sm">
                        <option value="01" class="text-gray-800">01</option>
                        <option value="02" class="text-gray-800">02</option>
                        <option value="03" class="text-gray-800">03</option>
                        <option value="04" class="text-gray-800">04</option>
                        <option value="05" class="text-gray-800">05</option>
                        <option value="06" class="text-gray-800">06</option>
                        <option value="07" class="text-gray-800">07</option>
                        <option value="08" class="text-gray-800">08</option>
                        <option value="09" class="text-gray-800">09</option>
                        <option value="10" class="text-gray-800">10</option>
                        <option value="11" class="text-gray-800">11</option>
                        <option value="12" class="text-gray-800">12</option>
                    </select>
                    {{-- Minutos --}}
                    <select x-model="slot.fin_min" @change="updateHoraFin(index)"
                            class="px-2 py-2 rounded-lg bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none text-sm">
                        <option value="00" class="text-gray-800">00</option>
                        <option value="15" class="text-gray-800">15</option>
                        <option value="30" class="text-gray-800">30</option>
                        <option value="45" class="text-gray-800">45</option>
                    </select>
                    {{-- AM/PM --}}
                    <select x-model="slot.fin_ampm" @change="updateHoraFin(index)"
                            class="px-2 py-2 rounded-lg bg-white/15 border border-white/30 text-white focus:bg-white/25 focus:outline-none text-sm">
                        <option value="AM" class="text-gray-800">AM</option>
                        <option value="PM" class="text-gray-800">PM</option>
                    </select>
                </div>
                <input type="hidden" :name="`disponibilidad[${index}][hora_fin]`" :value="slot.fin">
                <p class="text-xs text-white/60 mt-1">
                    Seleccionado: <strong x-text="formatHora(slot.fin)"></strong>
                </p>
            </div>

            {{-- Botón eliminar --}}
            <div class="lg:col-span-1">
                <button type="button" @click="removeSlot(index)"
                        class="w-full bg-red-500/40 hover:bg-red-500/60 px-3 py-2 rounded-lg text-sm"
                        x-show="slots.length > 1"
                        title="Eliminar horario">
                    🗑️
                </button>
            </div>
        </div>
    </div>
</template>

            <button type="button" @click="addSlot()" 
                    class="bg-green-500/40 hover:bg-green-500/60 px-4 py-2 rounded-xl text-sm font-medium mb-4">
                ➕ Agregar horario
            </button>

            <div class="flex gap-3">
                <button type="submit" class="bg-white text-brand-600 px-6 py-3 rounded-xl font-bold hover:bg-white/90 transition">
                    Guardar Disponibilidad
                </button>
                <a href="{{ route('tutor.dashboard') }}" class="bg-white/20 hover:bg-white/30 px-6 py-3 rounded-xl font-bold transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@php
    $slotsIniciales = $disponibilidades->map(function ($d) {
        // Convertir "16:30:00" a formato con partes
        $horaInicio = substr($d->hora_inicio, 0, 5); // "16:30"
        $horaFin = substr($d->hora_fin, 0, 5);
        
        [$hInicio, $mInicio] = explode(':', $horaInicio);
        [$hFin, $mFin] = explode(':', $horaFin);
        
        $ampmInicio = (int)$hInicio >= 12 ? 'PM' : 'AM';
        $ampmFin = (int)$hFin >= 12 ? 'PM' : 'AM';
        
        $h12Inicio = (int)$hInicio % 12 ?: 12;
        $h12Fin = (int)$hFin % 12 ?: 12;
        
        return [
            'dia' => (string) $d->dia_semana,
            'inicio' => $horaInicio,
            'fin' => $horaFin,
            'inicio_hora' => str_pad($h12Inicio, 2, '0', STR_PAD_LEFT),
            'inicio_min' => $mInicio,
            'inicio_ampm' => $ampmInicio,
            'fin_hora' => str_pad($h12Fin, 2, '0', STR_PAD_LEFT),
            'fin_min' => $mFin,
            'fin_ampm' => $ampmFin,
        ];
    })->values();

    if ($slotsIniciales->isEmpty()) {
        $slotsIniciales = collect([[
            'dia' => '', 'inicio' => '', 'fin' => '',
            'inicio_hora' => '09', 'inicio_min' => '00', 'inicio_ampm' => 'AM',
            'fin_hora' => '10', 'fin_min' => '00', 'fin_ampm' => 'AM',
        ]]);
    }
@endphp

<script>
function disponibilidadForm() {
    return {
        slots: {!! json_encode($slotsIniciales) !!},
        
        addSlot() {
            this.slots.push({
                dia: '',
                inicio: '', fin: '',
                inicio_hora: '09', inicio_min: '00', inicio_ampm: 'AM',
                fin_hora: '10', fin_min: '00', fin_ampm: 'AM',
            });
        },
        
        removeSlot(index) {
            if (this.slots.length > 1) {
                this.slots.splice(index, 1);
            }
        },
        
        updateHoraInicio(index) {
            const s = this.slots[index];
            let h = parseInt(s.inicio_hora);
            if (s.inicio_ampm === 'PM' && h !== 12) h += 12;
            if (s.inicio_ampm === 'AM' && h === 12) h = 0;
            s.inicio = String(h).padStart(2, '0') + ':' + s.inicio_min;
        },
        
        updateHoraFin(index) {
            const s = this.slots[index];
            let h = parseInt(s.fin_hora);
            if (s.fin_ampm === 'PM' && h !== 12) h += 12;
            if (s.fin_ampm === 'AM' && h === 12) h = 0;
            s.fin = String(h).padStart(2, '0') + ':' + s.fin_min;
        },
        
        formatHora(hora24) {
            if (!hora24) return '—';
            const [h, m] = hora24.split(':');
            let hInt = parseInt(h);
            const ampm = hInt >= 12 ? 'PM' : 'AM';
            hInt = hInt % 12 || 12;
            return String(hInt).padStart(2, '0') + ':' + m + ' ' + ampm;
        },
    }
}
</script>
@endsection