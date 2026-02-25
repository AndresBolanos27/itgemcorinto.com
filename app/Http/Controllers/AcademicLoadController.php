<?php

namespace App\Http\Controllers;

use App\Models\AcademicLoad;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicLoadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = AcademicLoad::with(['teacher', 'subject', 'group']);
        
        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('teacher', function($query) use ($search) {
                    $query->where('nombre', 'LIKE', "%{$search}%")
                          ->orWhere('apellido', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('subject', function($query) use ($search) {
                    $query->where('nombre', 'LIKE', "%{$search}%");
                })
                ->orWhereHas('group', function($query) use ($search) {
                    $query->where('nombre', 'LIKE', "%{$search}%")
                          ->orWhere('codigo', 'LIKE', "%{$search}%");
                });
            });
        }
        
        // Filtro por estado
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }
        
        // Ordenar por ID de forma descendente por defecto
        $query->orderBy('id', 'desc');
        
        // Paginación
        $academicLoads = $query->paginate(10);
        
        return view('academic-loads.index', compact('academicLoads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::where('estado', 'activo')->get();
        $subjects = Subject::where('estado', 'activo')->get();
        $groups = Group::where('estado', 'activo')->get();
        
        return view('academic-loads.create', compact('teachers', 'subjects', 'groups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_ids' => 'required|array',
            'subject_ids.*' => 'exists:subjects,id',
            'group_id' => 'required|exists:groups,id',
            'estado' => 'required|in:activo,inactivo',
            'periodo' => 'nullable|string|max:20',
            'observaciones' => 'nullable|string',
        ]);

        // Validación: No permitir duplicados de grupo-materia
        foreach ($request->subject_ids as $subjectId) {
            $existsGroupSubject = AcademicLoad::where([
                'subject_id' => $subjectId,
                'group_id' => $request->group_id,
            ])->exists();
            if ($existsGroupSubject) {
                return redirect()->back()
                    ->with('error', 'Ya existe una carga académica para este grupo y materia. No se puede asignar otro docente al mismo grupo y materia.')
                    ->withInput();
            }
        }

        try {
            DB::beginTransaction();
            
            $createdLoads = 0;
            $duplicateLoads = 0;
            
            foreach ($request->subject_ids as $subjectId) {
                // Verificar si ya existe una carga académica con la misma combinación
                $exists = AcademicLoad::where([
                    'teacher_id' => $request->teacher_id,
                    'subject_id' => $subjectId,
                    'group_id' => $request->group_id,
                ])->exists();
                
                if (!$exists) {
                    // Crear la carga académica
                    AcademicLoad::create([
                        'teacher_id' => $request->teacher_id,
                        'subject_id' => $subjectId,
                        'group_id' => $request->group_id,
                        'estado' => $request->estado,
                        'periodo' => $request->periodo,
                        'observaciones' => $request->observaciones,
                    ]);
                    
                    $createdLoads++;
                } else {
                    $duplicateLoads++;
                }
            }
            
            DB::commit();
            
            $message = "Se crearon $createdLoads cargas académicas exitosamente.";
            if ($duplicateLoads > 0) {
                $message .= " Se omitieron $duplicateLoads cargas que ya existían.";
            }
            
            return redirect()->route('academic-loads.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error al crear las cargas académicas: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(AcademicLoad $academicLoad)
    {
        $academicLoad->load(['teacher', 'subject', 'group']);
        
        return view('academic-loads.show', compact('academicLoad'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AcademicLoad $academicLoad)
    {
        $teachers = Teacher::where('estado', 'activo')->get();
        $subjects = Subject::where('estado', 'activo')->get();
        $groups = Group::where('estado', 'activo')->get();
        
        return view('academic-loads.edit', compact('academicLoad', 'teachers', 'subjects', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AcademicLoad $academicLoad)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'group_id' => 'required|exists:groups,id',
            'estado' => 'required|in:activo,inactivo',
            'periodo' => 'nullable|string|max:20',
            'observaciones' => 'nullable|string',
        ]);

        // Verificar si ya existe otra carga académica con la misma combinación (excluyendo la actual)
        $exists = AcademicLoad::where([
            'teacher_id' => $request->teacher_id,
            'subject_id' => $request->subject_id,
            'group_id' => $request->group_id,
        ])->where('id', '!=', $academicLoad->id)->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'Ya existe otra carga académica con esa combinación de docente, materia y grupo.')
                ->withInput();
        }

        try {
            DB::beginTransaction();
            
            $academicLoad->update($request->all());
            
            DB::commit();
            
            return redirect()->route('academic-loads.index')
                ->with('success', 'Carga académica actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error al actualizar la carga académica: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicLoad $academicLoad)
    {
        try {
            $academicLoad->delete();
            
            return redirect()->route('academic-loads.index')
                ->with('success', 'Carga académica eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar la carga académica: ' . $e->getMessage());
        }
    }
    
    /**
     * Asignar cargas académicas para un docente específico.
     */
    public function assignForTeacher(Teacher $teacher)
    {
        $subjects = Subject::where('estado', 'activo')->get();
        $groups = Group::where('estado', 'activo')->get();
        $currentLoads = AcademicLoad::where('teacher_id', $teacher->id)
                                    ->with(['subject', 'group'])
                                    ->get();
        
        return view('academic-loads.assign-for-teacher', compact('teacher', 'subjects', 'groups', 'currentLoads'));
    }
    
    /**
     * Asignar cargas académicas para una materia específica.
     */
    public function assignForSubject(Subject $subject)
    {
        $teachers = Teacher::where('estado', 'activo')->get();
        $groups = Group::where('estado', 'activo')->get();
        $currentLoads = AcademicLoad::where('subject_id', $subject->id)
                                    ->with(['teacher', 'group'])
                                    ->get();
        
        return view('academic-loads.assign-for-subject', compact('subject', 'teachers', 'groups', 'currentLoads'));
    }
    
    /**
     * Asignar cargas académicas para un grupo específico.
     */
    public function assignForGroup(Group $group)
    {
        $teachers = Teacher::where('estado', 'activo')->get();
        $subjects = Subject::where('estado', 'activo')->get();
        $currentLoads = AcademicLoad::where('group_id', $group->id)
                                    ->with(['teacher', 'subject'])
                                    ->get();
        
        return view('academic-loads.assign-for-group', compact('group', 'teachers', 'subjects', 'currentLoads'));
    }
}
