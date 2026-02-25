<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Admin::query();
        
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
        
        // Filtrar por estado
        if ($request->filled('estado')) {
            $estado = $request->get('estado');
            $query->where('estado', $estado);
        }
        
        // Ordenar por nombre por defecto
        $query->orderBy('nombre', 'asc');
        
        // Paginación
        $admins = $query->paginate(10)->withQueryString();
        
        return view('admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'cedula' => ['required', 'string', 'unique:admins'],
            'correo' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'celular' => ['required', 'string', 'max:20'],
            'titulo' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date'],
            'direccion' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Usar una transacción para asegurar que ambos registros se creen o ninguno
        try {
            \DB::beginTransaction();
            
            // Crear el usuario primero
            $user = User::create([
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->correo,
                'password' => Hash::make($request->password),
                'role' => 'admin'
            ]);
            
            // Luego crear el registro de administrador relacionado
            $admin = Admin::create([
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
            
            \DB::commit();
            
            return redirect()->route('admins.index')
                ->with('success', 'Administrador creado exitosamente.');
        } catch (\Exception $e) {
            \DB::rollBack();
            
            // Para depuración - en producción nunca mostrar el mensaje de error completo
            return redirect()->back()
                ->with('error', 'Error al crear el administrador: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Admin $admin)
    {
        return view('admins.edit', compact('admin'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'cedula' => ['required', 'string', Rule::unique('admins')->ignore($admin->id)],
            'correo' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($admin->user_id)],
            'celular' => ['required', 'string', 'max:20'],
            'titulo' => ['required', 'string', 'max:255'],
            'fecha_nacimiento' => ['required', 'date'],
            'direccion' => ['required', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $admin->user->update([
                'name' => $request->nombre . ' ' . $request->apellido,
                'email' => $request->correo,
            ]);

            if ($request->filled('password')) {
                $admin->user->update([
                    'password' => Hash::make($request->password),
                ]);
            }

            $admin->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'cedula' => $request->cedula,
                'correo' => $request->correo,
                'celular' => $request->celular,
                'titulo' => $request->titulo,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'direccion' => $request->direccion,
            ]);

            return redirect()->route('admins.index')
                ->with('success', 'Administrador actualizado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al actualizar el administrador.')
                ->withInput();
        }
    }

    public function destroy(Admin $admin)
    {
        if ($admin->id === 1) {
            return redirect()->route('admins.index')
                ->with('error', 'No se puede eliminar el administrador principal.');
        }

        try {
            $admin->user->delete(); // Esto también eliminará el admin por la relación cascade
            return redirect()->route('admins.index')
                ->with('success', 'Administrador eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admins.index')
                ->with('error', 'Error al eliminar el administrador.');
        }
    }
}
