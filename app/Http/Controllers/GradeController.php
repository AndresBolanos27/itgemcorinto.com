<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Group;
use App\Models\Student;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $groups = Group::where('estado', 'activo')->get();
        $students = collect();
        $subjects = collect();

        if ($request->has('group_id')) {
            $group = Group::findOrFail($request->group_id);
            $students = $group->students()->orderBy('apellido')->orderBy('nombre')->get();
            
            // Obtener materias asignadas al grupo a través de cargas académicas
            $subjects = Subject::whereHas('academicLoads', function($query) use ($group) {
                $query->where('group_id', $group->id)
                      ->where('estado', 'activo');
            })->orderBy('nombre')->get();
            
            // Si no hay materias a través de cargas académicas, intentar obtenerlas de la relación directa
            if ($subjects->isEmpty()) {
                $subjects = Subject::whereHas('groups', function($query) use ($group) {
                    $query->where('groups.id', $group->id);
                })->orderBy('nombre')->get();
            }
        }

        return view('grades.index', compact('groups', 'students', 'subjects'));
    }

    public function show(Request $request, Student $student)
    {
        if (!$request->has('subject_id')) {
            return response()->json(['error' => 'Se requiere el ID de la materia'], 400);
        }

        $grade = Grade::where('student_id', $student->id)
                     ->where('subject_id', $request->subject_id)
                     ->first();

        return response()->json(['grade' => $grade]);
    }

    public function edit($studentId, $subjectId)
    {
        $student = Student::findOrFail($studentId);
        $subject = Subject::findOrFail($subjectId);
        $grades = Grade::where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->get()
            ->keyBy('periodo');

        return view('grades.edit', compact('student', 'subject', 'grades'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'grades' => 'required|array',
            'grades.*' => 'required|numeric|min:0|max:5',
            'subject_id' => 'required|exists:subjects,id'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->grades as $periodId => $grade) {
                Grade::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'subject_id' => $request->subject_id,
                        'period_id' => $periodId,
                    ],
                    ['value' => $grade]
                );
            }

            DB::commit();
            return response()->json(['message' => 'Notas actualizadas exitosamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al actualizar las notas'], 500);
        }
    }

    public function batchUpdate(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:students,id',
            'grades.*.nota_final' => 'required|numeric|min:0|max:5',
            'grades.*.observacion' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->grades as $gradeData) {
                // Formatear la nota_final para asegurar que tenga exactamente 2 decimales
                $notaFinal = number_format((float)$gradeData['nota_final'], 2, '.', '');
                
                Grade::updateOrCreate(
                    [
                        'student_id' => $gradeData['student_id'],
                        'subject_id' => $request->subject_id,
                    ],
                    [
                        'nota_final' => $notaFinal,
                        'value' => $notaFinal,
                        'observacion' => $gradeData['observacion'] ?? null
                    ]
                );
            }

            DB::commit();
            return response()->json(['message' => 'Notas actualizadas exitosamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            // Devolver el mensaje de error específico para depuración
            return response()->json(['error' => 'Error al actualizar las notas: ' . $e->getMessage()], 500);
        }
    }
}
