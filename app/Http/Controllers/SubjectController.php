<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\Group;
use App\Models\Teacher;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::with(['groups', 'teachers']);
        
        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('codigo', 'LIKE', "%{$search}%")
                  ->orWhere('descripcion', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtro por estado
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }
        
        // Ordenar por nombre por defecto
        $query->orderBy('nombre', 'asc');
        
        // Paginación
        $subjects = $query->paginate(10);
        
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        return view('subjects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|unique:subjects',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        Subject::create($request->all());
        return redirect()->route('subjects.index')->with('success', 'Materia creada exitosamente.');
    }

    public function show(Subject $subject)
    {
        $subject->load(['groups', 'teachers']);
        return view('subjects.show', compact('subject'));
    }

    public function edit(Subject $subject)
    {
        return view('subjects.edit', compact('subject'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'codigo' => 'required|string|unique:subjects,codigo,' . $subject->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'estado' => 'required|in:activo,inactivo',
        ]);

        $subject->update($request->all());
        return redirect()->route('subjects.index')->with('success', 'Materia actualizada exitosamente.');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Materia eliminada exitosamente.');
    }

    public function assignGroups(Subject $subject)
    {
        $groups = Group::where('estado', 'activo')->get();
        return view('subjects.assign-groups', compact('subject', 'groups'));
    }

    public function updateGroups(Request $request, Subject $subject)
    {
        $request->validate([
            'groups' => 'nullable|array',
            'groups.*' => 'exists:groups,id'
        ]);

        $subject->groups()->sync($request->groups ?? []);
        
        return redirect()->route('subjects.index')
            ->with('success', 'Grupos asignados correctamente a la materia.');
    }

    public function assignTeachers(Subject $subject)
    {
        $teachers = Teacher::all();
        return view('subjects.assign-teachers', compact('subject', 'teachers'));
    }

    public function updateTeachers(Request $request, Subject $subject)
    {
        $request->validate([
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:teachers,id'
        ]);

        $subject->teachers()->sync($request->teachers ?? []);
        
        return redirect()->route('subjects.index')
            ->with('success', 'Docentes asignados correctamente a la materia.');
    }

    public function getAssignedGroups(Subject $subject)
    {
        return response()->json([
            'groups' => $subject->groups->pluck('id')
        ]);
    }

    /**
     * Buscar materias basado en un término de búsqueda
     */
    public function search(Request $request)
    {
        // Método ya no es necesario con DataTables, pero se mantiene por compatibilidad
        $query = $request->input('query');
        $subjects = Subject::with(['groups', 'teachers'])
            ->when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where(function($q) use ($query) {
                    $q->where('nombre', 'LIKE', '%'.$query.'%')
                      ->orWhere('codigo', 'LIKE', '%'.$query.'%')
                      ->orWhere('descripcion', 'LIKE', '%'.$query.'%');
                });
            })
            ->paginate(10);

        return response()->json([
            'subjects' => $subjects->items(),
            'pagination' => [
                'total' => $subjects->total(),
                'per_page' => $subjects->perPage(),
                'current_page' => $subjects->currentPage(),
                'last_page' => $subjects->lastPage(),
                'from' => $subjects->firstItem(),
                'to' => $subjects->lastItem()
            ]
        ]);
    }
}