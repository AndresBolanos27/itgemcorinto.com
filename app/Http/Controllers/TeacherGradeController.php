<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Grade;
use App\Models\AcademicLoad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherGradeController extends Controller
{
    public function __construct()
    {
        // Eliminamos el middleware interno y usaremos verificación directa
    }

    public function index()
    {
        // Verificar si el usuario es docente
        if (Auth::user()->role !== 'teacher') {
            return redirect('/dashboard')->with('error', 'Acceso no autorizado.');
        }

        // Obtener el docente actualmente autenticado
        $teacherId = Auth::user()->id;
        $teacher = Teacher::where('user_id', $teacherId)->first();
        
        if (!$teacher) {
            return redirect()->route('teacher.dashboard')
                ->with('error', 'No se encontró información de docente asociada a tu cuenta.');
        }

        // Verificar si el docente tiene cargas académicas asignadas
        $hasAcademicLoads = $teacher->academicLoads()->where('estado', 'activo')->exists();
        
        if (!$hasAcademicLoads) {
            return view('teacher.grades.index', [
                'groups' => collect(),
                'subjects' => collect(),
                'students' => collect(),
                'noAssignments' => true,
                'noAssignmentsMessage' => 'No tienes asignaciones de carga académica. Contacta al administrador para que te asigne grupos y materias.'
            ]);
        }

        // Obtener los grupos asignados al docente a través de las cargas académicas
        $groups = $teacher->groupsViaLoads()->distinct()->get();
        
        if ($groups->isEmpty()) {
            return view('teacher.grades.index', [
                'groups' => collect(),
                'subjects' => collect(),
                'students' => collect(),
                'noAssignments' => true,
                'noAssignmentsMessage' => 'No tienes grupos asignados activos en tus cargas académicas.'
            ]);
        }

        // Si hay un group_id en la solicitud, obtener las materias asignadas a ese grupo para este docente
        $subjects = collect();
        $students = collect();
        $selectedGroup = null;
        
        if ($groupId = request('group_id')) {
            $selectedGroup = $groups->where('id', $groupId)->first();
            
            if ($selectedGroup) {
                // Obtener las materias asignadas a este docente para el grupo seleccionado
                $subjects = Subject::whereHas('academicLoads', function($query) use ($teacher, $groupId) {
                    $query->where('teacher_id', $teacher->id)
                          ->where('group_id', $groupId)
                          ->where('estado', 'activo');
                })->get();
                
                if ($subjects->isEmpty()) {
                    return view('teacher.grades.index', [
                        'groups' => $groups,
                        'subjects' => collect(),
                        'students' => collect(),
                        'noSubjectsInGroup' => true
                    ]);
                }
                
                // Obtener los estudiantes del grupo seleccionado, independientemente de si hay una materia seleccionada
                $students = Student::where('group_id', $groupId)
                                   ->where('estado', 'activo')
                                   ->orderBy('apellido')
                                   ->get();
                
                // Si hay un subject_id en la solicitud, obtener las calificaciones para esa materia
                if ($subjectId = request('subject_id')) {
                    // Verificar si la materia seleccionada está asignada a este docente para este grupo
                    $hasSubjectInGroup = AcademicLoad::where('teacher_id', $teacher->id)
                                                     ->where('group_id', $groupId)
                                                     ->where('subject_id', $subjectId)
                                                     ->where('estado', 'activo')
                                                     ->exists();
                    
                    if (!$hasSubjectInGroup) {
                        return redirect()->route('teacher.grades.index', ['group_id' => $groupId])
                            ->with('error', 'No tienes asignada esta materia para este grupo.');
                    }
                    
                    // Para cada estudiante, obtener o crear su calificación para esta materia
                    foreach ($students as $student) {
                        $grade = Grade::firstOrCreate(
                            ['student_id' => $student->id, 'subject_id' => $subjectId],
                            ['nota_final' => null, 'value' => null, 'observacion' => null]
                        );
                        
                        $student->grade = $grade;
                    }
                }
            }
        }
        
        return view('teacher.grades.index', [
            'groups' => $groups,
            'subjects' => $subjects,
            'students' => $students,
            'selectedGroup' => $selectedGroup
        ]);
    }

    /**
     * Obtener la calificación de un estudiante para una materia específica
     */
    public function getGrade($subjectId)
    {
        // Verificar si el usuario es docente
        if (Auth::user()->role !== 'teacher') {
            return response()->json(['error' => 'Acceso no autorizado.'], 403);
        }

        // Verificar que el docente exista
        $teacherId = Auth::user()->id;
        $teacher = Teacher::where('user_id', $teacherId)->first();
        
        if (!$teacher) {
            return response()->json(['error' => 'No se encontró información de docente.'], 403);
        }
        
        $groupId = request('group_id');
        
        // Verificar que el grupo exista
        $group = Group::find($groupId);
        if (!$group) {
            return response()->json(['error' => 'Grupo no encontrado.'], 404);
        }
        
        // Verificar que el docente tenga asignada esta materia para este grupo a través de cargas académicas
        $hasSubjectInGroup = AcademicLoad::where('teacher_id', $teacher->id)
            ->where('subject_id', $subjectId)
            ->where('group_id', $groupId)
            ->where('estado', 'activo')
            ->exists();
            
        if (!$hasSubjectInGroup) {
            return response()->json(['error' => 'No tienes asignada esta materia para este grupo.'], 403);
        }
        
        // Obtener todos los estudiantes del grupo
        $students = Student::where('group_id', $groupId)
            ->where('estado', 'activo')
            ->get();
            
        // Obtener todas las calificaciones para estos estudiantes en esta materia
        $studentIds = $students->pluck('id')->toArray();
        $grades = Grade::whereIn('student_id', $studentIds)
            ->where('subject_id', $subjectId)
            ->get();
            
        return response()->json([
            'success' => true,
            'grades' => $grades,
            'students' => $students
        ]);
    }

    public function batchUpdate(Request $request)
    {
        // Verificar si el usuario es docente
        if (Auth::user()->role !== 'teacher') {
            return response()->json(['error' => 'Acceso no autorizado.'], 403);
        }

        $teacherId = Auth::user()->id;
        $teacher = Teacher::where('user_id', $teacherId)->first();
        
        if (!$teacher) {
            return response()->json(['error' => 'No se encontró información de docente.'], 403);
        }
        
        $subjectId = $request->subject_id;
        $groupId = $request->group_id;
        
        // Verificar que el docente tenga asignada esta materia a través de cargas académicas
        $hasSubjectInGroup = AcademicLoad::where('teacher_id', $teacher->id)
            ->where('subject_id', $subjectId)
            ->where('group_id', $groupId)
            ->where('estado', 'activo')
            ->exists();
            
        if (!$hasSubjectInGroup) {
            return response()->json(['error' => 'No tienes asignada esta materia para este grupo en tus cargas académicas.'], 403);
        }
        
        $grades = $request->grades;
        $updatedGrades = [];
        
        DB::beginTransaction();
        
        try {
            foreach ($grades as $gradeData) {
                $studentId = $gradeData['student_id'];
                $value = $gradeData['nota_final'];
                $observation = $gradeData['observacion'] ?? null;
                
                // Verificar que el estudiante pertenezca al grupo seleccionado
                $student = Student::where('id', $studentId)
                    ->where('group_id', $groupId)
                    ->first();
                
                if (!$student) {
                    DB::rollBack();
                    return response()->json(['error' => 'Estudiante no encontrado en el grupo seleccionado.'], 404);
                }
                
                // Formatear la nota_final para asegurar que tenga exactamente 2 decimales
                $notaFinal = number_format((float)$value, 2, '.', '');
                
                // Actualizar o crear la calificación
                $grade = Grade::updateOrCreate(
                    ['student_id' => $studentId, 'subject_id' => $subjectId],
                    [
                        'nota_final' => $notaFinal,
                        'value' => $notaFinal, // Agregar el campo value que es requerido
                        'observacion' => $observation
                    ]
                );
                
                $updatedGrades[] = $grade;
            }
            
            DB::commit();
            return response()->json(['success' => true, 'grades' => $updatedGrades]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al guardar las calificaciones: ' . $e->getMessage()], 500);
        }
    }
}
