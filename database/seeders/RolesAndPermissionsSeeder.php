<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Limpiar caché de Spatie para evitar solapamientos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. CREAR LOS ROLES PRINCIPALES
        $roleAdmin = Role::create(['name' => 'administrativo']);
        $roleBoletero = Role::create(['name' => 'boletero']);
        $roleGerente = Role::create(['name' => 'gerente']);
        $rolePasajero = Role::create(['name' => 'pasajero']); // Rol por defecto de registro con Google

        // 3. MATRIZ DE PERMISOS BASADA EN MÓDULOS Y ACCIONES  

        // 👥 Módulo: Usuarios / Clientes (Administrativo gestiona todo, Boletero consulta)
        Permission::create(['name' => 'user.index'])->assignRole($roleAdmin, $roleBoletero);
        Permission::create(['name' => 'user.show'])->assignRole($roleAdmin, $roleBoletero);
        Permission::create(['name' => 'user.create'])->assignRole($roleAdmin);
        Permission::create(['name' => 'user.store'])->assignRole($roleAdmin);
        Permission::create(['name' => 'user.edit'])->assignRole($roleAdmin);
        Permission::create(['name' => 'user.update'])->assignRole($roleAdmin);
        Permission::create(['name' => 'user.destroy'])->assignRole($roleAdmin);

        // 🚌 Módulo: Colectivos, Marcas, Modelos y Asientos (Exclusivo Administrativo)
        Permission::create(['name' => 'colectivo.index'])->assignRole($roleAdmin);
        Permission::create(['name' => 'colectivo.show'])->assignRole($roleAdmin);
        Permission::create(['name' => 'colectivo.create'])->assignRole($roleAdmin);
        Permission::create(['name' => 'colectivo.store'])->assignRole($roleAdmin);
        Permission::create(['name' => 'colectivo.edit'])->assignRole($roleAdmin);
        Permission::create(['name' => 'colectivo.update'])->assignRole($roleAdmin);
        Permission::create(['name' => 'colectivo.destroy'])->assignRole($roleAdmin);

        // 🪪 Módulo: Choferes (Exclusivo Administrativo)
        Permission::create(['name' => 'chofer.index'])->assignRole($roleAdmin);
        Permission::create(['name' => 'chofer.show'])->assignRole($roleAdmin);
        Permission::create(['name' => 'chofer.create'])->assignRole($roleAdmin);
        Permission::create(['name' => 'chofer.store'])->assignRole($roleAdmin);
        Permission::create(['name' => 'chofer.edit'])->assignRole($roleAdmin);
        Permission::create(['name' => 'chofer.update'])->assignRole($roleAdmin);
        Permission::create(['name' => 'chofer.destroy'])->assignRole($roleAdmin);

        // 🗺️ Módulo: Rutas y Localidades (Exclusivo Administrativo)
        Permission::create(['name' => 'ruta.index'])->assignRole($roleAdmin);
        Permission::create(['name' => 'ruta.show'])->assignRole($roleAdmin);
        Permission::create(['name' => 'ruta.create'])->assignRole($roleAdmin);
        Permission::create(['name' => 'ruta.store'])->assignRole($roleAdmin);
        Permission::create(['name' => 'ruta.edit'])->assignRole($roleAdmin);
        Permission::create(['name' => 'ruta.update'])->assignRole($roleAdmin);
        Permission::create(['name' => 'ruta.destroy'])->assignRole($roleAdmin);

        // ⏳ Módulo: Viajes (Administrativo programa, Boletero y Gerente consultan disponibilidad)
        Permission::create(['name' => 'viaje.index'])->assignRole($roleAdmin, $roleBoletero, $roleGerente);
        Permission::create(['name' => 'viaje.show'])->assignRole($roleAdmin, $roleBoletero, $roleGerente);
        Permission::create(['name' => 'viaje.create'])->assignRole($roleAdmin);
        Permission::create(['name' => 'viaje.store'])->assignRole($roleAdmin);
        Permission::create(['name' => 'viaje.edit'])->assignRole($roleAdmin);
        Permission::create(['name' => 'viaje.update'])->assignRole($roleAdmin);
        Permission::create(['name' => 'viaje.destroy'])->assignRole($roleAdmin);

        // 🎫 Módulo: Operaciones Comerciales (Ventas y Boletos)
        Permission::create(['name' => 'venta.index'])->assignRole($roleAdmin, $roleBoletero, $roleGerente);
        Permission::create(['name' => 'venta.show'])->assignRole($roleAdmin, $roleBoletero, $roleGerente);
        Permission::create(['name' => 'venta.store'])->assignRole($roleBoletero); // Emitir pasaje/venta
        Permission::create(['name' => 'venta.destroy'])->assignRole($roleBoletero); // Anular pasaje/venta

        // 📊 Módulo: Reportes y Auditoría (Exclusivo Gerencia y Administrativo de control)
        Permission::create(['name' => 'reporte.recaudacion'])->assignRole($roleAdmin, $roleGerente);
        Permission::create(['name' => 'reporte.historial'])->assignRole($roleAdmin, $roleGerente);
    }
}
