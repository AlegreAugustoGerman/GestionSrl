<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       User::create([
            'nombre' => 'Administrador',
            'apellido' => 'Gurí',
            'dni' => '99999999',  
            'email' => 'admin@admin.com',
            'telefono' => '3764111111',
            'password' => bcrypt('adminadmin'), // Encriptación obligatoria para Breeze
        ])->assignRole('administrativo');
           
          User::create([
            'nombre' => 'Usuario',
            'apellido' => 'Demo',
            'dni' => '88888888',
            'email' => 'demo@demo.com',
            'telefono' => '3764222222',
            'password' => bcrypt('demodemo'),
        ])->assignRole('gerente');

            User::create([
            'nombre' => 'Usuario',
            'apellido' => 'boletero',
            'dni' => '66666666',
            'email' => 'boletero@demo.com',
            'telefono' => '3764222333',
            'password' => bcrypt('demodemo'),
        ])->assignRole('boletero');

            User::create([
            'nombre' => 'Usuario',
            'apellido' => 'pasajero',
            'dni' => '77777777',
            'email' => 'pasajero@demo.com',
            'telefono' => '3764222111',
            'password' => bcrypt('demodemo'),
        ])->assignRole('pasajero');


    }
 }
