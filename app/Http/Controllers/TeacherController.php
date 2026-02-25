<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::query();
        
        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('apellido', 'LIKE', "%{$search}%")
                  ->orWhere('correo', 'LIKE', "%{$search}%")
                  ->orWhere('cedula', 'LIKE', "%{$search}%")
                  ->orWhere('titulo', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtro por estado
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }
        
        // Ordenar por nombre por defecto
        $query->orderBy('nombre', 'asc');
        
        // Paginación
        $teachers = $query->paginate(10);
        
        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'cedula' => ['required', 'string', 'unique:teachers'],
            'correo' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'celular' => ['required', 'string', 'max:20'],
            'titulo' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date'],
            'direccion' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->correo,
                'password' => Hash::make($request->password),
                'role' => 'teacher'
            ]);

            $teacher = Teacher::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'cedula' => $request->cedula,
                'correo' => $request->correo,
                'celular' => $request->celular,
                'titulo' => $request->titulo,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'direccion' => $request->direccion,
                'user_id' => $user->id
            ]);

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Docente creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el docente. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'cedula' => ['required', 'string', 'unique:teachers,cedula,' . $teacher->id],
            'correo' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $teacher->user_id],
            'celular' => ['required', 'string', 'max:20'],
            'titulo' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date'],
            'direccion' => ['required', 'string'],
        ];

        // Hacer que los campos de contraseña sean opcionales y coherentes
        $rules['password'] = ['nullable', 'string', 'min:8'];
        $rules['password_confirmation'] = ['nullable', 'same:password'];

        $request->validate($rules);

        try {
            DB::beginTransaction();

            $teacher->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'cedula' => $request->cedula,
                'correo' => $request->correo,
                'celular' => $request->celular,
                'titulo' => $request->titulo,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'direccion' => $request->direccion,
            ]);

            $userData = [
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->correo,
            ];

            // Solo sobreescribir password si se proporcionó
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $teacher->user->update($userData);

            DB::commit();

            return redirect()->route('teachers.index')
                ->with('success', 'Docente actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el docente. ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Teacher $teacher)
    {
        try {
            $teacher->delete();
            return redirect()->route('teachers.index')
                ->with('success', 'Docente eliminado exitosamente.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar el docente.');
        }
    }

    public function assignGroups(Teacher $teacher)
    {
        $groups = Group::where('estado', 'activo')->get();
        return view('teachers.assign-groups', compact('teacher', 'groups'));
    }

    public function updateGroups(Request $request, Teacher $teacher)
    {
        $request->validate([
            'groups' => 'nullable|array',
            'groups.*' => 'exists:groups,id'
        ]);

        $teacher->groups()->sync($request->groups ?? []);
        
        return redirect()->route('teachers.index')
            ->with('success', 'Grupos asignados correctamente al docente.');
    }

    public function assignSubjects(Teacher $teacher)
    {
        $subjects = Subject::where('estado', 'activo')->get();
        return view('teachers.assign-subjects', compact('teacher', 'subjects'));
    }

    public function updateSubjects(Request $request, Teacher $teacher)
    {
        $request->validate([
            'subjects' => 'nullable|array',
            'subjects.*' => 'exists:subjects,id'
        ]);

        $teacher->subjects()->sync($request->subjects ?? []);
        
        return redirect()->route('teachers.index')
            ->with('success', 'Materias asignadas correctamente al docente.');
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['groups', 'subjects']);
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Buscar docentes basado en un término de búsqueda
     * Mantenido para compatibilidad con código anterior
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        $teachers = Teacher::when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where(function($q) use ($query) {
                    $q->where('nombre', 'LIKE', '%'.$query.'%')
                      ->orWhere('apellido', 'LIKE', '%'.$query.'%')
                      ->orWhere('cedula', 'LIKE', '%'.$query.'%')
                      ->orWhere('correo', 'LIKE', '%'.$query.'%')
                      ->orWhere('titulo', 'LIKE', '%'.$query.'%')
                      ->orWhere('tipo_documento', 'LIKE', '%'.$query.'%')
                      ->orWhere('eps', 'LIKE', '%'.$query.'%')
                      ->orWhere('pension', 'LIKE', '%'.$query.'%')
                      ->orWhere('caja_compensacion', 'LIKE', '%'.$query.'%');
                });
            })
            ->paginate(10);

        return response()->json([
            'teachers' => $teachers->items(),
            'pagination' => [
                'total' => $teachers->total(),
                'per_page' => $teachers->perPage(),
                'current_page' => $teachers->currentPage(),
                'last_page' => $teachers->lastPage(),
                'from' => $teachers->firstItem(),
                'to' => $teachers->lastItem()
            ]
        ]);
    }
}
