<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Group;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Crear usuario administrador
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Crear usuarios para docentes
        $user1 = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan@example.com',
            'password' => Hash::make('password'),
            'role' => 'teacher'
        ]);

        $user2 = User::create([
            'name' => 'María González',
            'email' => 'maria@example.com',
            'password' => Hash::make('password'),
            'role' => 'teacher'
        ]);

        // Crear docentes
        $teacher1 = Teacher::create([
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'cedula' => '123456789',
            'correo' => 'juan@example.com',
            'celular' => '3001234567',
            'titulo' => 'Licenciado en Matemáticas',
            'fecha_nacimiento' => '1985-01-15',
            'direccion' => 'Calle 123',
            'user_id' => $user1->id
        ]);

        $teacher2 = Teacher::create([
            'nombre' => 'María',
            'apellido' => 'González',
            'cedula' => '987654321',
            'correo' => 'maria@example.com',
            'celular' => '3007654321',
            'titulo' => 'Licenciada en Ciencias',
            'fecha_nacimiento' => '1988-05-20',
            'direccion' => 'Carrera 456',
            'user_id' => $user2->id
        ]);

        // Crear grupos
        $group1 = Group::create([
            'codigo' => 'G1-2025',
            'nombre' => 'Grupo 1',
            'estado' => 'activo'
        ]);

        $group2 = Group::create([
            'codigo' => 'G2-2025',
            'nombre' => 'Grupo 2',
            'estado' => 'activo'
        ]);

        // Crear materias
        $subjects = [
            Subject::create([
                'codigo' => 'MAT101',
                'nombre' => 'Matemáticas',
                'descripcion' => 'Curso básico de matemáticas'
            ]),
            Subject::create([
                'codigo' => 'ESP101',
                'nombre' => 'Español',
                'descripcion' => 'Curso de lengua española'
            ]),
            Subject::create([
                'codigo' => 'CIE101',
                'nombre' => 'Ciencias',
                'descripcion' => 'Introducción a las ciencias naturales'
            ]),
            Subject::create([
                'codigo' => 'HIS101',
                'nombre' => 'Historia',
                'descripcion' => 'Historia general'
            ])
        ];

        // Asignar materias a grupos
        foreach ($subjects as $subject) {
            $group1->subjects()->attach($subject->id);
            $group2->subjects()->attach($subject->id);
        }

        // Asignar materias a docentes
        $teacher1->subjects()->attach([$subjects[0]->id, $subjects[1]->id]); // Matemáticas y Español
        $teacher2->subjects()->attach([$subjects[2]->id, $subjects[3]->id]); // Ciencias e Historia

        // Asignar grupos a docentes
        $teacher1->groups()->attach($group1->id);
        $teacher2->groups()->attach($group2->id);

        // Crear usuarios para estudiantes
        $studentUsers = [
            User::create(['name' => 'Ana Martínez', 'email' => 'ana@example.com', 'password' => Hash::make('password'), 'role' => 'student']),
            User::create(['name' => 'Carlos López', 'email' => 'carlos@example.com', 'password' => Hash::make('password'), 'role' => 'student']),
            User::create(['name' => 'Diana Ramírez', 'email' => 'diana@example.com', 'password' => Hash::make('password'), 'role' => 'student']),
            User::create(['name' => 'Eduardo García', 'email' => 'eduardo@example.com', 'password' => Hash::make('password'), 'role' => 'student']),
            User::create(['name' => 'Fernanda Torres', 'email' => 'fernanda@example.com', 'password' => Hash::make('password'), 'role' => 'student']),
            User::create(['name' => 'Gabriel Sánchez', 'email' => 'gabriel@example.com', 'password' => Hash::make('password'), 'role' => 'student']),
        ];

        // Crear estudiantes
        $students = [
            [
                'nombre' => 'Ana', 
                'apellido' => 'Martínez', 
                'cedula' => '1001001',
                'correo' => 'ana@example.com',
                'celular' => '3001111111',
                'fecha_nacimiento' => '2000-03-15',
                'direccion' => 'Calle 1 #123',
                'group_id' => $group1->id,
                'user_id' => $studentUsers[0]->id
            ],
            [
                'nombre' => 'Carlos', 
                'apellido' => 'López', 
                'cedula' => '1001002',
                'correo' => 'carlos@example.com',
                'celular' => '3002222222',
                'fecha_nacimiento' => '2000-05-20',
                'direccion' => 'Calle 2 #456',
                'group_id' => $group1->id,
                'user_id' => $studentUsers[1]->id
            ],
            [
                'nombre' => 'Diana', 
                'apellido' => 'Ramírez', 
                'cedula' => '1001003',
                'correo' => 'diana@example.com',
                'celular' => '3003333333',
                'fecha_nacimiento' => '2000-07-10',
                'direccion' => 'Calle 3 #789',
                'group_id' => $group1->id,
                'user_id' => $studentUsers[2]->id
            ],
            [
                'nombre' => 'Eduardo', 
                'apellido' => 'García', 
                'cedula' => '1001004',
                'correo' => 'eduardo@example.com',
                'celular' => '3004444444',
                'fecha_nacimiento' => '2000-09-25',
                'direccion' => 'Calle 4 #012',
                'group_id' => $group2->id,
                'user_id' => $studentUsers[3]->id
            ],
            [
                'nombre' => 'Fernanda', 
                'apellido' => 'Torres', 
                'cedula' => '1001005',
                'correo' => 'fernanda@example.com',
                'celular' => '3005555555',
                'fecha_nacimiento' => '2000-11-30',
                'direccion' => 'Calle 5 #345',
                'group_id' => $group2->id,
                'user_id' => $studentUsers[4]->id
            ],
            [
                'nombre' => 'Gabriel', 
                'apellido' => 'Sánchez', 
                'cedula' => '1001006',
                'correo' => 'gabriel@example.com',
                'celular' => '3006666666',
                'fecha_nacimiento' => '2001-01-05',
                'direccion' => 'Calle 6 #678',
                'group_id' => $group2->id,
                'user_id' => $studentUsers[5]->id
            ],
        ];

        foreach ($students as $studentData) {
            Student::create($studentData);
        }

        // Crear algunas notas de ejemplo
        $students = Student::all();
        foreach ($students as $student) {
            foreach ($subjects as $subject) {
                if (rand(0, 1)) { // 50% de probabilidad de tener nota
                    Grade::create([
                        'student_id' => $student->id,
                        'subject_id' => $subject->id,
                        'nota_final' => number_format(rand(30, 50) / 10, 1),
                        'observacion' => rand(0, 1) ? 'Buen desempeño' : 'Necesita mejorar'
                    ]);
                }
            }
        }
    }
}
