@extends('template')

@section('title', 'Emisión de Pasajes')

@section('content')
<div class="max-w-6xl mx-auto mt-2">
    @if(session('error'))
        <div class="bg-red-50 border border-red-300 text-red-700 p-4 rounded-xl mb-6 text-sm font-bold">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-xl mb-6 text-sm">
            <ul class="list-disc pl-5">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('boleteria.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        @csrf
        <input type="hidden" name="viaje_id" value="{{ $viaje->id }}">

        <!-- 💺 COLUMNA IZQUIERDA: MAPA MULTI-SELECCIÓN CON TARIFA REAL POR BUTACA -->
        <div class="lg:col-span-6 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-100">
                <div>
                    <h2 class="font-black text-gray-800 text-lg">Seleccioná Butacas</h2>
                    <p class="text-xs text-gray-500">Tarifa base viaje: ${{ number_format($viaje->precio, 2, ',', '.') }}</p>
                </div>
                <div class="flex space-x-3 text-xs font-bold">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-gray-100 border border-gray-300 inline-block"></span> Libre</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-blue-600 inline-block"></span> Elegido</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded bg-red-100 border border-red-300 inline-block"></span> Vendido</span>
                </div>
            </div>

            <div class="bg-gray-100 text-center py-2 rounded-t-2xl text-xs font-bold uppercase tracking-widest text-gray-500 mb-4 border border-gray-200">
                🛞 Cabina de Conducción
            </div>

            <div class="grid grid-cols-4 gap-3 max-w-md mx-auto">
                @foreach($viaje->colectivo->asientos as $asiento)
                    @php
                        $ocupado = in_array($asiento->id, $asientosOcupadosIds);
                        $inhabilitado = $asiento->estado !== 'habilitado';
                        
                        // Precio real calculado desde el valor de la DB según categoría
                        $precioReal = match ($asiento->tipo) {
                            'cama'  => round($viaje->precio * 1.25, 2),
                            'comun' => round($viaje->precio * 0.90, 2),
                            default => (float) $viaje->precio,
                        };
                    @endphp
                    <label class="relative select-none {{ ($ocupado || $inhabilitado) ? 'cursor-not-allowed' : 'cursor-pointer' }}">
                        <input type="checkbox" value="{{ $asiento->id }}" 
                               data-numero="{{ $asiento->numero }}" 
                               data-precio="{{ $precioReal }}"
                               data-tipo="{{ strtoupper($asiento->tipo) }}"
                               onchange="toggleAsiento(this)" class="sr-only" {{ ($ocupado || $inhabilitado) ? 'disabled' : '' }}>
                        
                        <div id="box-asiento-{{ $asiento->id }}" class="p-2.5 rounded-xl border text-center transition
                            {{ $ocupado ? 'bg-red-50 border-red-200 text-red-400' : 
                               ($inhabilitado ? 'bg-gray-200 border-gray-300 text-gray-400' : 
                               'bg-gray-50 border-gray-200 text-gray-800 hover:border-blue-400') }}">
                            <span class="block font-black text-base">{{ str_pad($asiento->numero, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="block text-[9px] font-bold uppercase tracking-wider opacity-80">{{ $ocupado ? 'Ocupado' : $asiento->tipo }}</span>
                            @if(!$ocupado && !$inhabilitado)
                                <span class="block text-[10px] font-semibold mt-0.5">${{ number_format($precioReal, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- 🧾 COLUMNA DERECHA: COMPRADOR Y PASAJEROS NOMINALES -->
        <div class="lg:col-span-6 bg-white p-6 rounded-2xl border border-gray-200 shadow-sm flex flex-col justify-between space-y-5">
            <div class="space-y-4">
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 flex justify-between items-center">
                    <div>
                        <h3 class="font-black text-gray-900 text-sm">{{ $viaje->ruta->origen->nombre }} ➔ {{ $viaje->ruta->destino->nombre }}</h3>
                        <p class="text-xs text-gray-600">📅 {{ date('d/m/Y', strtotime($viaje->fecha)) }} • {{ date('H:i', strtotime($viaje->hora_salida)) }} hs</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] font-bold uppercase text-gray-500 block">Total (<span id="cant-butacas">0</span>)</span>
                        <span class="text-xl font-black text-blue-700">$<span id="total-cobrar">0,00</span></span>
                    </div>
                </div>

                <!-- 1. Titular de la Venta -->
                <div class="border border-gray-200 p-4 rounded-xl space-y-3 bg-gray-50/50">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-black uppercase text-gray-700">1. Datos del Comprador (Titular de Venta)</span>
                        <button type="button" onclick="limpiarFormulario()" class="text-[11px] font-bold text-red-500 hover:text-red-700 underline">
                            Limpiar todo
                        </button>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <input type="text" id="comp_dni" name="comprador_dni" onblur="autocompletar(this.value, 'comp')" placeholder="DNI (sin puntos)" class="border border-gray-300 p-2 rounded-lg text-sm" required>
                        <input type="text" id="comp_nombre" name="comprador_nombre" placeholder="Nombre" class="border border-gray-300 p-2 rounded-lg text-sm" required>
                        <input type="text" id="comp_apellido" name="comprador_apellido" placeholder="Apellido" class="border border-gray-300 p-2 rounded-lg text-sm" required>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="tel" id="comp_telefono" name="comprador_telefono" placeholder="Teléfono (Opcional)" class="border border-gray-300 p-2 rounded-lg text-sm">
                        <select name="metodo_pago" class="border border-gray-300 p-2 rounded-lg text-sm" required>
                            <option value="efectivo">Pago en Efectivo</option>
                            <option value="tarjeta">Tarjeta Débito/Crédito</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>
                </div>

                <!-- 2. Pasajeros por cada asiento -->
                <div class="space-y-3">
                    <span class="text-xs font-black uppercase text-gray-700 block">2. Pasajeros por Butaca Seleccionada</span>
                    <div id="contenedor-pasajeros" class="space-y-2 max-h-60 overflow-y-auto pr-1">
                        <p class="text-xs text-gray-400 italic">Marcá una o más butacas en el mapa de la izquierda...</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex space-x-3">
                <a href="{{ route('boleteria.index') }}" class="w-1/3 text-center bg-gray-100 text-gray-700 py-3 rounded-xl font-bold text-sm hover:bg-gray-200 transition">Volver</a>
                <button type="submit" class="w-2/3 bg-blue-600 text-white py-3 rounded-xl font-black uppercase tracking-wider text-sm hover:bg-blue-700 shadow-md transition">
                    Confirmar Venta
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    const urlBuscarDni = "{{ url('/boleteria/buscar-dni') }}";
    let asientosElegidos = [];

    function toggleAsiento(checkbox) {
        guardarDatosTemporales();

        const id     = checkbox.value;
        const numero = checkbox.dataset.numero;
        const precio = parseFloat(checkbox.dataset.precio);
        const tipo   = checkbox.dataset.tipo;
        const box    = document.getElementById('box-asiento-' + id);

        if (checkbox.checked) {
            box.classList.remove('bg-gray-50', 'text-gray-800', 'border-gray-200');
            box.classList.add('bg-blue-600', 'text-white', 'border-blue-600', 'shadow-md');
            asientosElegidos.push({ id: id, numero: numero, precio: precio, tipo: tipo, dni: '', nombre: '', apellido: '' });
        } else {
            box.classList.add('bg-gray-50', 'text-gray-800', 'border-gray-200');
            box.classList.remove('bg-blue-600', 'text-white', 'border-blue-600', 'shadow-md');
            asientosElegidos = asientosElegidos.filter(a => a.id !== id);
        }
        renderizarPasajeros();
    }

    function guardarDatosTemporales() {
        asientosElegidos.forEach((asiento, index) => {
            const dniInput = document.getElementById('pas_' + index + '_dni');
            if (dniInput) {
                asiento.dni      = dniInput.value;
                asiento.nombre   = document.getElementById('pas_' + index + '_nombre').value;
                asiento.apellido = document.getElementById('pas_' + index + '_apellido').value;
            }
        });
    }

    function renderizarPasajeros() {
        const contenedor = document.getElementById('contenedor-pasajeros');
        const sumaTotal  = asientosElegidos.reduce((acc, item) => acc + item.precio, 0);

        document.getElementById('cant-butacas').innerText = asientosElegidos.length;
        document.getElementById('total-cobrar').innerText = sumaTotal.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        if (asientosElegidos.length === 0) {
            contenedor.innerHTML = '<p class="text-xs text-gray-400 italic">Marcá una o más butacas en el mapa de la izquierda...</p>';
            return;
        }

        let html = '';
        asientosElegidos.forEach((asiento, index) => {
            const numFormateado    = String(asiento.numero).padStart(2, '0');
            const precioFormateado = asiento.precio.toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

            html += '<div class="p-3 border border-blue-100 bg-blue-50/30 rounded-xl space-y-2">'
                  + '  <div class="flex justify-between items-center">'
                  + '    <span class="text-xs font-black text-blue-700">Butaca N° ' + numFormateado + ' (' + asiento.tipo + ') — $' + precioFormateado + '</span>'
                  + '    <button type="button" onclick="copiarComprador(' + index + ')" class="text-[11px] font-bold text-blue-600 hover:underline">Copiar comprador</button>'
                  + '  </div>'
                  + '  <input type="hidden" name="pasajeros[' + index + '][asiento_id]" value="' + asiento.id + '">'
                  + '  <div class="grid grid-cols-3 gap-2">'
                  + '    <input type="text" id="pas_' + index + '_dni" name="pasajeros[' + index + '][dni]" value="' + asiento.dni + '" onblur="autocompletar(this.value, \'pas_' + index + '\')" placeholder="DNI" class="border border-gray-300 p-1.5 rounded-lg text-xs" required>'
                  + '    <input type="text" id="pas_' + index + '_nombre" name="pasajeros[' + index + '][nombre]" value="' + asiento.nombre + '" placeholder="Nombre" class="border border-gray-300 p-1.5 rounded-lg text-xs" required>'
                  + '    <input type="text" id="pas_' + index + '_apellido" name="pasajeros[' + index + '][apellido]" value="' + asiento.apellido + '" placeholder="Apellido" class="border border-gray-300 p-1.5 rounded-lg text-xs" required>'
                  + '  </div>'
                  + '</div>';
        });

        contenedor.innerHTML = html;
    }

    function copiarComprador(index) {
        document.getElementById('pas_' + index + '_dni').value      = document.getElementById('comp_dni').value;
        document.getElementById('pas_' + index + '_nombre').value   = document.getElementById('comp_nombre').value;
        document.getElementById('pas_' + index + '_apellido').value = document.getElementById('comp_apellido').value;
        guardarDatosTemporales();
    }

    function autocompletar(dni, prefijo) {
        if (!dni || dni.length < 7) return;
        fetch(urlBuscarDni + '/' + dni)
            .then(res => res.json())
            .then(data => {
                if (data && data.nombre) {
                    document.getElementById(prefijo + '_nombre').value = data.nombre;
                    document.getElementById(prefijo + '_apellido').value = data.apellido;
                    if (prefijo === 'comp' && data.telefono) {
                        document.getElementById('comp_telefono').value = data.telefono;
                    }
                    guardarDatosTemporales();
                }
            })
            .catch(err => console.error('Error buscando DNI:', err));
    }

    function limpiarFormulario() {
        document.getElementById('comp_dni').value      = '';
        document.getElementById('comp_nombre').value   = '';
        document.getElementById('comp_apellido').value = '';
        document.getElementById('comp_telefono').value = '';

        asientosElegidos.forEach(asiento => {
            const box = document.getElementById('box-asiento-' + asiento.id);
            if (box) {
                box.classList.add('bg-gray-50', 'text-gray-800', 'border-gray-200');
                box.classList.remove('bg-blue-600', 'text-white', 'border-blue-600', 'shadow-md');
            }
        });

        document.querySelectorAll('input[type="checkbox"]:not(:disabled)').forEach(cb => cb.checked = false);
        asientosElegidos = [];
        renderizarPasajeros();
    }
</script>
@endsection
