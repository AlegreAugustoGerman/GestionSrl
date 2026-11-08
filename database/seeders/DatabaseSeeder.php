<?php

namespace Database\Seeders;

 

use App\Models\User;
use App\Models\Chofer;
use App\Models\Provincia;
use App\Models\Localidad;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Colectivo;
use App\Models\Ruta;
use App\Models\Viaje;
use App\Models\Venta;
use Illuminate\Database\Seeder;
 

class DatabaseSeeder extends Seeder {
    public function run(): void {
        
        // 1. Datos Geográficos Reales
        $provincia = Provincia::create(['nombre' => 'Misiones', 'pais' => 'Argentina']);
        $posadas = Localidad::create(['provincia_id' => $provincia->id, 'nombre' => 'Posadas']);
        $iguazu = Localidad::create(['provincia_id' => $provincia->id, 'nombre' => 'Puerto Iguazú']);
        $obera = Localidad::create(['provincia_id' => $provincia->id, 'nombre' => 'Oberá']);

        // 2. Estructura de Flota
        $marca = Marca::create(['nombre' => 'Mercedes-Benz']);
        $modelo = Modelo::create([
            'marca_id' => $marca->id, 
            'nombre' => 'Marcopolo Paradiso 1200', 
            'capacidad_asientos' => 40
        ]);

        $colectivo1 = Colectivo::create(['patente' => 'AB123CD', 'modelo_id' => $modelo->id, 'anio' => 2024]);
        $colectivo2 = Colectivo::create(['patente' => 'EF456GH', 'modelo_id' => $modelo->id, 'anio' => 2025]);
        $colectivo3 = Colectivo::create(['patente' => 'IJ789KL', 'modelo_id' => $modelo->id, 'anio' => 2026]);

        // 3. Rutas Base
        $ruta1 = Ruta::create(['origen_id' => $posadas->id, 'destino_id' => $iguazu->id, 'distancia' => 300.00, 'duracion_estimada' => '05:00:00']);
        $ruta2 = Ruta::create(['origen_id' => $posadas->id, 'destino_id' => $obera->id, 'distancia' => 95.50, 'duracion_estimada' => '01:45:00']);

        // 4. Choferes
        $choferes = Chofer::factory(4)->create();
        $choferesIds = $choferes->pluck('id')->toArray();

        // 5. Usuarios del Sistema (Boleteros y Pasajeros)
        $usuariosGenerales = User::factory(30)->create();
        $boleterosIds = [$usuariosGenerales[0]->id, $usuariosGenerales[1]->id];
        $pasajerosIds = $usuariosGenerales->skip(2)->pluck('id')->toArray();

        // 6. Oferta de Viajes
        $viajes = [];
        $viajes[] = Viaje::create([
            'ruta_id' => $ruta1->id, 'colectivo_id' => $colectivo1->id,
            'fecha' => '2026-11-15', 'hora_salida' => '06:00:00', 'hora_llegada' => '11:00:00',
            'precio' => 12500.00, 'estado' => 'programado'
        ]);

        $viajes[] = Viaje::create([
            'ruta_id' => $ruta2->id, 'colectivo_id' => $colectivo2->id,
            'fecha' => '2026-11-15', 'hora_salida' => '14:00:00', 'hora_llegada' => '15:45:00',
            'precio' => 4500.00, 'estado' => 'programado'
        ]);

        $viajes[] = Viaje::create([
            'ruta_id' => $ruta1->id, 'colectivo_id' => $colectivo3->id,
            'fecha' => '2026-11-16', 'hora_salida' => '08:00:00', 'hora_llegada' => '13:00:00',
            'precio' => 13000.00, 'estado' => 'programado'
        ]);

        // 7. Asignación N:M de Conductores
        $viajes[0]->choferes()->attach([$choferesIds[0], $choferesIds[1]]);
        $viajes[1]->choferes()->attach([$choferesIds[2]]);
        $viajes[2]->choferes()->attach([$choferesIds[3]]);

        // 8. SIMULACIÓN DE VENTAS DE PASAJES (Genera exactamente 20 transacciones con boletos vinculados)
        $contadorTotalBoletos = 0;

        foreach ($viajes as $viaje) {
            $asientosDisponibles = $viaje->colectivo->asientos()->where('estado', 'habilitado')->get();
            
            // Cada viaje simulará la venta de varias plazas
            for ($i = 0; $i < 7; $i++) {
                if ($contadorTotalBoletos >= 20) break;

                $compradorId = fake()->randomElement($pasajerosIds);
                $vendedorId = fake()->randomElement($boleterosIds);
                $asiento = $asientosDisponibles[$i];

                // 8.1 Creamos la cabecera de la venta
                $venta = Venta::create([
                    'cliente_id' => $compradorId,
                    'vendedor_id' => $vendedorId,
                    'fecha_venta' => now()->subDays(fake()->numberBetween(1, 4)),
                    'total' => $viaje->precio, // En este caso simple, un pasaje por venta
                    'metodo_pago' => fake()->randomElement(['efectivo', 'tarjeta', 'transferencia']),
                    'estado' => 'completada'
                ]);

                // 8.2 Anexamos el boleto fáctico enlazado a la venta
                $venta->boletos()->create([
                    'user_id' => $compradorId, // Viaja el mismo comprador para la simulación
                    'viaje_id' => $viaje->id,
                    'asiento_id' => $asiento->id,
                    'precio' => $viaje->precio,
                    'estado' => 'vendido'
                ]);

                $contadorTotalBoletos++;
            }
        }

}
}