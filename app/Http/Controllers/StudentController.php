<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('group');
        
        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'LIKE', "%{$search}%")
                  ->orWhere('apellido', 'LIKE', "%{$search}%")
                  ->orWhere('correo', 'LIKE', "%{$search}%")
                  ->orWhere('cedula', 'LIKE', "%{$search}%");
            });
        }
        
        // Filtrar por estado
        if ($request->filled('estado')) {
            $estado = $request->get('estado');
            $query->where('estado', $estado);
        }
        
        // Filtrar por grupo asignado
        if ($request->filled('grupo_id')) {
            $grupoId = $request->get('grupo_id');
            $query->where('group_id', $grupoId);
        }
        // Ordenar por nombre por defecto
        $query->orderBy('nombre', 'asc');
        
        // Obtener todos los grupos para el filtro
        $groups = Group::orderBy('nombre', 'asc')->get();
        
        // Paginación con mantenimiento de query string para filtros
        $students = $query->paginate(10)->withQueryString();
        
        return view('students.index', compact('students', 'groups'));
    }

    public function create()
    {
        $groups = Group::all();
        return view('students.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cedula' => 'required|string|max:20|unique:students',
            'correo' => 'required|email|max:255|unique:users,email',
            'celular' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string|max:255',
            'group_id' => 'nullable|exists:groups,id',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->correo,
                'password' => Hash::make($request->password),
                'role' => 'student'
            ]);

            Student::create([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'cedula' => $request->cedula,
                'correo' => $request->correo,
                'celular' => $request->celular,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'direccion' => $request->direccion,
                'group_id' => $request->group_id,
                'user_id' => $user->id
            ]);

            DB::commit();
            return redirect()->route('students.index')->with('success', 'Estudiante creado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear el estudiante: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Student $student)
    {
        $groups = Group::all();
        return view('students.edit', compact('student', 'groups'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'cedula' => ['required', 'string', 'max:20', Rule::unique('students')->ignore($student->id)],
            'correo' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($student->user_id)],
            'celular' => 'required|string|max:20',
            'fecha_nacimiento' => 'required|date',
            'direccion' => 'required|string|max:255',
            'genero' => 'nullable|string|in:masculino,femenino,otro',
            'grupo_etnico' => 'required|string',
            'eps' => 'required|string',
            'acudiente' => 'required|string|max:255',
            'telefono_acudiente' => 'required|string|max:20',
            'estado' => 'required|string|in:activo,inactivo',
            'tipo_documento' => 'required|string',
            'group_id' => 'nullable|exists:groups,id',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $userData = [
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->correo,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $student->user->update($userData);

            $student->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'tipo_documento' => $request->tipo_documento,
                'cedula' => $request->cedula,
                'correo' => $request->correo,
                'celular' => $request->celular,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'direccion' => $request->direccion,
                'genero' => $request->genero,
                'grupo_etnico' => $request->grupo_etnico,
                'eps' => $request->eps,
                'acudiente' => $request->acudiente,
                'telefono_acudiente' => $request->telefono_acudiente,
                'estado' => $request->estado,
                'group_id' => $request->group_id,
            ]);

            DB::commit();
            return redirect()->route('students.index')->with('success', 'Estudiante actualizado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar el estudiante: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Student $student)
    {
        try {
            DB::beginTransaction();

            // Eliminar el usuario asociado
            $student->user->delete();
            
            // El estudiante se eliminará automáticamente debido a la relación y la clave foránea
            
            DB::commit();
            return redirect()->route('students.index')->with('success', 'Estudiante eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar el estudiante: ' . $e->getMessage());
        }
    }
}
