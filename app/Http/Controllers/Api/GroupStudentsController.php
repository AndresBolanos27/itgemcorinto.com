<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Grade;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GroupStudentsController extends Controller
{
    public function index(Request $request, Group $group)
    {
        try {
            // Verificar que el usuario tenga acceso al grupo
            if (Auth::user()->role === 'teacher') {
                $teacher = Auth::user()->teacher;
                if (!$teacher->groups->contains($group->id)) {
                    return response()->json(['error' => 'No tienes permiso para ver este grupo'], 403);
                }
                // Filtrar materias por las asignadas al profesor
                $subjects = $teacher->subjects;
            } else {
                // Para administradores, todas las materias
                $subjects = Subject::all();
            }

            // Cargar estudiantes con sus calificaciones
            $students = $group->students()
                ->with(['grades' => function ($query) use ($subjects) {
                    $query->whereIn('subject_id', $subjects->pluck('id'));
                }])
                ->get();

            // Log para debugging
            Log::info('Students loaded:', [
                'group_id' => $group->id,
                'student_count' => $students->count(),
                'subjects' => $subjects->pluck('nombre', 'id')
            ]);

            // Mapear estudiantes con sus calificaciones
            $mappedStudents = $students->map(function ($student) use ($subjects) {
                $grades = [];
                foreach ($subjects as $subject) {
                    $grade = $student->grades->where('subject_id', $subject->id)->first();
                    $grades[$subject->id] = [
                        'id' => $grade ? $grade->id : null,
                        'grade' => $grade ? $grade->grade : null,
                        'description' => $grade ? $grade->description : null,
                        'status' => $this->getGradeStatus($grade ? $grade->grade : null)
                    ];
                }

                return [
                    'id' => $student->id,
                    'nombre' => $student->nombre,
                    'apellido' => $student->apellido,
                    'grades' => $grades
                ];
            });

            // Log para debugging
            Log::info('Response data:', [
                'student_count' => $mappedStudents->count(),
                'subject_count' => $subjects->count()
            ]);

            return response()->json([
                'students' => $mappedStudents,
                'subjects' => $subjects->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'nombre' => $subject->nombre
                    ];
                })
            ]);
        } catch (\Exception $e) {
            Log::error('Error in GroupStudentsController:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'Error al cargar los estudiantes y calificaciones',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getGradeStatus($grade)
    {
        if ($grade === null) return 'pending';
        if ($grade >= 70) return 'good';
        if ($grade >= 60) return 'warning';
        return 'danger';
    }
}
