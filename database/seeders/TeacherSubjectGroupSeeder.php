<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Group;
use Illuminate\Support\Facades\DB;

class TeacherSubjectGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener el primer docente
        $teacher = Teacher::first();
        
        if (!$teacher) {
            $this->command->info('No hay docentes en la base de datos. Ejecuta primero DatabaseSeeder.');
            return;
        }
        
        // Obtener grupos y materias
        $groups = Group::take(3)->get();
        $subjects = Subject::take(5)->get();
        
        if ($groups->isEmpty() || $subjects->isEmpty()) {
            $this->command->info('No hay suficientes grupos o materias en la base de datos.');
            return;
        }
        
        // Asignar grupos al docente
        foreach ($groups as $group) {
            // Verificar si ya existe la relación
            if (!DB::table('group_teacher')->where('teacher_id', $teacher->id)->where('group_id', $group->id)->exists()) {
                $teacher->groups()->attach($group->id);
                $this->command->info("Grupo {$group->nombre} asignado al docente {$teacher->nombre}");
            }
        }
        
        // Asignar materias al docente
        foreach ($subjects as $subject) {
            // Verificar si ya existe la relación
            if (!DB::table('subject_teacher')->where('teacher_id', $teacher->id)->where('subject_id', $subject->id)->exists()) {
                $teacher->subjects()->attach($subject->id);
                $this->command->info("Materia {$subject->nombre} asignada al docente {$teacher->nombre}");
            }
        }
        
        // Asignar materias a grupos
        foreach ($groups as $group) {
            foreach ($subjects->take(3) as $subject) {
                // Verificar si ya existe la relación
                if (!DB::table('group_subject')->where('group_id', $group->id)->where('subject_id', $subject->id)->exists()) {
                    $group->subjects()->attach($subject->id);
                    $this->command->info("Materia {$subject->nombre} asignada al grupo {$group->nombre}");
                }
            }
        }
    }
}
