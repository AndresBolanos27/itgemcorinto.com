<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentGradeController extends Controller
{
    public function index()
    {
        // Verificar si el usuario es estudiante
        if (Auth::user()->role !== 'student') {
            return redirect('/dashboard')->with('error', 'Acceso no autorizado.');
        }

        // Obtener el estudiante actualmente autenticado
        $studentId = Auth::user()->id;
        $student = Student::where('user_id', $studentId)->first();
        
        if (!$student) {
            return redirect('/dashboard')
                ->with('error', 'No se encontró información de estudiante asociada a tu cuenta.');
        }

        // Obtener el grupo del estudiante
        $group = $student->group;
        
        if (!$group || $group->estado !== 'activo') {
            return view('student.grades.index', [
                'subjects' => collect(),
                'grades' => collect(),
                'noGroup' => true,
                'noGroupMessage' => 'No estás asignado a un grupo activo. Contacta al administrador.'
            ]);
        }

        // Obtener las materias del grupo a través de cargas académicas
        $subjects = Subject::whereHas('academicLoads', function($query) use ($group) {
            $query->where('group_id', $group->id)
                  ->where('academic_loads.estado', 'activo');
        })->get();
        
        if ($subjects->isEmpty()) {
            // Si no hay materias a través de cargas académicas, intentar obtenerlas de la relación directa
            $subjects = $group->subjects()->where('subjects.estado', 'activo')->get();
        }
        
        if ($subjects->isEmpty()) {
            return view('student.grades.index', [
                'subjects' => collect(),
                'grades' => collect(),
                'noSubjects' => true,
                'noSubjectsMessage' => 'Tu grupo no tiene materias asignadas. Contacta al administrador.'
            ]);
        }

        // Obtener las calificaciones del estudiante para todas las materias
        $grades = Grade::where('student_id', $student->id)
                       ->whereIn('subject_id', $subjects->pluck('id'))
                       ->get()
                       ->keyBy('subject_id');
        
        return view('student.grades.index', [
            'student' => $student,
            'group' => $group,
            'subjects' => $subjects,
            'grades' => $grades
        ]);
    }
}
