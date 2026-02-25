<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run()
    {
        // Crear grupo de prueba
        $group = Group::create([
            'codigo' => 'G001',
            'nombre' => 'Grupo 1',
            'estado' => 'activo'
        ]);

        // Crear usuario y docente de prueba
        $teacherUser = User::create([
            'name' => 'Profesor Prueba',
            'email' => 'profesor@test.com',
            'password' => Hash::make('12345678'),
            'role' => 'teacher'
        ]);

        Teacher::create([
            'nombre' => 'Profesor',
            'apellido' => 'Prueba',
            'cedula' => '98765432',
            'correo' => 'profesor@test.com',
            'celular' => '3001234567',
            'titulo' => 'Licenciado en Educación',
            'fecha_nacimiento' => '1985-05-15',
            'direccion' => 'Calle 123 #45-67',
            'user_id' => $teacherUser->id
        ]);

        // Crear usuario y estudiante de prueba
        $studentUser = User::create([
            'name' => 'Estudiante Prueba',
            'email' => 'estudiante@test.com',
            'password' => Hash::make('12345678'),
            'role' => 'student'
        ]);

        Student::create([
            'nombre' => 'Estudiante',
            'apellido' => 'Prueba',
            'cedula' => '12345678',
            'correo' => 'estudiante@test.com',
            'celular' => '3007654321',
            'fecha_nacimiento' => '2000-03-20',
            'direccion' => 'Avenida 456 #78-90',
            'group_id' => $group->id,
            'user_id' => $studentUser->id
        ]);
    }
}
