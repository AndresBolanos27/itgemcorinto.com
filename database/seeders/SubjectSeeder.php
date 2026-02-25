<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        Subject::create([
            'codigo' => 'MAT101',
            'nombre' => 'Matemáticas Básicas',
            'descripcion' => 'Curso introductorio de matemáticas'
        ]);

        Subject::create([
            'codigo' => 'FIS101',
            'nombre' => 'Física General',
            'descripcion' => 'Fundamentos de física'
        ]);
    }
}
