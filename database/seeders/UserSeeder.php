<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Crear el usuario administrador principal
        $user = User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Crear el registro de administrador
        Admin::create([
            'nombre' => 'Admin',
            'apellido' => 'Principal',
            'cedula' => '12345678',
            'correo' => 'admin@admin.com',
            'celular' => '1234567890',
            'titulo' => 'Administrador del Sistema',
            'fecha_nacimiento' => '1990-01-01',
            'direccion' => 'Dirección Principal',
            'rol' => 'super_admin',
            'user_id' => $user->id
        ]);
    }
}
