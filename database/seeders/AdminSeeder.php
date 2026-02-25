<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin'
        ]);

        Admin::create([
            'nombre' => 'Admin',
            'apellido' => 'System',
            'cedula' => '1234567890',
            'correo' => 'admin@admin.com',
            'celular' => '1234567890',
            'titulo' => 'Administrador del Sistema',
            'fecha_nacimiento' => '1990-01-01',
            'direccion' => 'Dirección Admin',
            'user_id' => $user->id
        ]);
    }
}
