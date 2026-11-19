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

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 0. Ejecutar primero los Roles y Permisos de Spatie
        $this->call(RolesAndPermissionsSeeder::class);

        // 1. Datos Geográficos Reales
        $provincia = Provincia::create(['nombre' => 'Misiones', 'pais' => 'Argentina']);
        $posadas   = Localidad::create(['provincia_id' => $provincia->id, 'nombre' => 'Posadas']);
        $iguazu    = Localidad::create(['provincia_id' => $provincia->id, 'nombre' => 'Puerto Iguazú']);
        $obera     = Localidad::create(['provincia_id' => $provincia->id, 'nombre' => 'Oberá']);

        // 2. Estructura de Flota
        $marca  = Marca::create(['nombre' => 'Mercedes-Benz']);
        $modelo = Modelo::create([
            'marca_id'           => $marca->id,
            'nombre'             => 'Marcopolo Paradiso 1200',
            'capacidad_asientos' => 40,
        ]);

        $colectivo1 = Colectivo::create(['patente' => 'AB123CD', 'modelo_id' => $modelo->id, 'anio' => 2024]);
        $colectivo2 = Colectivo::create(['patente' => 'EF456GH', 'modelo_id' => $modelo->id, 'anio' => 2025]);
        $colectivo3 = Colectivo::create(['patente' => 'IJ789KL', 'modelo_id' => $modelo->id, 'anio' => 2026]);

        // 3. Rutas Base
        $ruta1 = Ruta::create(['origen_id' => $posadas->id, 'destino_id' => $iguazu->id, 'distancia' => 300.00, 'duracion_estimada' => '05:00:00']);
        $ruta2 = Ruta::create(['origen_id' => $posadas->id, 'destino_id' => $obera->id, 'distancia' => 95.50, 'duracion_estimada' => '01:45:00']);

        // 4. Usuarios Fijos de Prueba por Rol (Clave para todos: password)
        $admin = User::create([
            'nombre' => 'Admin', 'apellido' => 'Gurí', 'dni' => '11111111',
            'email' => 'admin@guri.com', 'telefono' => '3764111111', 'password' => bcrypt('password'),
        ])->assignRole('administrativo');

        $gerente = User::create([
            'nombre' => 'Carlos', 'apellido' => 'Gerente', 'dni' => '22222222',
            'email' => 'gerente@guri.com', 'telefono' => '3764222222', 'password' => bcrypt('password'),
        ])->assignRole('gerente');

        $boleteroFijo = User::create([
            'nombre' => 'Laura', 'apellido' => 'Boletera', 'dni' => '33333333',
            'email' => 'boletero@guri.com', 'telefono' => '3764333333', 'password' => bcrypt('password'),
        ])->assignRole('boletero');

        $userChoferFijo = User::create([
            'nombre' => 'Juan', 'apellido' => 'Chofer', 'dni' => '44444444',
            'email' => 'chofer@guri.com', 'telefono' => '3764444444', 'password' => bcrypt('password'),
        ])->assignRole('pasajero');

        $choferFijo = Chofer::create([
            'user_id' => $userChoferFijo->id, 'licencia' => 'N-44444444', 'estado' => 'activo',
        ]);

        $pasajeroFijo = User::create([
            'nombre' => 'María', 'apellido' => 'Pasajera', 'dni' => '55555555',
            'email' => 'pasajero@guri.com', 'telefono' => '3764555555', 'password' => bcrypt('password'),
        ])->assignRole('pasajero');

        // 5. Choferes y Usuarios Aleatorios Adicionales
        $choferesExtras = Chofer::factory(3)->create();
        $choferesExtras->each(fn($c) => $c->user->assignRole('pasajero'));
        $choferesIds = collect([$choferFijo->id])->merge($choferesExtras->pluck('id'))->toArray();

        $usuariosGenerales = User::factory(25)->create();
        $usuariosGenerales->each(fn($u) => $u->assignRole('pasajero'));

        $boleterosIds = [$boleteroFijo->id, $admin->id];
        $pasajerosIds = collect([$pasajeroFijo->id])->merge($usuariosGenerales->pluck('id'))->toArray();

        // 6. Oferta de Viajes (Con fechas dinámicas para que siempre estén vigentes)
        $viajes = [];
        $viajes[] = Viaje::create([
            'ruta_id' => $ruta1->id, 'colectivo_id' => $colectivo1->id,
            'fecha' => now()->addDays(1)->toDateString(), 'hora_salida' => '06:00:00', 'hora_llegada' => '11:00:00',
            'precio' => 12500.00, 'estado' => 'programado',
        ]);

        $viajes[] = Viaje::create([
            'ruta_id' => $ruta2->id, 'colectivo_id' => $colectivo2->id,
            'fecha' => now()->addDays(1)->toDateString(), 'hora_salida' => '14:00:00', 'hora_llegada' => '15:45:00',
            'precio' => 4500.00, 'estado' => 'programado',
        ]);

        $viajes[] = Viaje::create([
            'ruta_id' => $ruta1->id, 'colectivo_id' => $colectivo3->id,
            'fecha' => now()->addDays(2)->toDateString(), 'hora_salida' => '08:00:00', 'hora_llegada' => '13:00:00',
            'precio' => 13000.00, 'estado' => 'programado',
        ]);

        // 7. Asignación N:M de Conductores (Incluye a nuestro chofer fijo en los viajes 0 y 1)
        $viajes[0]->choferes()->attach([$choferesIds[0], $choferesIds[1]]);
        $viajes[1]->choferes()->attach([$choferesIds[0]]);
        $viajes[2]->choferes()->attach([$choferesIds[2]]);

        // 8. Simulación de 20 Ventas con Precios Reales por Butaca y Estados de Embarque
        $contadorTotalBoletos = 0;

        foreach ($viajes as $indexViaje => $viaje) {
            $asientosDisponibles = $viaje->colectivo->asientos()->where('estado', 'habilitado')->get();

            for ($i = 0; $i < 7; $i++) {
                if ($contadorTotalBoletos >= 20) break;

                // Asignamos el primer boleto de cada viaje a nuestra pasajera fija para que tenga historial
                $compradorId = ($i === 0) ? $pasajeroFijo->id : fake()->randomElement($pasajerosIds);
                $vendedorId  = fake()->randomElement($boleterosIds);
                $asiento     = $asientosDisponibles[$i];

                $precioReal = match ($asiento->tipo) {
                    'cama'  => round($viaje->precio * 1.25, 2),
                    'comun' => round($viaje->precio * 0.90, 2),
                    default => $viaje->precio,
                };

                $venta = Venta::create([
                    'cliente_id'  => $compradorId,
                    'vendedor_id' => $vendedorId,
                    'fecha_venta' => now()->subHours(fake()->numberBetween(1, 48)),
                    'total'       => $precioReal,
                    'metodo_pago' => fake()->randomElement(['efectivo', 'tarjeta', 'transferencia']),
                    'estado'      => 'completada',
                ]);

                $venta->boletos()->create([
                    'user_id'    => $compradorId,
                    'viaje_id'   => $viaje->id,
                    'asiento_id' => $asiento->id,
                    'precio'     => $precioReal,
                    'estado'     => 'vendido',
                    'embarque'   => fake()->randomElement(['pendiente', 'pendiente', 'embarcado', 'ausente']),
                ]);

                $contadorTotalBoletos++;
            }
        }
    }
}
